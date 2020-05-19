<?php
/**
 * Lantra Skills Plus for Craft CMS 3.x
 *
 * @link      https:##coffeebean.design
 * @copyright Copyright (c) 2020 Coffee Bean Design
 */

namespace lantra\sp\controllers;

use Craft;
use craft\elements\MatrixBlock;

use lantra\sp\Plugin as Lantra;
use lantra\sp\contracts\PaypalIpn;

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

        $ipn = new PaypalIpn;
        if (getenv('ENVIRONMENT') != 'production') {
            $ipn->useSandbox();
        }
        if (!$ipn->verifyIpn()) {
            Craft::error('PayPal fail to verify IPN.', __METHOD__);
            Craft::$app->response->setStatusCode(500);
            return $this->asJson(['success' => 'false']);
        }

        $custom = json_decode(Craft::$app->request->getParam('custom'));
        $ipnRecord = $this->ipnRecord(
            Craft::$app->request->getParam('payer_email'),
            Craft::$app->request->getParam('mc_gross'),
            Craft::$app->request->getParam('txn_id')
        );
        if (!isset($custom->userId) || null == $user = Craft::$app->users->getUserById($custom->userId)) {
            Craft::error('PayPal failed to validate user.', __METHOD__);
            return $this->asJson(['success' => 'false']);
        }
        if (isset($custom->packageId)) {
            Lantra::$app->packages->setPaid($custom->packageId, $ipnRecord);
        }
        return $this->asJson(['success' => 'true']);
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
        ## create payment block
        $payment = new MatrixBlock();
        $payment->fieldId = 77;
        $payment->typeId = 6;
        $payment->ownerId = $user->id;
        $payment->setAttributes([
            'payer_email' => $payerEmail,
            'mc_gross' => $paymentAmount,
            'txn_id' => $transactionId,
        ]);
        ## save payment
        Craft::$app->elements->saveElement($payment);
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