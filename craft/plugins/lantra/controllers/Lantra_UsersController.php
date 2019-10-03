<?php

namespace Craft;

class Lantra_UsersController extends Lantra_BaseController {

    public $allowAnonymous = array('actionHierarchy', 'actionSaveUser', 'actionDeleteUser', 'actionRestoreUser');

    /**
     * Get company users for hierarchy
     *
     * @throws mixed
     */
    public function actionHierarchy() {
        craft()->userSession->requireLogin();
        // get the posted nodeId
        $companyId = craft()->request->getParam('companyId');
        $type = craft()->request->getParam('type');
        $user = craft()->userSession->getUser();
        // look in cache
        $cache = 'lantraHierarchy' . $user->id . (!$companyId ? 'root' : $companyId . $type);
        if (false == $node = craft()->cache->get($cache)) {
            $node = craft()->lantra_structure->getHierarchy($companyId, $type);
            // set cache
            craft()->cache->set($cache, $node, 86400);
        }
        return craft()->controller->returnJson($node);
    }

    /**
     * return key value managers for report option js
     */
    public function actionCompanyManagers() {
        craft()->userSession->requireLogin();
        $companyIds = craft()->request->getParam('companyIds');
        $return = [];
        if (count($companyIds)) {
            $managers = craft()->lantra_users->getMultipleCompanyManagers($companyIds);
            if (count($managers)) {
                foreach ($managers as $manager) {
                    $return[$manager->id] = $manager->fullName;
                }
                sort($managers);
            }
        }
        return craft()->controller->returnJson($return);
    }

    /**
     * Saves user from the management form
     *
     * @throws mixed
     */
    public function actionSaveUser() {
        $this->requirePostRequest();
        craft()->userSession->requireLogin();
        craft()->userSession->requirePermission('editUsers');
        // get the posted userId
        $userId = craft()->request->getPost('editUserId');
        $redirect = craft()->request->getPost('redirect') ? craft()->request->getPost('redirect') : '/management/users';
        $fields = craft()->request->getPost('fields');

        // existing user
        if ($userId) {
            if (false == $user = craft()->users->getUserById($userId)) {
                $this->_returnError('Invalid user ID ' . $userId . '.');
            }
        }
        // create new user
        else {
            craft()->userSession->requirePermission('registerUsers');
            $user = new UserModel();
        }
        // set basic account fields
        $user->firstName = craft()->request->getPost('firstName');
        $user->lastName = craft()->request->getPost('lastName');
        if ($fields['userDummyEmail']) {
            $user->email = craft()->lantra_users->generateEmail($user->firstName, $user->lastName);
        }
        else {
            $user->email = craft()->request->getPost('email');
        }
        // set custom fields
        $user->setContentFromPost('fields');
        // username is email
        if (false != $username = craft()->request->getPost('username')) {
            $user->username = $username;
        }
        else {
           $user->username = $user->email;
        }
        $companyManager = false;
        // assign user to groups (always in 'user' group from front end)
        $groupIds = array(4);
        $userCompanyId = isset($fields['userCompany']) ? $fields['userCompany'] : null;
        if (craft()->request->getPost('companyManagers')) {
            $groupIds[] = 2;
            $companyManager = true;
        }
        // remove as manager from all companies
        elseif (false != $companies = craft()->lantra_users->getManagerCompanies($user)) {
            foreach ($companies as $company) {
                craft()->lantra_users->removeCompanyManager($company, $user);
            }
        }
        if (craft()->request->getPost('teamManagers')) {
            $groupIds[] = 3;
        }
        // mimic cp form for onSaveUser event
        $_POST['groups'] = $groupIds;
        // set new password (if present)
        $user->newPassword = (craft()->request->getPost('newPassword') ?: null);
        $confirmPassword = (craft()->request->getPost('confirmPassword') ?: null);
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
                    craft()->lantra_users->setManager([$userCompanyId], $user, 'primary');
                }
                $secondaryManagerCompanyIds = craft()->request->getPost('userSecondaryManagerCompanies', []);
                craft()->lantra_users->setManager($secondaryManagerCompanyIds, $user, 'secondary');
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
        craft()->userSession->requireLogin();
        // get the posted userId
        $userId = craft()->request->getPost('userId');
        if (false == $user = craft()->users->getUserById($userId)) {
            $this->_returnError('Invalid user ID ' . $userId . '.');
        }
        $user->suspended = true;
        if ( ! craft()->users->saveUser($user)) {
            $this->_returnError('Error removing user.');
        }
        $this->_returnMessage('User has been removed.');
    }

    /**
     * Restores user
     *
     * @throws mixed
     */
    public function actionRestoreUser()
    {
        $this->requirePostRequest();
        craft()->userSession->requireLogin();
        // get the posted userId
        $userId = craft()->request->getPost('userId');
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
