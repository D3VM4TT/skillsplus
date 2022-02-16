<?php
/**
 * Lantra Skills Plus for Craft CMS 3.x
 *
 * @link      https://coffeebean.design
 * @copyright Copyright (c) 2020 Coffee Bean Design
 */

namespace lantra\spbase\services;

use Craft;

use craft\elements\Entry;
use craft\elements\User;
use craft\helpers\Json;
use craft\helpers\UrlHelper;
use GuzzleHttp\Exception\GuzzleException;
use lantra\sp\Plugin as Lantra;
use lantra\spbase\Module;

class SpBase
{
    /**
     * @var
     */
    public $client;

    /**
     *
     */
    public function __construct()
    {
        $this->client = new SpBaseClient();
    }

    /**
     *
     */
    public function processPayment(User $user, $paymentId)
    {
        $uri = '/';

        if (null == $payment = $this->getPayment($user->id, $paymentId)) {
            Module::error('processPayment() invalid payment');
            return $uri;
        }

        $meta = Json::decodeifJson($payment->meta, true);
        $uri = isset($meta['redirect']) ? $meta['redirect'] : '/';

        ## handle packages
        if (isset($meta['packageId'])) {
            $this->processTaskbookPayment($meta);
        }
        ## handle membership
        elseif (isset($meta['isMembership']) && $user->isInGroup('usersMembershipPending')) {
            $this->processMembershipPayment($user);
        }

        $this->setPaymentProcessed($user->id, $payment->id);
        return $uri;
    }

    /**
     * @param User $user
     * @throws \Throwable
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\SyntaxError
     */
    private function processMembershipPayment(User $user)
    {
        $group = Craft::$app->userGroups->getGroupByHandle('users');
        Craft::$app->users->assignUserToGroups($user->id, [$group->id]);
        Lantra::$app->notify->sendNewMembership($user);
    }

    /**
     * @param $meta
     */
    private function processTaskbookPayment($meta)
    {
        if (null == $package = Entry::findOne($meta['packageId'])) {
            Module::error('processTaskbookPayment() invalid package id [' . $meta['packageId'] . ']');
        }

        ## handle module groups
        if (isset($meta['moduleGroupIds'])) {
            $package->payModuleGroups($meta['moduleGroupIds']);
        }
        else {
            $package->setFieldValue('packagePaid', true);
        }
        $package->save();
    }

    /**
     *
     */
    public function getSite($cache = true)
    {
        $site = $this->client->getSite($cache);
        return $site;
    }

    /**
     *
     */
    public function log($element, $action, $comment = '')
    {
        $user = Craft::$app->getUser()->getIdentity();
        $log = [
            'userId' => $user ? $user->id : 0,
            'action' => $action,
            'comment' => $comment
        ];
        return $this->client->saveProcess($element, ['log' => $log]);
    }

    /**
     * @param $userId
     * @return bool
     */
    public function validateLicence($userId): bool
    {
        $licence = $this->getLicence($userId);
        return $licence->valid;
    }

    /**
     * @param $userId
     * @param bool $create
     * @return \lantra\spbase\models\Licence
     */
    public function getLicence($userId, $create = true)
    {
        $licence = $this->client->getLicence($userId);

        ## create new licence
        if ($create && !$licence->id) {
            Module::log('create user licence [' . $userId . ']');
            $this->client->saveLicence($userId);
            $licence = $this->getLicence($userId, false);
            $this->log($licence,'created');
            return $licence;
        }

        return $licence;
    }

    /**
     * @param $userId
     */
    public function cancelLicence($userId)
    {
        $licence = $this->client->getLicence($userId);

        if ($licence->id) {
            if (!$this->client->suspendEntry($licence)) {

            }
            $this->log($licence, 'cancelled');
        }
    }

    /**
     * @param $userId
     * @param $month
     * @param $postDate
     * @param array $meta
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function updateLicence($userId, $month, $postDate, $meta = [])
    {
        ## create if it doesn't exist
        $licence = $this->getLicence($userId);
        $this->client->saveLicence($userId, $month, $postDate, $meta, $licence->id);
    }

    /**
     * @param $userId
     * @param array $process
     */
    public function processLicence($userId, $process = [])
    {
        $licence = $this->getLicence($userId);
        $this->client->saveProcess($licence, $process);
    }

    /**
     * @param $userId
     * @return array
     */
    public function getPayments($userId)
    {
        $licence = $this->getLicence($userId);
        return $licence->valid ? $licence->payments : [];
    }

    /**
     * @param $userId
     * @return array
     */
    public function getPayment($userId, $paymentId)
    {
        $payments = $this->getPayments($userId);
        foreach ($payments as $payment) {
            if ($payment->id == $paymentId) {
                return $payment;
            }
        }
        return null;
    }

    /**
     * @param $userId
     * @param $amount
     * @param $reference
     * @param array $meta
     * @param string $label
     * @return \Psr\Http\Message\ResponseInterface|string
     * @throws GuzzleException
     */
    public function getPaypalButton($userId, $amount, $meta = [], $label = 'Pay Now')
    {
        $licence = $this->getLicence($userId);

        return $this->client->request('spbase/payment/button', [
                'licenceId' => $licence->id,
                'amount' => $amount,
                'meta' => $meta,
                'label' => $label
            ]);
    }

    /**
     * @param $userId
     * @param $amount
     * @param $reference
     * @return bool
     */
    public function addPayment($userId, $method, $amount, $reference, $meta = [])
    {
        $licence = $this->getLicence($userId);
        $payment = [
            'method' => $method,
            'amount' => $amount,
            'reference' => $reference,
            'meta' => Json::encode($meta)
        ];
        return $this->client->saveProcess($licence, ['payment' => $payment]);
    }

    /**
     * @param $userId
     * @param $paymentId
     * @return bool
     */
    public function setPaymentProcessed($userId, $paymentId)
    {
        $licence = $this->getLicence($userId);
        $payment = [
            'id' => $paymentId,
            'isProcessed' => true
        ];
        return $this->client->saveProcess($licence, ['payment' => $payment]);
    }

    /**
     *
     */
    public function getCompany($companyId, $create = true)
    {
        $company = $this->client->getCompany($companyId);

        if (!$company) {
            ## create new company
            $this->client->saveCompany(null, ['companyId' => $companyId]);
            return $this->getCompany($companyId, false);
        }

        return $company;
    }

    /**
     *
     */
    public function updateCompany($companyId, $data = [])
    {
        $company = $this->client->getLicence($companyId);
        if ($company->valid) {
            $this->client->saveCompany($company->id, $data);
        }
    }
}