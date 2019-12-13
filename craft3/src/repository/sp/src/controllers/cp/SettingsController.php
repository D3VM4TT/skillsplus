<?php
/**
 * Lantra Skills Plus for Craft CMS 3.x
 *
 * @link      https://coffeebean.design
 * @copyright Copyright (c) 2020 Coffee Bean Design
 */

namespace lantra\sp\controllers\cp;

use Craft;
use craft\elements\Entry;
use craft\elements\Asset;
use craft\elements\Category;
use craft\elements\User;
use craft\web\Controller;

use lantra\sp\Plugin as Lantra;
use lantra\sp\models\Settings as SettingsModel;


class SettingsController extends Controller
{
    /**
     * @throws HttpException
     */
    public function actionIndex()
    {
        $this->requireAdmin(false);

        $settingsModel = new SettingsModel;
        $settingsModel->setAttributes(Lantra::getInstance()->getSettings());
        $variables['settings'] = $settingsModel;
        $config['version'] = Lantra::getInstance()->getVersion();

        ## config for logo asset
        $volume = Craft::$app->volumes->getVolumeByHandle('theme');
        $themeFolder = Craft::$app->assets->getRootFolderByVolumeId($volume->id);
        $config['themeFolder'] = ['folder:'.$themeFolder->id.':single'];

        ## config for navigation entries
        $config['pagesSection'] = ['section:14'];
        $config['companiesSection'] = ['section:3'];
        $config['jobRoleCategoryGroup'] = ['group:1'];
        $config['assetsElementType'] = Asset::class;
        $config['entryElementType'] = Entry::class;
        $config['categoryElementType'] = Category::class;
        $variables['config'] = $config;

        $this->renderTemplate('sp/cp/settings/index', $variables);
    }

    /**
     * @throws HttpException
     */
    public function actionQueue()
    {
        $this->requireAdmin(false);
        $this->renderTemplate('sp/cp/queue');
    }

    /**
     * @throws HttpException
     */
    public function actionCache()
    {
        $this->requireAdmin(false);
        $this->renderTemplate('sp/cp/cache');
    }

