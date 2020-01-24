<?php

namespace Craft;

class Lantra_BaseController extends BaseController {

    /**
     * @param $message
     */
    public function _returnError($message) {
        $this->_returnMessage($message, FALSE);
    }

    /**
     * @param $message
     * @param bool $success
     * @param bool $redirect
     */
    public function _returnMessage($message, $success = TRUE, $redirect = FALSE) {
         if(craft()->request->isAjaxRequest()) {
            craft()->controller->returnJson(['success' => $success, 'message' => $message, 'redirect' => $redirect]);
        }
        else {
            if ($success) {
                craft()->userSession->setNotice($message);
            }
            else {
                craft()->userSession->setError($message);
            }
            if ($redirect) {
                craft()->request->redirect($redirect);
            }
            else {
                craft()->controller->redirectToPostedUrl();
            }
        }
    }
}
