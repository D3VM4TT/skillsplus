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
        $userId = craft()->request->getParam('custom');
        $payerEmail = craft()->request->getParam('payer_email');
        $paymentAmount = craft()->request->getParam('mc_gross');
        $transactionId = craft()->request->getParam('txn_id');
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
        craft()->lantra_users->activateIndividualUser($user);
        // add to Lantra team
        craft()->lantra_users->addUserToIndividualTeam($user);
        // set account expiry
        $days = craft()->lantra_licence->getIndividualLicenceDays();
        craft()->lantra_users->setUserExpiryDate($user, $days);
        // log success message
        Craft::log('IPN request received [' . $payerEmail . ']',LogLevel::Info, true, 'paypal', 'lantra');
        die();
    }
}