    /**
     * @throws \CException
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     * @throws \yii\web\BadRequestHttpException
     */
    public function actionTools()
    {
        $this->requireAdmin(false);
        ## Craft::$app->db->createCommand()->truncateTable('searchindex');

        $tool = Craft::$app->request->getParam('tool');
        if ($tool == 'saveCompanies') {
            $topCompanies = Lantra::$app->structure->getCompanyChildren(null, false, null);
            if ($topCompanies){
                foreach($topCompanies as $company) {
                    Craft::$app->elements->saveElement($company);
                }
            }
            Craft::$app->session->setNotice(Craft::t('sp', 'All companies saved.'));
            $this->redirectToPostedUrl();
        }
        if ($tool == 'saveUnits') {
            $criteria = Entry::find();
            $criteria->section = 'units';
            $criteria->limit = null;
            foreach($criteria->all() as $unit) {
                Craft::$app->elements->saveElement($unit);
            }
            Craft::$app->session->setNotice(Craft::t('sp', 'All units saved.'));
            $this->redirectToPostedUrl();
        }
        if ($tool == 'saveUsers') {
            $users = $this->getUsers();
            foreach($users as $user) {
                Craft::$app->elements->saveElement($user);
            }
            Craft::$app->session->setNotice(Craft::t('sp', 'All users saved.'));
            $this->redirectToPostedUrl();
        }
        if ($tool == 'cleanUsernames') {
            $users = $this->getUsers();
            foreach($users as $user) {
                $user->username = str_replace('..','.', $user->username);
                $user->username = rtrim($user->username, '.');
                Craft::$app->elements->saveElement($user);
            }
            Craft::$app->session->setNotice(Craft::t('sp', 'All usernames cleaned.'));
            $this->redirectToPostedUrl();
        }
        if ($tool == 'fixLastNames') {
            $users = $this->getUsers();
            foreach($users as $user) {
                if (empty($user->lastName)) {
                    $names = $this->getNames($user->firstName);
                    $user->firstName = $names[0];
                    $user->lastName = $names[1];
                    Craft::$app->elements->saveElement($user);
                }
            }
            Craft::$app->session->setNotice(Craft::t('sp', 'All users with empty last names updated.'));
            $this->redirectToPostedUrl();
        }
        if ($tool == 'setManagerReadOnly') {
            $managers = $this->getManagers();
            $message = '';
            foreach($managers as $user) {
                $user->setAttributes([
                    'managerReadOnly' => 1
                ]);
                if ( ! Craft::$app->elements->saveElement($user)) {
                    $message .= ' ' . $user->fullName . ' not updated.';
                };
            }
            Craft::$app->session->setNotice(Craft::t('sp', 'Managers updated.' . $message));
            $this->redirectToPostedUrl();
        }
        if ($tool == 'removeManagersChildren') {
            $managers = $this->getManagers(100, 'ManagersChildren', false);
            $message = '';
            foreach($managers as $user) {
                $user->setAttributes([
                    'dataCleanManagersChildren' => 1
                ]);
                Craft::$app->elements->saveElement($user, false);
                $companies = Lantra::$app->users->getManagerCompanies($user, true);
                $companyIds = [];
                foreach($companies as $company) {
                    $companyIds[] = $company->id;
                }
                foreach($companies as $company) {
                    if ($company->companyParent->one() && in_array($company->companyParent->one()->id, $companyIds)) {
                        Lantra::$app->users->removeCompanyManager($company, $user);
                    }
                }
            }
            Craft::$app->session->setNotice(Craft::t('sp', count($managers) . ' managers updated.' . $message));
            $this->redirectToPostedUrl();
        }
        if ($tool == 'dataResetManagersChildren') {
            Lantra::$app->settings->resetDataClean('ManagersChildren');
            Craft::$app->session->setNotice('All managers have been reset.');
            $this->redirectToPostedUrl();
        }
        if ($tool == 'setManagerUserCompany') {
            $managers = $this->getManagers(100, 'ManagersUserCompany', false);
            $message = '';
            foreach($managers as $manager) {
                $manager->setAttributes([
                    'dataCleanManagersUserCompany' => 1
                ]);
                Craft::$app->elements->saveElement($manager, false);
                $companies = Lantra::$app->users->getManagerCompanies($manager, true);
                if (! $companies) {
                    continue;
                }
                $companyId = null;
                foreach($companies as $company) {
                    $companyId = $company->id;
                }
                if ($companyId) {
                    $manager->setAttributes([
                        'userCompany' => [$companyId]
                    ]);
                    if ( ! Craft::$app->elements->saveElement($manager, false)) {
                        $message .= ' ' . $manager->fullName . ' not updated.';
                    };
                }
            }
            Craft::$app->session->setNotice(Craft::t('sp', count($managers) . ' managers user company updated'));
            $this->redirectToPostedUrl();
        }
        if ($tool == 'dataResetManagersUserCompany') {
            Lantra::$app->settings->resetDataClean('ManagersUserCompany');
            Craft::$app->session->setNotice('All managers have been reset.');
            $this->redirectToPostedUrl();
        }
        if ($tool == 'setResultCache') {
            $users = $this->getUsers(500, 'ResultCache', false);
            $message = '';
            foreach($users as $user) {
                $results = $this->getUserUnitResults($user->id);
                if ($results->count()) {
                    Lantra::$app->results->saveUserResultCache($user->id, $results->find());
                }
                $user->setAttributes([
                    'dataCleanResultCache' => 1,
                    // update userType here
                    'userType' => Lantra::$app->users->canManage($user) ? 'manager' : 'member'
                ]);
                if ( ! Craft::$app->elements->saveElement($user, false)) {
                    $message .= ' ' . $user->fullName . ' not updated.';
                };
            }
            Craft::$app->session->setNotice(Craft::t('sp', count($users) . ' users results cached. ' . $message));
        }
        if ($tool == 'dataResetResultCache') {
            Lantra::$app->settings->resetDataClean('ResultCache');
            Craft::$app->session->setNotice('All users have been reset.');
            $this->redirectToPostedUrl();
        }
        if ($tool == 'setReportIncludeRequired') {
            // update existing reports
            $criteria = Entry::find();
            $criteria->section = 'reports';
            $criteria->limit = null;
            $criteria->status = null;
            foreach($criteria->all() as $report) {
                $report->setAttributes(['reportIncludeRequired' => 1]);
                Craft::$app->elements->saveElement($report, false);
            };
            Craft::$app->session->setNotice($criteria->count() . ' reports updated.');
            $this->redirectToPostedUrl();
        }
        if ($tool == 'copyNotes') {
            $criteria = Entry::find();
            $criteria->section = 'results';
            $criteria->limit = 1000;
            $criteria->type = ['unitResult', 'userResult'];
            $criteria->resultNotes = ':notempty:';
            $criteria->status = null;
            foreach($criteria->all() as $result) {
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
                $result->setAttributes([
                    'resultNotes' => '',
                    'resultComments' => $comments
                ]);
                Craft::$app->elements->saveElement($result, false);
            }
            if ($criteria->count()) {
                Craft::$app->session->setNotice($criteria->count() . ' result updated. ');
            }
            else {
                Craft::$app->session->setError('No results to update. ');
            }
            $this->redirectToPostedUrl();
        }
        if ($tool == 'copyDatabase' || $tool == 'copyDatabaseProd') {
            $server = Craft::getAlias('server');
            if ($tool == 'copyDatabaseProd') {
                $target = 'prod';
            }
            elseif ($server == 'prod' || $server == 'dev' || $server == 'local') {
                $target = 'uat';
            }
            else {
                $target = 'dev';
            }
            $result = Lantra::$app->deploy->copyDatabase($target);
            $message = Lantra::$app->deploy->message;
            if ($result) {
                Craft::$app->session->setNotice($message);
            }
            else {
                Craft::$app->session->setError($message);
            }
            $this->redirectToPostedUrl();
        }

        ## count results with notes
        $criteria = Entry::find();
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
        $this->renderTemplate('sp/cp/tools', $variables);
    }

