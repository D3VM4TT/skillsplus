<?php
/**
 * Lantra Skills Plus for Craft CMS 3.x
 *
 * @link      https://coffeebean.design
 * @copyright Copyright (c) 2020 Coffee Bean Design
 */

namespace lantra\sp\controllers;

use Craft;
use craft\web\Controller;

class BaseController extends Controller {

    /**
     * @param $message
     */
    public function _returnError($message)
    {
        $this->_returnMessage($message, false);
    }

    /**
     * @param $message
     * @param bool $success
     * @param bool $redirect
     * @return \yii\web\Response
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\BadRequestHttpException
     */
    public function _returnMessage($message, $success = true, $redirect = false)
    {
         if (Craft::$app->getRequest()->getIsAjax()) {
            return $this->asJson(['success' => $success, 'message' => $message, 'redirect' => $redirect]);
        }

        if ($success) {
            Craft::$app->session->setNotice($message);
        }
        else {
            Craft::$app->session->setError($message);
        }

        if ($redirect)
        {
            return $this->redirect($redirect);
        }
        $this->redirectToPostedUrl();
    }
}
