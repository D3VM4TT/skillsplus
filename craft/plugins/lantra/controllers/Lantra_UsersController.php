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
        $node = craft()->lantra_structure->getHierarchy($companyId, $type, $user->id);
        return craft()->controller->returnJson($node);
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
        if (craft()->request->getPost('companyManagers')) {
            $groupIds[] = 2;
            $companyManager = true;
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
            if ($companyManager )
            {
                $primaryManagerCompanyIds = craft()->request->getPost('userPrimaryManagerCompanies', []);
                $secondaryManagerCompanyIds = craft()->request->getPost('userSecondaryManagerCompanies', []);
                craft()->lantra_users->setManager($primaryManagerCompanyIds, $user, 'primary');
                craft()->lantra_users->setManager($secondaryManagerCompanyIds, $user, 'secondary');
            }
            $this->_returnMessage('User has been saved.', true, $redirect);
        } else {
            craft()->urlManager->setRouteVariables(array('account' => $user));
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