    /**
     * @throws \yii\web\BadRequestHttpException
     */
    public function actionSaveSettings()
    {
        $this->requirePostRequest();
        $settings = Craft::$app->request->getParam('settings');

        if (Lantra::$app->settings->saveSettings($settings)) {
            Craft::$app->session->setNotice('Settings saved.');
            $this->redirectToPostedUrl();
        } else {
            Craft::$app->session->setError('Settings not saved.');
            Craft::$app->urlManager->setRouteParams(array('settings' => $settings));
        }
    }

    /**
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDeleteJob()
    {
        $elementId = Craft::$app->request->getParam('elementId');
        if ($elementId == 'all') {
            Lantra::$app->queue->clear();
        }
        else {
            Lantra::$app->queue->delete($elementId);
        }
        Craft::$app->session->setNotice('Queue updated.');
        $this->redirect('lantra/settings/queue');
    }

    /**
     * @param null $limit
     * @param null $dataCleanKey
     * @param bool $dataCleanValue
     * @param bool $count
     * @return mixed
     */
    private function getUsers($limit = null, $dataCleanKey = null, $dataCleanValue = false, $count = false) {
        $criteria = User::find();
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

    /**
     * @param null $limit
     * @param null $dataCleanKey
     * @param bool $dataCleanValue
     * @param bool $count
     * @return mixed
     */
    private function getManagers($limit = null, $dataCleanKey = null, $dataCleanValue = false, $count = false) {
        $criteria = User::find();
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
     * @param $userId
     * @return \craft\elements\db\ElementQueryInterface|\craft\elements\db\EntryQuery
     */
    private function getUserUnitResults($userId) {
        $criteria = Entry::find();
        $criteria->section = 'results';
        $criteria->type = 'unitResult';
        $criteria->authorId = $userId;
        $criteria->status = null;
        return $criteria;
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