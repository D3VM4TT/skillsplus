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