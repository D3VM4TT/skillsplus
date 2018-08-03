<?php

namespace Craft;

class Lantra_UsersController extends Lantra_BaseController {

    public $allowAnonymous = array('actionSaveUser', 'actionDeleteUser');

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
        $user->email = craft()->request->getPost('email');
        // set new password (if present)
        $user->newPassword = (craft()->request->getPost('newPassword') ?: null);
        // set custom fields
        $user->setContentFromPost('fields');
        // username is email
        $user->username = $user->email;
        // assign user to groups (always in 'user' group from front end)
        $groupIds = array(4);
        if (craft()->request->getPost('companyManagers')) {
            $groupIds[] = 2;
        }
        if (craft()->request->getPost('teamManagers')) {
            $groupIds[] = 3;
        }
        // mimic cp form for onSaveUser event
        $_POST['groups'] = $groupIds;
        // save user
        if (craft()->users->saveUser($user)) {
            craft()->userGroups->assignUserToGroups($user->id, $groupIds);
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
}
