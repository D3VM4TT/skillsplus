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

use lantra\spbase\Module;

class BaseController extends Controller {

    public $allowAnonymous = true;
    public $enableCsrfValidation = false;

    /**
     * @param string $action
     * @return \yii\web\Response
     */
    public function actionUsers($action = 'count')
    {
        $criteria = User::find();
        $criteria->group = ['users', 'companyManagers', 'teamManagers'];
        $criteria->admin(0);
        $criteria->userNotLicenced(false);
        $result = $action == 'count' ? $criteria->count() : $criteria->ids();
        return $this->asJson($result);
    }

    /**
     *
     */
    public function actionResave()
    {
        Module::$module->controllerNamespace = 'craft\console\controllers';
        Module::$module->runAction('resave/users');
        return $this->asJson(1);
    }
}