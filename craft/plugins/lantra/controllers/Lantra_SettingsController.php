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
        $variables['version'] = craft()->plugins->getPlugin('lantra')->getVersion();

        ## config for logo asset
        $themeFolder = craft()->assets->getRootFolderBySourceId(4)->id;
        $variables['themeFolder'] = ['folder:'.$themeFolder.':single'];

        ## config for navigation entries
        $variables['pagesSection'] = ['section:14'];
        $variables['companiesSection'] = ['section:3'];
        $variables['jobRoleCategoryGroup'] = ['group:1'];

        $this->renderTemplate('lantra/settings', $variables);
    }

    private function getUsers($clean = 'both', $limit = null) {
        $criteria = craft()->elements->getCriteria(ElementType::User);
        $criteria->groupId = [2,3,4];
        $criteria->admin = false;
        $criteria->limit = $limit;
        if ($clean != 'both'){
            $criteria->dataClean = $clean === 'dirty' ? 0 : 1;
        }
        return $criteria;
    }

    private function getManagers($clean = 'both', $limit = null) {
        $criteria = craft()->elements->getCriteria(ElementType::User);
        $criteria->groupId = [2,3];
        $criteria->admin = false;
        $criteria->limit = $limit;
        if ($clean != 'both'){
            $criteria->dataClean = $clean === 'dirty' ? 0 : 1;
        }
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
        if ($tool == 'saveUsers') {
            $users = $this->getUsers();
            foreach($users as $user) {
                craft()->users->saveUser($user);
            }
            craft()->userSession->setNotice(Craft::t('All users saved.'));
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
        if ($tool == 'removeManagersChildren') {
            $managers = $this->getManagers('dirty', 1000);
            $message = '';
            foreach($managers as $user) {
                $companies = craft()->lantra_users->getManagerCompanies($user, true);
                $companyIds = [];
                foreach($companies as $company) {
                    $companyIds[] = $company->id;
                }
                foreach($companies as $company) {
                    if ($company->companyParent->first() && in_array($company->companyParent->first()->id, $companyIds)) {
                        craft()->lantra_users->removeCompanyManager($company, $user);
                    }
                }
                $user->setContentFromPost(['dataClean' => 1]);
                if ( ! craft()->users->saveUser($user)) {
                    $message .= ' ' . $user->fullName . ' not updated.';
                };
            }
            craft()->userSession->setNotice(Craft::t(count($managers) . ' managers updated.' . $message));
            $this->redirectToPostedUrl();
        }
        if ($tool == 'setManagerUserCompany') {
            $managers = $this->getManagers('dirty', 1000);
            $message = '';
            foreach($managers as $manager) {
                $companies = craft()->lantra_users->getManagerCompanies($manager, true);
                if (! $companies) {
                    continue;
                }
                $companyId = null;
                foreach($companies as $company) {
                    $companyId = $company->id;
                }
                if ($companyId) {
                    $manager->setContentFromPost([
                        'userCompany' => [$companyId],
                        'dataClean' => 1
                    ]);
                    if ( ! craft()->users->saveUser($manager)) {
                        $message .= ' ' . $manager->fullName . ' not updated.';
                    };
                }
            }
            craft()->userSession->setNotice(Craft::t(count($managers) . ' managers user company updated'));
            $this->redirectToPostedUrl();
        }
        if ($tool == 'dataDirtyUsers') {
            $users = $this->getUsers('dirty');
            foreach($users as $user) {
                $user->setContentFromPost(['dataClean' => 0]);
                craft()->users->saveUser($user);
            }
            craft()->userSession->setNotice(Craft::t('All users have been reset (data clean).'));
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