<?php
/**
 * Lantra Skills Plus for Craft CMS 3.x
 *
 * @link      https:##coffeebean.design
 * @copyright Copyright (c) 2020 Coffee Bean Design
 */

namespace lantra\sp\controllers;

use Craft;
use craft\elements\User;
use lantra\sp\Plugin as Lantra;

class PaypalController extends BaseController
{
    public $enableCsrfValidation = false;
    public $allowAnonymous = true;

    public function actionProcess($paymentId, $userId = null)
    {
        ## $this->requireLogin();
        if (!$userId) {
            $userId = Craft::$app->getUser()->getIdentity()->id;
        }
        if (null == $user = User::findOne($userId)) {
            $this->_returnMessage('actionProcess() invalid user', false, '/');
        }
        $url = Lantra::$app->spbase->processPayment($user, $paymentId);
        $this->response->redirect($url);
    }
}