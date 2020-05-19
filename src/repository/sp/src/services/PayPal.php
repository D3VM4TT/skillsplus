<?php
/**
 * Lantra Skills Plus for Craft CMS 3.x
 *
 * @link      https://coffeebean.design
 * @copyright Copyright (c) 2020 Coffee Bean Design
 */

namespace lantra\sp\services;

use Craft;
use craft\base\Component;
use craft\helpers\UrlHelper;
use craft\helpers\FileHelper;

use lantra\sp\helpers\LantraHelper;

class PayPal extends Component
{
    private $certStorage;
    private $payPalCertPath;
    private $lantraCertPath;
    private $lantraKeyPath;
    private $certId;

    private $business;
    private $formUrl;

    public function __construct(array $config = [])
    {
        parent::__construct($config);
    }

    /**
     * @param string $product
     * @param int $amount
     * @param string $label
     * @param string $return
     * @param array $custom
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @throws \yii\base\ErrorException
     * @throws \yii\base\Exception
     */
    public function getButton($product = '', $amount = 0, $label = 'Pay Now', $return = '', $custom = [])
    {
        $this->_setup();

        $custom['environment'] = getenv('ENVIRONMENT');
        $custom['site'] = getenv('SITE');
        $custom['userId'] = Craft::$app->getUser()->id;

        $data = [
            'business'      => $this->business,
            'cmd'           => '_cart',
            'upload'        => '1',
            'currency_code' => 'GBP',
            'notify_url'    => UrlHelper::siteUrl('sp/paypal/ipn'),
            'item_name_1'   => $product,
            'amount_1'      => $amount,
            'cancel_return' => UrlHelper::siteUrl(),
            'custom'        => json_encode($custom)
        ];
        if (null == $encrypted = $this->_encryptCart($data)) {
            return '[[ PayPal is not yet configured ]]';
        }
        $variables = [
            'url' => $this->formUrl,
            'label' => $label,
            'encrypted' => $encrypted,
            'return' => $return
        ];

        return LantraHelper::renderCpTemplate('sp/forms/paypal', $variables);
    }

    /**
     * @param null $ipn
     * @return bool|null
     */
    public function verifyIpn($ipn = null)
    {
        if (empty($ipn)){
            $ipn = $_POST;
        }
        if (empty($ipn['verify_sign'])){
            Craft::error('PayPal verify_sign empty.', __METHOD__);
        }
        try {
            $ipn['cmd'] = '_notify-validate';
            $paypalHost = (empty($ipn['test_ipn']) ? 'www' : 'www.sandbox') . '.paypal.com';
            $cURL = curl_init();
            curl_setopt($cURL, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($cURL, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($cURL, CURLOPT_URL, "https://{$paypalHost}/cgi-bin/webscr");
            curl_setopt($cURL, CURLOPT_ENCODING, 'gzip');
            curl_setopt($cURL, CURLOPT_BINARYTRANSFER, true);
            curl_setopt($cURL, CURLOPT_POST, true);
            curl_setopt($cURL, CURLOPT_POSTFIELDS, $ipn);
            curl_setopt($cURL, CURLOPT_HEADER, false);
            curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($cURL, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
            curl_setopt($cURL, CURLOPT_FORBID_REUSE, true);
            curl_setopt($cURL, CURLOPT_FRESH_CONNECT, true);
            curl_setopt($cURL, CURLOPT_CONNECTTIMEOUT, 30);
            curl_setopt($cURL, CURLOPT_TIMEOUT, 60);
            curl_setopt($cURL, CURLINFO_HEADER_OUT, true);
            curl_setopt($cURL, CURLOPT_HTTPHEADER, array(
                'Connection: close',
                'Expect: ',
            ));
            $response = curl_exec($cURL);
            $status = (int)curl_getinfo($cURL, CURLINFO_HTTP_CODE);
            curl_close($cURL);
            if (empty($response) or !preg_match('~^(VERIFIED|INVALID)$~i', $response = trim($response)) or !$status) {
                Craft::error('PayPal response error.', __METHOD__);
                return null;
            }
            if(intval($status / 100) != 2){
                Craft::error('PayPal status error ' . $status . '.', __METHOD__);
                return false;
            }
            return !strcasecmp($response, 'VERIFIED');
        }
        catch(\Exception $e) {
            Craft::error($e->getMessage(), __METHOD__);
            return null;
        }
    }


    /**
     * @throws \yii\base\ErrorException
     * @throws \yii\base\Exception
     */
    private function _setup()
    {
        if (getenv('ENVIRONMENT') == 'production') {
            $this->business = 'accounts@lantra.co.uk';
            $this->formUrl = 'https://www.paypal.com/cgi-bin/webscr';
        }
        else {
            $this->business = 'lantra-business@thisistraffic.co.uk';
            $this->formUrl = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
        }

        $certName = 'lantra-public-' . getenv('SITE') . '-' . getenv('ENVIRONMENT') . '.pem';

        $this->certId = LantraHelper::setting('payPalCertId');

        ## sync the certificates to storage
        $payPalCert = trim(LantraHelper::setting('payPalPublicCert'));
        $lantraCert = trim(LantraHelper::setting('payPalLantraCert'));
        $lantraKey = trim(LantraHelper::setting('payPalLantraKey'));

        $this->certStorage = Craft::$app->path->getStoragePath() . '/paypal/';

        if ($this->certId && $payPalCert && $lantraCert && $lantraKey) {
            FileHelper::createDirectory($this->certStorage);
            $this->lantraCertPath = $this->certStorage . $certName;
            $this->lantraKeyPath = $this->certStorage . 'lantra-private.pem';
            $this->payPalCertPath = $this->certStorage . 'paypal-public.pem';
            FileHelper::writeToFile($this->lantraCertPath, $lantraCert);
            FileHelper::writeToFile($this->lantraKeyPath, $lantraKey);
            FileHelper::writeToFile($this->payPalCertPath, $payPalCert);
        }
    }

    /**
     * @param $data
     * @return bool|string
     * @throws \Exception
     */
    private function _encryptCart($data)
    {
        if (!$this->_checkCertificates()) {
            return null;
        }
        $data['cert_id'] = $this->certId;
        $request = '';
        foreach ($data as $key => $value) {
            if (!empty($value)) {
                $request .= "$key=$value\n";
            }
        }
        $openSslCommand = "(openssl smime -sign -signer " .
            "{$this->lantraCertPath} -inkey " .
            "{$this->lantraKeyPath} -outform der -nodetach -binary <<_EOF_" .
            "\n$request\n_EOF_\n) | openssl smime -encrypt ".
            "-des3 -binary -outform pem {$this->payPalCertPath}";

        exec($openSslCommand, $output, $error);
        return $error ? false : implode("\n", $output);
    }

    /**
     * @throws \Exception
     */
    private function _checkCertificates()
    {
        if (!is_file($this->payPalCertPath) || !is_file($this->lantraKeyPath) || !is_file($this->lantraCertPath)) {
            throw new \Exception('PayPal certificates not found. Check access to ' . $this->certStorage);
            return false;
        }
        return true;
    }
}