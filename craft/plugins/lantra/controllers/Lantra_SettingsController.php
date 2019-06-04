<?php

namespace Craft;

class Lantra_SettingsController extends BaseController
{
    /**
     * @throws HttpException
     */
    public function actionIndex()
    {
        $settingsModel = new Lantra_SettingsModel;

        $settings = craft()->db->createCommand()
            ->select('settings')
            ->from('plugins')
            ->where('class=:class', array(':class' => 'Lantra'))
            ->queryScalar();

        $settings = JsonHelper::decode($settings);
        $settingsModel->setAttributes($settings);
        $variables['settings'] = $settingsModel;

        ## config for logo asset
        $themeFolder = craft()->assets->getRootFolderBySourceId(4)->id;
        $variables['themeFolder'] = ['folder:'.$themeFolder.':single'];

        ## config for navigation entries
        $variables['pagesSection'] = ['section:14'];
        $variables['companiesSection'] = ['section:3'];
        $variables['jobRoleCategoryGroup'] = ['group:1'];

        $this->renderTemplate('lantra/settings', $variables);
    }

    private function getUsers() {
        $criteria = craft()->elements->getCriteria(ElementType::User);
        $criteria->groupId = [2,3,4];
        $criteria->admin = false;
        $criteria->limit = null;
        return $criteria;
    }

    private function getManagers() {
        $criteria = craft()->elements->getCriteria(ElementType::User);
        $criteria->groupId = [2,3];
        $criteria->admin = false;
        $criteria->limit = null;
        return $criteria;
    }

    /**
     * @throws HttpException
     */
    public function actionTools()
    {
        $tool = craft()->request->getParam('tool');
        if ($tool == 'saveCompanies') {
            $topCompanies = craft()->lantra_structure->getCompanyChildren(null, false, null);
            if ($topCompanies){
                foreach($topCompanies as $company) {
                    craft()->entries->saveEntry($company);
                }
            }
            craft()->userSession->setNotice(Craft::t('All companies saved.'));
            $this->redirectToPostedUrl();
        }
        if ($tool == 'setUsernames') {
            $users = $this->getUsers();
            $message = '';
            foreach($users as $user) {
                $username = strtolower($user->firstName);
                if ($user->lastName) {
                    $username.= '.' . strtolower($user->lastName);
                }
                $user->username = $username;
                if ( ! craft()->users->saveUser($user)) {
                    $message .= ' ' . $user->fullName . ' not updated.';
                };
            }
            craft()->userSession->setNotice(Craft::t('Usernames updated.' . $message));
            $this->redirectToPostedUrl();
        }
        if ($tool == 'setPasswords') {
            $users = $this->getUsers();
            $message = '';
            foreach($users as $user) {
                $user->newPassword = $user->userDateOfBirth ? $user->userDateOfBirth->format('dmy') : 010101;
                if ( ! craft()->users->saveUser($user)) {
                    $message .= ' ' . $user->fullName . ' not updated.';
                };
            }
            craft()->userSession->setNotice(Craft::t('Passwords updated.' . $message));
            $this->redirectToPostedUrl();
        }
        if ($tool == 'setManagerReadOnly') {
            $managers = $this->getManagers();
            $message = '';
            foreach($managers as $user) {
                $user->setContentFromPost([
                    'managerReadOnly' => 1
                ]);
                if ( ! craft()->users->saveUser($user)) {
                    $message .= ' ' . $user->fullName . ' not updated.';
                };
            }
            craft()->userSession->setNotice(Craft::t('Managers updated.' . $message));
            $this->redirectToPostedUrl();
        }
        $this->renderTemplate('lantra/settings/tools');
    }

    /**
     * @throws HttpException
     */
    public function actionSaveSettings()
    {
        $this->requirePostRequest();
        $settings = craft()->request->getPost('settings');

        if (craft()->lantra_settings->saveSettings($settings)) {
            craft()->userSession->setNotice(Craft::t('Settings saved.'));
            $this->redirectToPostedUrl();
        } else {
            craft()->userSession->setError(Craft::t('Settings not saved.'));
            craft()->urlManager->setRouteVariables(array('settings' => $settings));
        }
    }
}