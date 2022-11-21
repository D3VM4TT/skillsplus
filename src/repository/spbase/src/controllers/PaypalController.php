<?php
/**
 * Lantra Skills Plus for Craft CMS 3.x
 *
 * @link      https://coffeebean.design
 * @copyright Copyright (c) 2020 Coffee Bean Design
 */

namespace lantra\spbase\controllers;

use Craft;
use craft\elements\User;
use craft\web\Controller;
use lantra\spbase\Module;

class PaypalController extends Controller
{
    public $enableCsrfValidation = false;
    public $allowAnonymous = true;

    /**
     * @param $userId
     * @param $reference
     * @return \craft\web\Response
     */
    public function actionProcess($userId, $reference)
    {
        if (null == $user = User::findOne($userId)) {
            Craft::$app->session->setError('actionProcess() invalid user');
            $this->redirect('/');
        }
        $url = Module::$plugin->spbase->processPayment($user, $reference);

        $this->response->redirect($url);
    }
}