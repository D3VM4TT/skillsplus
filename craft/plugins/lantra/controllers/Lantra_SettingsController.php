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

    private function getUsers($limit = null, $dataCleanKey = null, $dataCleanValue = false, $count = false) {
        $criteria = craft()->elements->getCriteria(ElementType::User);
        $criteria->groupId = [2,3,4];
        $criteria->admin = false;
        $criteria->limit = $limit;
        $criteria->order = 'id';
        if ($dataCleanKey) {
            $fieldName = 'dataClean' . $dataCleanKey;
            $criteria->$fieldName = $dataCleanValue ? 1 : 0;
        }
        return $count ? $criteria->count() : $criteria;
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

    private function getUserUnitResults($userId) {
        $criteria = craft()->elements->getCriteria(ElementType::Entry);
        $criteria->section = 'results';
        $criteria->type = 'unitResult';
        $criteria->authorId = $userId;
        $criteria->status = null;
        return $criteria;
    }

    /**
     *
     */
    public function actionDeleteJob()
    {
        $elementId = craft()->request->getParam('elementId');
        if ($elementId == 'all') {
            craft()->lantra_queue->clear();
        }
        else {
            craft()->lantra_queue->delete($elementId);
        }
        craft()->userSession->setNotice(Craft::t('Queue updated.'));
        $this->redirect('lantra/settings/queue');
    }

    /**
     *
     */
    public function actionRunJob()
    {
        $elementId = craft()->request->getParam('elementId');
        craft()->lantra_queue->run($elementId);
        craft()->userSession->setNotice(Craft::t('Queue job ran.'));
        $this->redirect('lantra/settings/queue');
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
        if ($tool == 'saveUnits') {
            $criteria = craft()->elements->getCriteria(ElementType::Entry);
            $criteria->section = 'units';
            $criteria->limit = null;
            foreach($criteria->find() as $unit) {
                craft()->entries->saveEntry($unit);
            }
            craft()->userSession->setNotice(Craft::t('All units saved.'));
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
        if ($tool == 'fixLastNames') {
            $users = $this->getUsers();
            foreach($users as $user) {
                if (empty($user->lastName)) {
                    $names = $this->getNames($user->firstName);
                    $user->firstName = $names[0];
                    $user->lastName = $names[1];
                    craft()->users->saveUser($user);
                }
            }
            craft()->userSession->setNotice(Craft::t('All users with empty last names updated.'));
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
        if ($tool == 'setResultCache') {
            $users = $this->getUsers(500, 'ResultCache', false);
            $message = '';
            foreach($users as $user) {
                $results = $this->getUserUnitResults($user->id);
                if ($results->count()) {
                    craft()->lantra_results->saveUserResultCache($user->id, $results->find());
                }
                $user->setContentFromPost([
                    'dataCleanResultCache' => 1,
                    // update userType here
                    'userType' => craft()->lantra_users->canManage($user) ? 'manager' : 'member'
                ]);
                if ( ! craft()->elements->saveElement($user, false)) {
                    $message .= ' ' . $user->fullName . ' not updated.';
                };
            }
            craft()->userSession->setNotice(Craft::t(count($users) . ' users results cached. ' . $message));
        }
        if ($tool == 'dataResetResultCache') {
            craft()->lantra_settings->resetDataClean('ResultCache');
            craft()->userSession->setNotice('All users have been reset.');
            $this->redirectToPostedUrl();
        }
        if ($tool == 'setReportIncludeRequired') {
            // update existing reports
            $criteria = craft()->elements->getCriteria(ElementType::Entry);
            $criteria->section = 'reports';
            $criteria->limit = null;
            $criteria->status = null;
            foreach($criteria->find() as $report) {
                $report->setContentFromPost(['reportIncludeRequired' => 1]);
                craft()->elements->saveElement($report, false);
            };
            craft()->userSession->setNotice($criteria->count() . ' reports updated.');
            $this->redirectToPostedUrl();
        }
        if ($tool == 'copyNotes') {
            $criteria = craft()->elements->getCriteria(ElementType::Entry);
            $criteria->section = 'results';
            $criteria->limit = 1000;
            $criteria->type = ['unitResult', 'userResult'];
            $criteria->resultNotes = ':notempty:';
            $criteria->status = null;
            foreach($criteria->find() as $result) {
                $comments['new1'] = array(
                    'type' => 3,
                    'enabled' => true,
                    'fields' => [
                        'user' => [$result->authorId],
                        'comment' => $result->resultNotes,
                        'date' => time(),
                        'read' => 1
                    ]
                );
                $result->setContentFromPost([
                    'resultNotes' => '',
                    'resultComments' => $comments
                ]);
                craft()->elements->saveElement($result, false);
            }
            if ($criteria->count()) {
                craft()->userSession->setNotice($criteria->count() . ' result updated. ');
            }
            else {
                craft()->userSession->seError('No results to update. ');
            }
            $this->redirectToPostedUrl();
        }
        if ($tool == 'copyDatabase' || $tool == 'copyDatabaseProd') {
            $environmentVariables = craft()->config->get('environmentVariables');
            $server = $environmentVariables['server'];
            if ($tool == 'copyDatabaseProd') {
                $target = 'prod';
            }
            elseif ($server == 'prod' || $server == 'dev' || $server == 'local') {
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

        ## count results with notes
        $criteria = craft()->elements->getCriteria(ElementType::Entry);
        $criteria->section = 'results';
        $criteria->limit = null;
        $criteria->type = ['unitResult', 'userResult'];
        $criteria->resultNotes = ':notempty:';
        $criteria->status = null;
        $resultNotes = $criteria->count();

        $variables = [
            'dataCleanResultNotes' => $resultNotes,
            'dataCleanManagersChildrenTotal' => $this->getManagers(null, 'ManagersChildren', 1, true),
            'dataCleanManagersUserCompanyTotal' => $this->getManagers(null, 'ManagersUserCompany', 1, true),
            'dataCleanResultCacheTotal' => $this->getUsers(null, 'ResultCache', 1, true),
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

    /**
     * @param $name
     * @return array
     */
    private function getNames($name) {
        $parts = explode(' ', trim($name));
        if (count($parts) == 1) {
            $firstName = $parts[0];
            $lastName = '';
        } else if (count($parts) == 2) {
            $firstName = $parts[0];
            $lastName = $parts[1];
        } else {
            $lastName = array_pop($parts);
            $firstName = implode(' ', $parts);
        }

        return [utf8_encode($firstName), utf8_encode($lastName)];
    }
}