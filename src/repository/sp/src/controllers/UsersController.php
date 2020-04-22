<?php
/**
 * Lantra Skills Plus for Craft CMS 3.x
 *
 * @link      https://coffeebean.design
 * @copyright Copyright (c) 2020 Coffee Bean Design
 */

namespace lantra\sp\controllers;

use Craft;
use craft\elements\User;
use craft\elements\MatrixBlock;
use lantra\sp\helpers\LantraHelper;
use lantra\sp\Plugin as Lantra;
use verbb\supertable\elements\SuperTableBlockElement;

class UsersController extends BaseController {

    /**
     * @return \yii\web\Response
     */
    public function actionHierarchy()
    {
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
     * @return \yii\web\Response
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\BadRequestHttpException
     */
    public function actionRefreshHierarchy()
    {
        $user = Craft::$app->getUser()->getIdentity();
        Lantra::$app->structure->clearHierarchyCache($user->id);
        return $this->_returnMessage('Hierarchy cache deleted.', true, '/management/hierarchy');
    }

    /**
     * @return \yii\web\Response
     * @throws \CException
     * @throws \yii\web\BadRequestHttpException
     */
    public function actionCompanyManagers()
    {
        $this->requirePostRequest();
        $companyIds = Craft::$app->request->getParam('companyIds', []);
        $return = [];
        if (is_countable($companyIds) && count($companyIds)) {
            $managers = Lantra::$app->users->getMultipleCompanyManagers($companyIds);
            if (count($managers)) {
                foreach ($managers as $manager) {
                    $return[$manager->id] = $manager->fullName;
                }
            }
        }
        return $this->asJson($return);
    }

    /**
     * Saves user from the management form
     *
     * @throws mixed
     */
    public function actionSaveUser()
    {
        $this->requirePostRequest();
        $this->requirePermission('editUsers');
        ## get the posted userId
        $request = Craft::$app->getRequest();
        $userId = $request->getParam('editUserId');
        $redirect = Craft::$app->getRequest()->getValidatedBodyParam('redirect');
        $fields = $request->getParam('fields');

        ## existing user
        if ($userId) {
            $user = User::find()
                ->id($userId)
                ->anyStatus()
                ->addSelect(['users.password', 'users.passwordResetRequired'])
                ->one();
            if (!$user) {
                $this->_returnError('Invalid user ID ' . $userId . '.');
            }
        }
        ## create new user
        else {
            $this->requirePermission('registerUsers');
            $user = new User();
        }

        ## set basic account fields
        $user->firstName = $request->getBodyParam('firstName', $user->firstName);
        $user->lastName = $request->getBodyParam('lastName', $user->lastName);
        if ($fields['userDummyEmail']) {
            $user->email = Lantra::$app->users->generateEmail($user->firstName, $user->lastName);
        }
        else {
            $user->email = Craft::$app->request->getParam('email');
        }

        ## set custom fields
        $user->setFieldValuesFromRequest('fields');

        ## username is email
        if (false != $username = Craft::$app->request->getBodyParam('username')) {
            $user->username = $username;
        }
        else {
           $user->username = $user->email;
        }

        $companyManager = false;
        ## assign user to groups (always in 'user' group from front end)
        $groupIds = [4];
        $userCompany = isset($fields['userCompany']) ? $fields['userCompany'] : null;
        if ($fields['userType'] == 'manager') {
            $groupIds[] = 2;
            $companyManager = true;
        }
        ## remove as manager from all companies
        elseif (false != $companies = Lantra::$app->users->getManagerCompanies($user)) {
            foreach ($companies as $company) {
                Lantra::$app->users->removeCompanyManager($company, $user);
            }
        }
        if (Craft::$app->request->getParam('teamManagers')) {
            $groupIds[] = 3;
        }
        ## save scheme manager
        if ($user->isInGroup(1)) {
            $groupIds[] = 1;
        }

        ## mimic cp form for onSaveUser event
        $_POST['groups'] = $groupIds;

        ## set new password (if present)
        $user->newPassword = (Craft::$app->request->getParam('newPassword') ?: null);
        $confirmPassword = (Craft::$app->request->getParam('confirmPassword') ?: null);

        ## did they upload a photo
        if ($tempFilePath = LantraHelper::tempFilePath('photo')) {
            Craft::$app->users->saveUserPhoto($tempFilePath, $user);
        }

        if ($user->newPassword && ($user->newPassword != $confirmPassword)) {
            $user->addErrors(['confirmPassword' => 'Passwords do not match']);
            return Craft::$app->urlManager->setRouteParams(array('account' => $user));
        }
        ## save user
        elseif (!Craft::$app->elements->saveElement($user)) {
            return Craft::$app->urlManager->setRouteParams(['account' => $user, 'saveUserError' => true]);
        }

        Craft::$app->users->assignUserToGroups($user->id, $groupIds);
        ## set user manager relations
        if ($companyManager) {
            if ($userCompany) {
                Lantra::$app->users->setManager($userCompany, $user, 'primary');
            }
            $secondaryManagerCompanyIds = Craft::$app->request->getParam('userSecondaryManagerCompanies', []);
            Lantra::$app->users->setManager($secondaryManagerCompanyIds, $user, 'secondary');
        }

        ## delete hierarchy cache
        Lantra::$app->structure->clearHierarchyCache();

        $this->_returnMessage('User has been saved.', true, $redirect);
    }

    /**
     * Deletes users from the front end
     *
     * @throws mixed
     */
    public function actionDeleteUser() {
        $this->requirePostRequest();
        ## get the posted userId
        $userId = Craft::$app->request->getParam('userId');
        if (false == $user = Craft::$app->users->getUserById($userId)) {
            $this->_returnError('Invalid user ID ' . $userId . '.');
        }
        if (!Craft::$app->elements->deleteElement($user)) {
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
        $userId = Craft::$app->request->getParam('userId');
        if (false == $user = Craft::$app->users->getUserById($userId)) {
            $this->_returnError('Invalid user ID ' . $userId . '.');
        }
        if (! Craft::$app->users->suspendUser($user)) {
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
        $userId = Craft::$app->request->getParam('userId');
        if (false == $user = Craft::$app->users->getUserById($userId)) {
            $this->_returnError('Invalid user ID ' . $userId . '.');
        }
        if (! Craft::$app->users->unsuspendUser($user)) {
            $this->_returnError('Error restoring user.');
        }
        $this->_returnMessage('User has been restored.');
    }

    /**
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\BadRequestHttpException
     */
    public function actionRefreshResults()
    {
        $users = [];
        $this->requireLogin();
        $userId = Craft::$app->request->getParam('userId');
        $companyId = Craft::$app->request->getParam('companyId');
        if ($companyId) {
            $criteria = Lantra::$app->users->getCompanyUsers($companyId);
            $users = $criteria ? $criteria->all() : [];
        }
        elseif ($userId) {
            $users = [Craft::$app->users->getUserById($userId)];
        }
        $total = count($users);
        if ($total) {
            Lantra::$app->results->refreshResultCache($users);
        }
        $this->_returnMessage($total . ' users refreshed', true, 'management/' . ($companyId ? 'companies' : 'users'));
    }

    /**
     * Saves user taskbook package
     *
     * @throws mixed
     */
    public function actionSavePackage()
    {
        $this->requireLogin();
        $userId = Craft::$app->request->getParam('userId');
        $package = Craft::$app->request->getParam('package');
        $user = Craft::$app->users->getUserById($userId);

        if (! $user || !$package) {
            return $this->_returnError('Invalid params [userId = ' . $userId .'].');
        }

        $modules = [];
        foreach ($package as $id => $m) {
            if (isset($m['selected']) && $m['selected']) {
                $modules[$id] = $m['level'];
            }
        }

        if (!count($modules)) {
            return $this->_returnError('You must select some modules for your package.');
        }

        $spField = Craft::$app->fields->getFieldByHandle('userPackages');
        if (!$spField) {
            return $this->_returnError('Could not locate field type.');
        }
        $spType = $spField->getBlockTypes()[0];

        $spBlock = new SuperTableBlockElement();
        $spBlock->ownerId = $user->id;
        $spBlock->fieldId = $spField->id;
        $spBlock->typeId = $spType->id;
        $spBlock->enabled = true;
        $spBlock->setFieldValue('packageDateCreated', time());
        Craft::$app->elements->saveElement($spBlock);

        $mBlock = null;
        foreach ($spField->getBlockTypeFields() as $field) {
            if ($field->handle == 'packageModules') {
                $mBlock = $field;
            }
        }
        if (!$mBlock) {
            return $this->_returnError('Could not locate field type.');
        }
        foreach ($modules as $id => $level) {
            $block = new MatrixBlock();
            $block->enabled = true;
            $block->ownerId = $spBlock->id;
            $block->fieldId = $mBlock->id;
            $block->typeId = $mBlock->getBlockTypes()[0]->id;
            $block->setFieldValue('packageModule', [$id]);
            $block->setFieldValue('packageModuleLevel', $level);
            Craft::$app->elements->saveElement($block);
        }

        $this->_returnMessage('Taskbook package saved.', 'true', 'profile/taskbooks');
    }
}
