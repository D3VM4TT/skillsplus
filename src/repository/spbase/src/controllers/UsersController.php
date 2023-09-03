<?php
/**
 * Lantra Skills Plus for Craft CMS 3.x
 *
 * @link      https://coffeebean.design
 * @copyright Copyright (c) 2020 Coffee Bean Design
 */

namespace lantra\spbase\controllers;

use craft\elements\User;
use craft\web\Controller;

use lantra\spbase\jobs\ResaveUsersJob;
use lantra\spbase\jobs\SetRenewalMonthJob;
use craft\helpers\Queue;

class UsersController extends Controller {

    public $allowAnonymous = true;
    public $enableCsrfValidation = false;

    /**
     * @param string $action
     * @return \yii\web\Response
     */
    public function actionInfo($action = 'count')
    {
        $criteria = User::find();
        $criteria->group('users');
        if (null != $this->request->getParam('notLicenced', null)) {
            $criteria->userNotLicenced(true);
        }
        elseif (null != $this->request->getParam('admin', null)) {
            $criteria->admin(1);
        }
        if (null == $status = $this->request->getParam('status')) {
            $criteria->anyStatus();
        }
        else {
            $criteria->status($status);
        }
        $result = $action == 'count' ? $criteria->count() : $criteria->ids();
        return $this->response($result);
    }

    /**
     *
     */
    public function actionResave()
    {
        $resaveUsersJob = new ResaveUsersJob([
            'hasLicence' => false,
            'userId' => $this->request->getParam('userId')
        ]);

        Queue::push($resaveUsersJob);
        return $this->response('Resave user(s) added to queue.');
    }

    /**
     *
     */
    public function actionSetRenewalMonth()
    {
        $setRenewalMonthJob = new SetRenewalMonthJob([
            'userId' => $this->request->getParam('userId')
        ]);

        Queue::push($setRenewalMonthJob);
        return $this->response('Set renewal month(s) added to queue.');
    }

    /**
     * @param bool $success
     * @param string $message
     * @return \yii\web\Response
     */
    public function response($message = '', $success = true)
    {
        return $this->asJson([
            'success' => $success,
            'message' => $message
        ]);
    }
}