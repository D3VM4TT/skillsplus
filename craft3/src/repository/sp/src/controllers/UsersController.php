<?php
/**
 * Lantra Skills Plus for Craft CMS 3.x
 *
 * @link      https://coffeebean.design
 * @copyright Copyright (c) 2020 Coffee Bean Design
 */

namespace lantra\sp\controllers;

use Craft;

use lantra\sp\Plugin as Lantra;

class UsersController extends BaseController {

    protected $allowAnonymous = true;

    /**
     * Get company users for hierarchy
     *
     * @throws mixed
     */
    public function actionHierarchy() {
        $this->requireLogin();
        $companyId = Craft::$app->request->getParam('companyId');
        $type = Craft::$app->request->getParam('type');
        $user = Craft::$app->getUser()->getIdentity();
        ## look in cache
        $name = 'lantraHierarchy' . $user->id;
        if (false == $cache = Craft::$app->cache->get($name)){
            $cache = [];
            Craft::$app->cache->set($name, $cache);
        }
        $key = (!$companyId ? 'root' : $companyId . $type);
        if (isset($cache[$key])) {
            $node = $cache[$key];
        }
        else {
            $node = Lantra::$app->structure->getHierarchy($companyId, $type);
            ## set cache
            $cache[$key] = $node;
            Craft::$app->cache->set($name, $cache, 86400);
        }
        return $this->asJson($node);
    }

    /**
     * @throws \yii\web\BadRequestHttpException
     */
    public function actionRefreshHierarchy() {
        $this->requireLogin();
        $user = Craft::$app->getUser()->getIdentity();
        Lantra::$app->structure->clearHierarchyCache($user->id);
        return $this->_returnMessage('Hierarchy cache deleted.', true, '/management/hierarchy');
    }

    /**
     * return key value managers for report option js
     */
    public function actionCompanyManagers() {
        $this->requireLogin();
        $companyIds = Craft::$app->request->getParam('companyIds');
        $return = [];
        if (count($companyIds)) {
            $managers = Lantra::$app->users->getMultipleCompanyManagers($companyIds);
            if (count($managers)) {
                foreach ($managers as $manager) {
                    $return[$manager->id] = $manager->fullName;
                }
                sort($managers);
            }
        }
        return $this->asJson($return);
    }

    /**
     * Saves user from the management form
     *
     * @throws mixed
     */
    public function actionSaveUser() {
        $this->requirePostRequest();
        $this->requireLogin();
        $this->requirePermission('editUsers');
        // get the posted userId
        $userId = Craft::$app->request->getPost('editUserId');
        $redirect = Craft::$app->request->getPost('redirect') ? Craft::$app->request->getPost('redirect') : '/management/users';
        $fields = Craft::$app->request->getPost('fields');

        // existing user
        if ($userId) {
            if (false == $user = craft()->users->getUserById($userId)) {
                $this->_returnError('Invalid user ID ' . $userId . '.');
            }
        }
        // create new user
        else {
            $this->requirePermission('registerUsers');
            $user = new UserModel();
        }
        // set basic account fields
        $user->firstName = Craft::$app->request->getPost('firstName');
        $user->lastName = Craft::$app->request->getPost('lastName');
        if ($fields['userDummyEmail']) {
            $user->email = Lantra::$app->users->generateEmail($user->firstName, $user->lastName);
        }
        else {
            $user->email = Craft::$app->request->getPost('email');
        }
        // set custom fields
        $user->setContentFromPost('fields');
        // username is email
        if (false != $username = Craft::$app->request->getPost('username')) {
            $user->username = $username;
        }
        else {
           $user->username = $user->email;
        }
        $companyManager = false;
        // assign user to groups (always in 'user' group from front end)
        $groupIds = array(4);
        $userCompanyId = isset($fields['userCompany']) ? $fields['userCompany'] : null;
        if (Craft::$app->request->getPost('companyManagers')) {
            $groupIds[] = 2;
            $companyManager = true;
        }
        // remove as manager from all companies
        elseif (false != $companies = Lantra::$app->users->getManagerCompanies($user)) {
            foreach ($companies as $company) {
                Lantra::$app->users->removeCompanyManager($company, $user);
            }
        }
        if (Craft::$app->request->getPost('teamManagers')) {
            $groupIds[] = 3;
        }
        // save scheme manager
        if ($user->isInGroup(1)) {
            $groupIds[] = 1;
        }
        // mimic cp form for onSaveUser event
        $_POST['groups'] = $groupIds;
        // set new password (if present)
        $user->newPassword = (Craft::$app->request->getPost('newPassword') ?: null);
        $confirmPassword = (Craft::$app->request->getPost('confirmPassword') ?: null);
        if ($user->newPassword && ($user->newPassword != $confirmPassword))
        {
            $user->addErrors(array('confirmPassword' => Craft::t('Passwords do not match')));
            craft()->urlManager->setRouteVariables(array('account' => $user));
        }
        // save user
        elseif (craft()->users->saveUser($user)) {
            craft()->userGroups->assignUserToGroups($user->id, $groupIds);
            // set user manager relations
            if ($companyManager) {
                if ($userCompanyId) {
                    Lantra::$app->users->setManager([$userCompanyId], $user, 'primary');
                }
                $secondaryManagerCompanyIds = Craft::$app->request->getPost('userSecondaryManagerCompanies', []);
                Lantra::$app->users->setManager($secondaryManagerCompanyIds, $user, 'secondary');
            }
            $this->_returnMessage('User has been saved.', true, $redirect);
        } else {
            craft()->urlManager->setRouteVariables(array('account' => $user, 'saveUserError' => true));
        }
    }

    /**
     * Deletes users from the front end
     *
     * @throws mixed
     */
    public function actionDeleteUser() {
        $this->requirePostRequest();
        $this->requireLogin();
        // get the posted userId
        $userId = Craft::$app->request->getPost('userId');
        if (false == $user = craft()->users->getUserById($userId)) {
            $this->_returnError('Invalid user ID ' . $userId . '.');
        }
        if ( ! craft()->users->deleteUser($user)) {
            $this->_returnError('Error deleting user.');
        }
        $this->_returnMessage('User has been deleted.');
    }

    /**
     * Suspends users from the front end
     *
     * @throws mixed
     */
    public function actionSuspendUser() {
        $this->requirePostRequest();
        $this->requireLogin();
        // get the posted userId
        $userId = Craft::$app->request->getPost('userId');
        if (false == $user = craft()->users->getUserById($userId)) {
            $this->_returnError('Invalid user ID ' . $userId . '.');
        }
        $user->suspended = true;
        if ( ! craft()->users->saveUser($user)) {
            $this->_returnError('Error suspending user.');
        }
        $this->_returnMessage('User has been suspended.');
    }

    /**
     * Restores user
     *
     * @throws mixed
     */
    public function actionRestoreUser()
    {
        $this->requirePostRequest();
        $this->requireLogin();
        // get the posted userId
        $userId = Craft::$app->request->getPost('userId');
        if (false == $user = craft()->users->getUserById($userId)) {
            $this->_returnError('Invalid user ID ' . $userId . '.');
        }
        $user->suspended = false;
        if ( ! craft()->users->saveUser($user)) {
            $this->_returnError('Error restoring user.');
        }
        $this->_returnMessage('User has been restored.');
    }
}
