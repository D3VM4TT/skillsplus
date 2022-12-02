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
use craft\elements\Entry;
use craft\helpers\Queue;
use craft\web\UploadedFile;
use craft\helpers\Image;
use lantra\sp\helpers\LantraHelper;
use lantra\sp\Plugin as Lantra;
use lantra\sp\jobs\SetSubordinatesLicenceCompanyJob;
use verbb\supertable\elements\SuperTableBlockElement;

class UsersController extends BaseController
{

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
        if (false == $cache = Craft::$app->cache->get($name)) {
            $cache = [];
            Craft::$app->cache->set($name, $cache);
        }
        $key = (!$companyId ? 'root' : $companyId . $type);
        if (isset($cache[$key])) {
            $node = $cache[$key];
        } else {
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
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\BadRequestHttpException
     */
    public function actionPrivacyConfirm()
    {
        $user = Craft::$app->getUser()->getIdentity();
        $now = new \DateTime();
        $user->setFieldValue('userPrivacy', true);
        $user->setFieldValue('userPrivacyDate', $now);
        Craft::$app->elements->saveElement($user);
        return $this->_returnMessage('', true);
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
     * Saves user from the edit profile form
     *
     * @throws mixed
     */
    public function actionSaveProfile()
    {
        $this->requirePostRequest();
        ## get the posted userId
        $request = Craft::$app->getRequest();
        $userId = $request->getRequiredParam('userId');
        $redirect = Craft::$app->getRequest()->getValidatedBodyParam('redirect');

        if (null == $user = User::find()->id($userId)->one()) {
            $this->_returnError('Invalid user ID ' . $userId . '.');
        }

        ## set basic account fields
        $user->firstName = $request->getBodyParam('firstName', $user->firstName);
        $user->lastName = $request->getBodyParam('lastName', $user->lastName);
        $user->email = Craft::$app->request->getParam('email', $user->email);

        ## set custom fields
        $user->setFieldValuesFromRequest('fields');

        ## did they upload a photo
        $this->_processUserPhoto($user);

        if (!Craft::$app->elements->saveElement($user)) {
            Craft::$app->session->setError('Could not save profile.');
            return Craft::$app->urlManager->setRouteParams(['user' => $user, 'saveUserError' => true]);
        }

        return $this->_returnMessage('Profile updated.', true, $redirect);
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

        $isNew = !$userId;

        if ($isNew) {
            $this->requirePermission('registerUsers');
            $user = new User();
        } else {
            $user = User::find()
                ->id($userId)
                ->anyStatus()
                ->addSelect(['users.password', 'users.passwordResetRequired'])
                ->one();
            if (!$user) {
                $this->_returnError('Invalid user ID ' . $userId . '.');
            }
        }

        ## set custom fields
        $user->setFieldValuesFromRequest('fields');

        ## set email
        $postedEmail = Craft::$app->request->getParam('email');
        $user->email = $postedEmail;

        ## handle linked user
        if ($isNew && Craft::$app->request->getParam('linkedUser')) {
            if (!$postedEmail || null == $linkedUser = Craft::$app->users->getUserByUsernameOrEmail($postedEmail)) {
                $user->addErrors(['linkedUser' => 'Linked user not found.']);
                return Craft::$app->urlManager->setRouteParams(['account' => $user]);
            }
            $user->email = Lantra::$app->users->generateEmail($user->firstName, $user->lastName);
            $user->firstName = $linkedUser->firstName;
            $user->lastName = $linkedUser->lastName;
            $user->setFieldValues([
                'userAddress' => $user->userAddress,
                'userTelephone' => $user->userTelephone,
                'userDateOfBirth' => $user->userDateOfBirth,
                'userLinkedUser' => [$linkedUser->id],
                'userNotLicenced' => true
            ]);
        } else {
            $user->firstName = $request->getBodyParam('firstName', $user->firstName);
            $user->lastName = $request->getBodyParam('lastName', $user->lastName);
            if (isset($fields['userDummyEmail']) && $fields['userDummyEmail'] == 1 && !$postedEmail) {
                $user->email = Lantra::$app->users->generateEmail($user->firstName, $user->lastName);
            }
        }

        ## username is email
        if (false != $username = Craft::$app->request->getBodyParam('username')) {
            $user->username = $username;
        } else {
            $user->username = $user->email;
        }

        $usersId = LantraHelper::userGroupId('users');
        $companyManagersId = LantraHelper::userGroupId('companyManagers');
        $teamManagersId = LantraHelper::userGroupId('teamManagers');
        $schemeManagersId = LantraHelper::userGroupId('schemeManagers');

        $companyManager = false;
        ## assign user to groups (always in 'user' group from front end)
        $groupIds = [$usersId];
        $userCompany = isset($fields['userCompany']) ? $fields['userCompany'] : null;

        if ($fields['userType'] == 'manager') {
            $groupIds[] = $companyManagersId;
            $companyManager = true;
        } ## remove as manager from all companies
        elseif (!$isNew && false != $companies = Lantra::$app->users->getManagerCompanies($user)) {
            foreach ($companies as $company) {
                Lantra::$app->users->removeCompanyManager($company, $user);
            }
        }
        if (Craft::$app->request->getParam('teamManagers')) {
            $groupIds[] = $teamManagersId;
        }
        ## save scheme manager
        if ($user->isInGroup($schemeManagersId)) {
            $groupIds[] = $schemeManagersId;
        }

        ## mimic cp form for onSaveUser event
        $_POST['groups'] = $groupIds;

        ## set new password (if present)
        $user->newPassword = (Craft::$app->request->getParam('newPassword') ?: null);
        $confirmPassword = (Craft::$app->request->getParam('confirmPassword') ?: null);

        ## did they upload a photo
        $this->_processUserPhoto($user);

        if ($user->newPassword && ($user->newPassword != $confirmPassword)) {
            $user->addErrors(['confirmPassword' => 'Passwords do not match']);
            return Craft::$app->urlManager->setRouteParams(array('account' => $user));
        } ## save user
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

        ## override redirect
        if ($request->getParam('taskbook')) {
            $redirect = '/management/taskbooks/new/?userId=' . $user->id;
        }

        $this->_returnMessage('User has been saved.', true, $redirect);
    }

    /**
     * Deletes users from the front end
     *
     * @throws mixed
     */
    public function actionDeleteUser()
    {
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
    public function actionSuspendUser()
    {
        $this->requirePostRequest();
        $this->requireLogin();
        // get the posted userId
        $userId = Craft::$app->request->getParam('userId');
        if (false == $user = Craft::$app->users->getUserById($userId)) {
            $this->_returnError('Invalid user ID ' . $userId . '.');
        }
        if (!Craft::$app->users->suspendUser($user)) {
            $this->_returnError('Error suspending user.');
        }
        $this->_returnMessage('User has been suspended.');
    }

    /**
     * Suspends users from the front end
     *
     * @throws mixed
     */
    public function actionSuspendCompanyUsers()
    {
        $this->requirePostRequest();
        $this->requireLogin();
        // get the posted companyId
        $companyId = Craft::$app->request->getParam('companyId');
        $includeHierarchy = Craft::$app->request->getParam('includeHierarchy', false);
        if (false == $company = Entry::findOne($companyId)) {
            return $this->_returnError('Invalid company ID ' . $companyId . '.');
        }
        $redirect = '/management/companies/edit/' . $companyId;
        $count = 0;
        $errorIds = [];
        $users = Lantra::$app->structure->getCompanyUsers($companyId, $includeHierarchy);
        foreach ($users as $user) {
            if (!Craft::$app->users->suspendUser($user)) {
                $errorIds[] = $user->id;
            } else {
                $count++;
            }
        }
        return $this->_returnMessage($count . ' users have been suspended.', true, $redirect);
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
        if (!Craft::$app->users->unsuspendUser($user)) {
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
        } elseif ($userId) {
            $users = [Craft::$app->users->getUserById($userId)];
        }
        $total = count($users);
        if ($total) {
            Lantra::$app->results->refreshResultCache($users);
        }
        $this->_returnMessage($total . ' users refreshed', true, 'management/' . ($companyId ? 'companies' : 'users'));
    }

    /**
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\BadRequestHttpException
     */
    public function actionSubordinateLicenceCompany()
    {
        $this->requireLogin();
        $companyId = Craft::$app->request->getParam('companyId');
        $setSubordinatesLicenceCompanyJob = new SetSubordinatesLicenceCompanyJob([
            'companyId' => $companyId,
            'includeHierarchy' => Craft::$app->request->getParam('includeHierarchy', false)
        ]);
        Queue::push($setSubordinatesLicenceCompanyJob);
        return $this->_returnMessage('Company licence update added to queue.');
    }

    /**
     * @param User $user
     * @throws \craft\errors\ImageException
     * @throws \craft\errors\VolumeException
     * @throws \yii\base\Exception
     */
    private function _processUserPhoto(User $user)
    {
        if (null == $photo = UploadedFile::getInstanceByName('photo')) {
            return;
        }
        if (!Image::canManipulateAsImage($photo->getExtension())) {
            $user->addError('photo', 'The profile photo is not an image.');
            return;
        }
        if (null == $tempFilePath = LantraHelper::tempFilePath('photo')) {
            $user->addError('photo', 'Could not move profile photo.');
            return;
        }
        if (!Craft::$app->users->saveUserPhoto($tempFilePath, $user, $user->id . '.' . $photo->extension)) {
            $user->addError('photo', 'Could not save profile photo.');
        }
    }
}
