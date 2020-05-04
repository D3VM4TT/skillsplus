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
     * @param $packageId
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\BadRequestHttpException
     */
    public function actionPay($packageId)
    {
        $spBlock = Craft::$app->elements->getElementById($packageId);
        $spBlock->setFieldValue('packagePaid', 1);
        Craft::$app->elements->saveElement($spBlock);
        $this->_returnMessage('Package Paid', true, 'profile/taskbooks');
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

        if (! $user || !$package || !isset($package['core'])) {
            return $this->_returnError('Invalid params [userId = ' . $userId .'].');
        }

        $coreModuleId = $package['core']['module'];
        $coreLevel = $package['core']['level'];
        $allOptionalModules = isset($package['optional']) && isset($package['optional'][$coreModuleId]) ? $package['optional'][$coreModuleId] : [];
        $optionalModules = [];
        foreach ($allOptionalModules as $id => $m) {
            if (isset($m['selected']) && $m['selected']) {
                $level = isset($m['level']) ? $m['level'] : $coreLevel;
                $optionalModules[$id] = $level;
            }
        }

        $totalOptional = count($optionalModules);

        $coreModule = Craft::$app->entries->getEntryById($coreModuleId);
        if ($totalOptional < $coreModule->moduleMinimumOptional) {
            $errors = ['You must select a minimum of ' . $coreModule->moduleMinimumOptional . ' optional modules.'];
            return Craft::$app->urlManager->setRouteParams(['errors' => $errors]);
        }

        $cost = $coreModule->moduleMaxCost;
        foreach ($coreModule->moduleCosts as $row) {
            if ($totalOptional == $row['optionalModules']) {
                $cost = (int) $row['cost'];
            }
        }

        $spField = Craft::$app->fields->getFieldByHandle('userPackages');
        if (!$spField) {
            $this->_returnError('Could not locate field type.');
            return;
        }

        ## create the package block
        $spType = $spField->getBlockTypes()[0];
        $spBlock = new SuperTableBlockElement();
        $spBlock->ownerId = $user->id;
        $spBlock->fieldId = $spField->id;
        $spBlock->typeId = $spType->id;
        $spBlock->enabled = true;
        $spBlock->setFieldValue('packageDateCreated', time());
        $spBlock->setFieldValue('packageCoreModule', [$coreModuleId]);
        $spBlock->setFieldValue('packageCoreLevel', $coreLevel);
        $spBlock->setFieldValue('packageCost', $cost);
        Craft::$app->elements->saveElement($spBlock);

        ## add the optional modules
        if (count($optionalModules)) {
            $mBlock = null;
            foreach ($spField->getBlockTypeFields() as $field) {
                if ($field->handle == 'packageOptionalModules') {
                    $mBlock = $field;
                }
            }
            if (!$mBlock) {
                return $this->_returnError('Could not locate field type.');
            }
            foreach ($optionalModules as $id => $level) {
                $block = new MatrixBlock();
                $block->enabled = true;
                $block->ownerId = $spBlock->id;
                $block->fieldId = $mBlock->id;
                $block->typeId = $mBlock->getBlockTypes()[0]->id;
                $block->setFieldValue('optionalModule', [$id]);
                $block->setFieldValue('optionalLevel', $level);
                Craft::$app->elements->saveElement($block);
            }
        }

        $this->_returnMessage('Please continue to PayPal to make payment.', 'true', 'profile/taskbooks/view/' . $spBlock->id);
    }

    /**
     * Update taskbook package
     *
     * @throws mixed
     */
    public function actionUpdatePackage()
    {
        $this->requireLogin();
        $packageId = Craft::$app->request->getRequiredParam('packageId');
        if (null == $package = SuperTableBlockElement::findOne($packageId)) {
            return $this->_returnError('Invalid params [packageId = ' . $packageId . '].');
        }

        $oldAssessorId = $package->packageAssessor->count() ? $package->packageAssessor->one()->id : null;
        $oldReviewerId = $package->packageReviewer->count() ? $package->packageReviewer->one()->id : null;
        $oldStatus = $package->packageStatus;

        $changed = [
          'assessor' => false,
          'reviewer' => false,
          'status' => false
        ];

        $assessorId = Craft::$app->request->getParam('assessorId');
        $reviewerId = Craft::$app->request->getParam('reviewerId');
        $status = Craft::$app->request->getParam('packageStatus');
        $comment = Craft::$app->request->getParam('packageComment');

        if ($assessorId != $oldAssessorId) {
            $package->setFieldValue('packageAssessor', [$assessorId]);
            $changed['assessor'] = true;
        }
        if ($reviewerId != $oldReviewerId) {
            $package->setFieldValue('packageReviewer', [$reviewerId]);
            $changed['reviewer'] = true;
        }
        if ($status != $oldStatus) {
            $package->setFieldValue('packageStatus', $status);
            $changed['status'] = true;
        }

        if (!Craft::$app->elements->saveElement($package)) {
            return Craft::$app->urlManager->setRouteParams(['package' => $package]);
        }

        if ($comment) {
            Lantra::$app->packages->addComment($package, $comment);
        }

        Lantra::$app->packages->onSavePackage($package, $changed);
        $redirect = '/cpd/' . $package->owner->id;
        $this->_returnMessage('Package has been updated', true, $redirect);
    }

    /**
     * Saves user taskbook package
     *
     * @throws mixed
     */
    public function actionPackageAssessor()
    {
        $this->requireLogin();
        $userId = Craft::$app->request->getParam('userId');
        $packageId = Craft::$app->request->getParam('packageId');
        if (null == $package = SuperTableBlockElement::findOne($packageId)) {
            return $this->_returnError('Invalid params [packageId = ' . $packageId . '].');
        }
        $assessor = $userId ? Craft::$app->users->getUserById($userId) :null;
        $package->setFieldValue('packageAssessor', [$userId]);
        if (!Craft::$app->elements->saveElement($package)) {
            return $this->_returnError($package->getFirstErrors()[0]);
        }
        if ($assessor) {
            $message = $assessor->fullname . ' assigned as assessor';
        }
        else {
            $message = 'Assessor unassigned.';
        }
        $this->_returnMessage($message, 'true');
    }
}
