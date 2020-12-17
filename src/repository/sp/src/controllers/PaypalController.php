<?php
/**
 * Lantra Skills Plus for Craft CMS 3.x
 *
 * @link      https:##coffeebean.design
 * @copyright Copyright (c) 2020 Coffee Bean Design
 */

namespace lantra\sp\controllers;

use Craft;
use craft\elements\Entry;
use lantra\sp\helpers\LantraHelper;
use lantra\sp\Plugin as Lantra;

class PaypalController extends BaseController
{
    public $enableCsrfValidation = false;
    public $allowAnonymous = ['ipn', 'payment'];

    /**
     * Receives confirmation from PayPal IPN script (new for packages)
     *
     * @throws mixed
     */
    public function actionIpn()
    {
        Craft::info("IPN Received:  ".json_encode($_POST), __METHOD__);

        if (!Lantra::$app->paypal->ipn->verifyIpn()) {
            Craft::error('PayPal fail to verify IPN.', __METHOD__);
            Craft::$app->response->setStatusCode(500);
            return $this->asJson(['success' => 'false']);
        }

        $custom = json_decode(Craft::$app->request->getParam('custom'));
        $payerEmail = Craft::$app->request->getParam('payer_email');
        $paymentAmount = Craft::$app->request->getParam('mc_gross');
        $transactionId = Craft::$app->request->getParam('txn_id');

        ## validate user
        if (!isset($custom->userId) || null == $user = Craft::$app->users->getUserById($custom->userId)) {
            Craft::error('PayPal failed to validate user [' . ($custom ? $custom->userId : 'NULL') . ']', __METHOD__);
            return $this->asJson(['success' => 'false']);
        }
        ## handle membership
        if (isset($custom->isMembership) && $user->isInGroup('usersMembershipPending')) {
            ## add user payment
            LantraHelper::addUserPayment($user, $payerEmail, $paymentAmount, $transactionId);
            $group = Craft::$app->userGroups->getGroupByHandle('users');
            Craft::$app->users->assignUserToGroups($user->id, [$group->id]);
        }

        ## handle successful package payment
        if (isset($custom->packageId)) {
            if (null == $package = Entry::findOne($custom->packageId)) {
                Craft::error('PayPal Invalid package ID [' . $custom->packageId . ']', __METHOD__);
                return $this->asJson(['success' => 'false']);
            }
            LantraHelper::addUserPayment($package, $payerEmail, $paymentAmount, $transactionId);
            $package->setFieldValue('packagePaid', true);
            $package->save();
            Craft::info("PayPal payment received for package #" . $package->id, __METHOD__);

            ## move pending users
            if ($user->isInGroup('usersTaskbookPending')) {
                $group = Craft::$app->userGroups->getGroupByHandle('users');
                Craft::$app->users->assignUserToGroups($user->id, [$group->id]);
                ## add user to job role
                $userRole = $package->moduleGroup->userRole->count() ? $package->moduleGroup->userRole->one() : LantraHelper::setting('taskbookJobRole');
                if ($userRole) {
                    $user->setFieldValue('userRole', [$userRole->id]);
                    Craft::$app->elements->saveElement($user);
                }
            }

            ## send notification to scheme managers
            Lantra::$app->notify->sendNewPackage($package);
        }
        return $this->asJson(['success' => 'true']);
    }

    /**
     * @param null $entryId
     * @return \yii\web\Response
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     */
    public function actionPay($entryId = null)
    {
        if ($entryId) {
            $entry = Craft::$app->entries->getEntryById($entryId);
            if ($entry && $entry->section->handle == 'packages') {
                $entry->setFieldValue('packagePaid', 1);
                Craft::$app->elements->saveElement($entry);
                $variables = [
                    'isTaskbooks' => true,
                    'entryId' => $entryId,
                    'redirect' => '/cpd/' . $entry->authorId . '/taskbooks/manage'
                ];
            }
        }
        else {
            $variables = [
                'isMembership' => true,
                'entryId' => null,
                'redirect' => '/'
            ];
        }
        return $this->renderTemplate('_paypal/payment', $variables);
    }

    /**
     * @return \yii\web\Response
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\BadRequestHttpException
     */
    public function actionVerifyPayment()
    {
        $this->requirePostRequest();
        $this->requireLogin();
        if (null == $entryId = Craft::$app->request->getParam('entryId')) {
            $user = Craft::$app->getUser()->getIdentity();
            return $this->_returnMessage($user->userPayments->count(), true);
        }
        if (null == $entry = Craft::$app->elements->getElementById($entryId)) {
            return $this->_returnError('Invalid Entry ID');
        }
        return $this->_returnMessage($entry->userPayments->count(), true);
    }

    /**
     * Receives confirmation from PayPal IPN script (old for individual buttons)
     *
     * @throws mixed
     */
    public function actionPayment()
    {
        ## get variables from PayPal
        $userId = Craft::$app->request->getParam('custom');
        $payerEmail = Craft::$app->request->getParam('payer_email');
        $paymentAmount = Craft::$app->request->getParam('mc_gross');
        $transactionId = Craft::$app->request->getParam('txn_id');
        ## get the user object
        $user = Craft::$app->users->getUserById($userId);
        if ( ! $user) {
            Craft::log('Invalid user ID sent from PayPal.',LogLevel::Error, true, 'paypal', 'lantra');
            die();
        }
        ## add user payment
        LantraHelper::addUserPayment($user, $payerEmail, $paymentAmount, $transactionId);
        ## add user to user group
        Lantra::$app->users->activateIndividualUser($user);
        ## add to Lantra company
        Lantra::$app->users->addUserToIndividualCompany($user);
        ## add to Lantra job role
        Lantra::$app->users->addUserToIndividualJobRole($user);
        ## set account expiry
        $days = Lantra::$app->licences->getIndividualLicenceDays();
        Lantra::$app->users->setUserExpiryDate($user, $days);
        ## log success message
        Craft::info('IPN request received [' . $payerEmail . ']',__METHOD__);
        die();
    }

    /**
     * @param $payerEmail
     * @param $paymentAmount
     * @param $transactionId
     * @return string
     */
    private function ipnRecord($payerEmail, $paymentAmount, $transactionId)
    {
        return json_encode([$payerEmail, $paymentAmount, $transactionId]);
    }
}