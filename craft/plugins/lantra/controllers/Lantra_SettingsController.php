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

    private function getUsers($limit = null) {
        $criteria = craft()->elements->getCriteria(ElementType::User);
        $criteria->groupId = [2,3,4];
        $criteria->admin = false;
        $criteria->limit = $limit;
        return $criteria;
    }

    private function getManagers($limit = null, $dataCleanKey = null, $dataCleanValue = false, $count = false) {
        $criteria = craft()->elements->getCriteria(ElementType::User);
        $criteria->groupId = [2,3];
        $criteria->admin = false;
        $criteria->limit = $limit;
        if ($dataCleanKey) {
            $fieldName = 'dataClean' . $dataCleanKey;
            $criteria->$fieldName = $dataCleanValue ? 1 : 0;
        }
        return $count ? $criteria->count() : $criteria;
    }

    /**
     * @throws HttpException
     */
    public function actionTools()
    {
        craft()->db->createCommand()->truncateTable('searchindex');
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
        if ($tool == 'cleanUsernames') {
            $users = $this->getUsers();
            foreach($users as $user) {
                $user->username = str_replace('..','.', $user->username);
                $user->username = rtrim($user->username, '.');
                craft()->users->saveUser($user);
            }
            craft()->userSession->setNotice(Craft::t('All usernames cleaned.'));
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
            $managers = $this->getManagers(100, 'ManagersChildren', false);
            $message = '';
            foreach($managers as $user) {
                $user->setContentFromPost([
                    'dataCleanManagersChildren' => 1
                ]);
                craft()->elements->saveElement($user, false);
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
            }
            craft()->userSession->setNotice(Craft::t(count($managers) . ' managers updated.' . $message));
            $this->redirectToPostedUrl();
        }
        if ($tool == 'dataResetManagersChildren') {
            craft()->lantra_settings->resetDataClean('ManagersChildren');
            craft()->userSession->setNotice('All managers have been reset.');
            $this->redirectToPostedUrl();
        }
        if ($tool == 'setManagerUserCompany') {
            $managers = $this->getManagers(100, 'ManagersUserCompany', false);
            $message = '';
            foreach($managers as $manager) {
                $manager->setContentFromPost([
                    'dataCleanManagersUserCompany' => 1
                ]);
                craft()->elements->saveElement($manager, false);
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
                        'userCompany' => [$companyId]
                    ]);
                    if ( ! craft()->elements->saveElement($manager, false)) {
                        $message .= ' ' . $manager->fullName . ' not updated.';
                    };
                }
            }
            craft()->userSession->setNotice(Craft::t(count($managers) . ' managers user company updated'));
            $this->redirectToPostedUrl();
        }
        if ($tool == 'dataResetManagersUserCompany') {
            craft()->lantra_settings->resetDataClean('ManagersUserCompany');
            craft()->userSession->setNotice('All managers have been reset.');
            $this->redirectToPostedUrl();
        }
        if ($tool == 'copyDatabase') {
            $environmentVariables = craft()->config->get('environmentVariables');
            $server = $environmentVariables['server'];
            if ($server == 'prod' || $server == 'dev' || $server == 'local') {
                $target = 'uat';
            }
            else {
                $target = 'dev';
            }
            $result = craft()->lantra_deploy->copyDatabase($target);
            $message = craft()->lantra_deploy->message;
            if ($result) {
                craft()->userSession->setNotice($message);
            }
            else {
                craft()->userSession->setError($message);
            }
            $this->redirectToPostedUrl();
        }
        $variables = [
            'dataCleanManagersChildrenTotal' => $this->getManagers(null, 'ManagersChildren', 1, true),
            'dataCleanManagersUserCompanyTotal' => $this->getManagers(null, 'ManagersUserCompany', 1, true),
        ];
        $this->renderTemplate('lantra/settings/tools', $variables);
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