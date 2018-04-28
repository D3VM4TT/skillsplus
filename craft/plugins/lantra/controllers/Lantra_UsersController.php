<?php

namespace Craft;

class Lantra_UsersController extends Lantra_BaseController {

    public $allowAnonymous = array('actionSaveUser', 'actionDeleteUser');

    /**
     * Saves user from the management form
     *
     * @throws Exception
     */
    public function actionSaveUser()
    {
        $this->requirePostRequest();
        craft()->userSession->requireLogin();
        craft()->userSession->requirePermission('editUsers');

        $userId = craft()->request->getPost('editUserId');

        $redirect = '/management/users';

        // existing user
        if ($userId) {

            $user = craft()->users->getUserById($userId);

            if (!$user) {
                throw new Exception(Craft::t('No user exists with the ID “{id}”.', array('id' => $userId)));
            }
        } // new user
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
            $this->returnSuccess($user->id, $redirect);
        } else {
            $this->returnError($user->getAllErrors(), array('account' => $user));
        }
    }

    /**
     * Deletes users from the front end
     *
     * @throws Exception
     */
    public function actionDeleteUser()
    {
        $this->requirePostRequest();
        craft()->userSession->requireLogin();

        $userId = craft()->request->getPost('userId');
        if (FALSE == $user = craft()->users->getUserById($userId)) {
            $this->_returnError('Invalid user ID.');
        }

        $user->suspended = true;

        if ( ! craft()->users->saveUser($user)) {
            $this->_returnError('Error updating user record.');
        }

        $this->_returnMessage('User has been removed.');
    }

    /**
     * Return back to form and show error message
     *
     */
    protected function returnError($errors = array(), $variables = array()) {

        if (craft()->request->isAjaxRequest())
        {
            $this->returnJson(array(
                'errors' => $errors,
            ));
        }

        craft()->urlManager->setRouteVariables($variables);
    }

    /**
     * Redirect with success message
     *
     */
    protected function returnSuccess($id, $redirect, $message = '') {

        if (craft()->request->isAjaxRequest())
        {
            $return['success']   = true;
            $return['id']        = $id;

            $this->returnJson($return);
        }

        craft()->userSession->setNotice($message);
        craft()->request->redirect($redirect);
    }
}
