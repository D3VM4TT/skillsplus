<?php

namespace Craft;

class Lantra_PaypalController extends Lantra_BaseController
{
    public $allowAnonymous = array('actionPayment');

    /**
     * Receives confirmation from PayPal IPN script
     *
     * @throws mixed
     */
    public function actionPayment(){
        // get variables from PayPal
        $userId = Craft::$app->request->getParam('custom');
        $payerEmail = Craft::$app->request->getParam('payer_email');
        $paymentAmount = Craft::$app->request->getParam('mc_gross');
        $transactionId = Craft::$app->request->getParam('txn_id');
        // get the user object
        $user = craft()->users->getUserById($userId);
        if ( ! $user) {
            Craft::log('Invalid user ID sent from PayPal.',LogLevel::Error, true, 'paypal', 'lantra');
            die();
        }
        // create payment block
        $payment = new MatrixBlockModel();
        $payment->fieldId = 77;
        $payment->typeId = 6;
        $payment->ownerId = $user->id;
        $payment->getContent()->setAttributes([
                'payer_email' => $payerEmail,
                'mc_gross' => $paymentAmount,
                'txn_id' => $transactionId,
            ]
        );
        // save payment
        craft()->matrix->saveBlock($payment);
        // add user to user group
        Lantra::$app->users->activateIndividualUser($user);
        // add to Lantra company
        Lantra::$app->users->addUserToIndividualCompany($user);
        // add to Lantra job role
        Lantra::$app->users->addUserToIndividualJobRole($user);
        // set account expiry
        $days = Lantra::$app->licences->getIndividualLicenceDays();
        Lantra::$app->users->setUserExpiryDate($user, $days);
        // log success message
        Craft::log('IPN request received [' . $payerEmail . ']',LogLevel::Info, true, 'paypal', 'lantra');
        die();
    }
}