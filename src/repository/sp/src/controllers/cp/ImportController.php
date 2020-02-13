<?php
/**
 * Lantra Skills Plus for Craft CMS 3.x
 *
 * @link      https:##coffeebean.design
 * @copyright Copyright (c) 2020 Coffee Bean Design
 */

namespace lantra\sp\controllers\cp;

use Craft;
use craft\elements\Entry;
use craft\elements\Category;
use craft\elements\User;
use craft\web\Controller;
use craft\web\UploadedFile;
use DateTime;

use lantra\sp\Plugin as Lantra;
use lantra\sp\records\Import as ImportRecord;
use lantra\sp\records\Import;

class ImportController extends Controller
{
    private $success = 0;
    private $log = [];

    private $sectionIdResults = 10;
    private $sectionIdCompanies = 3;
    private $typeIdCompany = 3;
    private $categoryGroupIdJobRoles = 1;

    private $emails;

    ## temp array legacyId => id
    private $companyTemp = [];
    private $jobRoleTemp = [];

    private $limit = 1000;

    /**
     * ImportController constructor.
     * @param $id
     * @param null $module
     */
    public function __construct($id, $module = null)
    {
        $this->log  = Craft::$app->session->getFlash('importLog', []);

        ## this might take some time...
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0);
        set_time_limit(0);

        parent::__construct($id, $module);
    }

    /**
     * Import Lantra Data
     *
     * @throws mixed
     */
    public function actionIndex()
    {
        if (false != $method = Craft::$app->request->getParam('method')) {
            if (!method_exists($this, $method)){
                Craft::$app->session->setError('Method ' . $method . ' does not exist!');
            }
            return $this->$method();
        }
        $this->loadTemplate();
    }

    /**
     * @throws \yii\web\BadRequestHttpException
     */
    public function actionUpload()
    {
        if (null == $file = UploadedFile::getInstanceByName('data')) {
            Craft::$app->session->setError('No file to upload!');
            return $this->redirectToPostedUrl();
        }
        $type = Craft::$app->request->getRequiredParam('type');
        $filePath = $file->saveAsTempFile();
        $csv = array_map('str_getcsv', file($filePath));
        $total = 0;
        $number = 0;
        foreach ($csv as $row) {
            $number++;
            if ($this->isHeaderRow($row)) {
                continue;
            }
            if (!$this->validateRowLength($row, $type)) {
                $this->log[] = 'Invalid row length row number ' . $number;
                continue;
            }
            $record = new ImportRecord();
            $record->type = $type;
            $record->data = json_encode($row);
            $record->save();
            $total++;
        }
        @unlink($filePath);
        Craft::$app->session->setNotice($type . ' file uploaded, ' . $total . ' added for processing.');
        return $this->loadTemplate();
    }

    /**
     * @return mixed
     * @throws \yii\web\BadRequestHttpException
     */
    public function actionProcess()
    {
        $type = Craft::$app->request->getRequiredParam('type');
        $this->limit = Craft::$app->request->getParam('limit', 250);
        $method = 'import' . strtoupper($type);
        if (method_exists($this, $method)) {
            return $this->$method();
        }
    }

    /**
     * @return \yii\web\Response
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     * @throws \yii\web\BadRequestHttpException
     */
    public function actionUsers()
    {
        $ids = Craft::$app->request->getRequiredParam('ids');
        $refId = Craft::$app->request->getParam('refId', 'legacyId');
        $action = Craft::$app->request->getParam('userAction', 'suspend');
        $userIds = $this->getUserIdsByRef($ids, $refId);
        if (!$userIds){
            Craft::$app->session->setError('No valid users found.');
            return $this->complete('#tab-import-process');
        }
        if ($action == 'suspend'){
            $success = $this->batchSuspendUsers($userIds);
        }
        elseif ($action == 'restore') {
            $success = $this->batchRestoreUsers($userIds);
        }
        elseif ($action == 'setManagerReadOnly') {
            $success = $this->batchManagerReadOnlyUsers($userIds, 1);
        }
        elseif ($action == 'unsetManagerReadOnly') {
            $success = $this->batchManagerReadOnlyUsers($userIds, 0);
        }
        elseif ($action == 'delete'){
            $success = $this->batchDeleteUsers($userIds);
        }
        Craft::$app->session->setNotice($success . ' users updated.');
        return $this->complete('#tab-import-users');
    }

    /**
     *
     */
    private function loadTemplate()
    {
        $variables = [
            'unprocessed' => [
                'companies'         => $this->countDataByType('companies'),
                'roles'             => $this->countDataByType('roles'),
                'users'             => $this->countDataByType('users'),
                'companyUsers'      => $this->countDataByType('companyUsers'),
                'companyManagers'   => $this->countDataByType('companyManagers'),
                'results'           => $this->countDataByType('results')
            ],
            'dataClean' => [
                'companies' => $this->dataCleanTotal('CompanyParent', true, 'companies'),
                'users' => $this->dataCleanTotal('JobRole', true, 'users'),
                'usernames' => $this->dataCleanTotal('Username', true, 'users'),
                'passwords' => $this->dataCleanTotal('Password', true, 'users')
            ],
            'log' => $this->log,
            'limit' => $this->limit
        ];

        return $this->renderTemplate('sp/cp/import', $variables);
    }

    /**
     * @return \yii\web\Response
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\BadRequestHttpException
     */
    private function removeData()
    {
        ImportRecord::deleteAll();
        Craft::$app->session->setNotice(Craft::t('sp', 'All raw import data removed.'));
        return $this->complete();
    }

    /**
     * @return \yii\web\Response
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\BadRequestHttpException
     */
    private function resetData()
    {
        ImportRecord::updateAll(['processed' => 0]);
        Craft::$app->session->setNotice(Craft::t('sp', 'All data reset.'));
        return $this->complete();
    }

    /**
     * @return \yii\web\Response
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\BadRequestHttpException
     */
    private function removeCompanies()
    {
        $this->deleteDataByType('companies');
        Craft::$app->session->setNotice(Craft::t('sp', 'All companies import data removed.'));
        return $this->complete();
    }

    /**
     * @return \yii\web\Response
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     * @throws \yii\web\BadRequestHttpException
     */
    private function resetCompanies()
    {
        $this->resetDataByType('companies');
        Craft::$app->session->setNotice(Craft::t('sp', 'All companies import data reset.'));
        return $this->complete();
    }

    /**
     * @return \yii\web\Response
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\BadRequestHttpException
     */
    private function removeRoles()
    {
        $this->deleteDataByType('roles');
        Craft::$app->session->setNotice(Craft::t('sp', 'All roles import data removed.'));
        return $this->complete();
    }

    /**
     * @return \yii\web\Response
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     * @throws \yii\web\BadRequestHttpException
     */
    private function resetRoles()
    {
        $this->resetDataByType('roles');
        Craft::$app->session->setNotice(Craft::t('sp', 'All roles import data reset.'));
        return $this->complete();
    }

    /**
     * @return \yii\web\Response
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\BadRequestHttpException
     */
    private function removeUsers()
    {
        $this->deleteDataByType('users');
        Craft::$app->session->setNotice(Craft::t('sp', 'All users import data removed.'));
        return $this->complete();
    }

    /**
     * @return \yii\web\Response
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     * @throws \yii\web\BadRequestHttpException
     */
    private function resetUsers()
    {
        $this->resetDataByType('users');
        Craft::$app->session->setNotice(Craft::t('sp', 'All users import data reset.'));
        return $this->complete();
    }

    /**
     * @return \yii\web\Response
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\BadRequestHttpException
     */
    private function removeResults()
    {
        $this->deleteDataByType('results');
        Craft::$app->session->setNotice(Craft::t('sp', 'All results import data removed.'));
        return $this->complete();
    }

    /**
     * @return \yii\web\Response
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     * @throws \yii\web\BadRequestHttpException
     */
    private function resetResults()
    {
        $this->resetDataByType('results');
        Craft::$app->session->setNotice(Craft::t('sp', 'All result import data reset.'));
        return $this->complete();
    }

    /**
     * @return \yii\web\Response
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\BadRequestHttpException
     */
    private function removeCompanyUsers()
    {
        $this->deleteDataByType('companyUsers');
        Craft::$app->session->setNotice(Craft::t('sp', 'All company users import data removed.'));
        return $this->complete();
    }

    /**
     * @return \yii\web\Response
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     * @throws \yii\web\BadRequestHttpException
     */
    private function resetCompanyUsers()
    {
        $this->resetDataByType('companyUsers');
        Craft::$app->session->setNotice(Craft::t('sp', 'All company users import data reset.'));
        return $this->complete();
    }

    /**
     * @return \yii\web\Response
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\BadRequestHttpException
     */
    private function removeCompanyManagers()
    {
        $this->deleteDataByType('companyManagers');
        Craft::$app->session->setNotice(Craft::t('sp', 'All company managers import data removed.'));
        return $this->complete();
    }

    /**
     * @return \yii\web\Response
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     * @throws \yii\web\BadRequestHttpException
     */
    private function resetCompanyManagers()
    {
        $this->resetDataByType('companyManagers');
        Craft::$app->session->setNotice(Craft::t('sp', 'All company managers import data reset.'));
        return $this->complete();
    }

    /**
     * @return \yii\web\Response
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     * @throws \yii\web\BadRequestHttpException
     */
    private function importCompanies()
    {
        $this->createCompanies($this->limit);
        $unprocessed = $this->countDataByType('companies');
        Craft::$app->session->setNotice($this->success  . ' companies imported. ' . $unprocessed . ' remaining.');
        return $this->complete();
    }

    /**
     * @return \yii\web\Response
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     * @throws \yii\web\BadRequestHttpException
     */
    private function importRoles()
    {
        $this->createRoles($this->limit);
        $unprocessed = $this->countDataByType('roles');
        Craft::$app->session->setNotice($this->success  . ' roles imported. ' . $unprocessed . ' remaining.');
        return $this->complete();
    }

    /**
     * @return \yii\web\Response
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     * @throws \yii\web\BadRequestHttpException
     */
    private function importUsers()
    {
        $this->createUsers($this->limit);
        $unprocessed = $this->countDataByType('users');
        Craft::$app->session->setNotice($this->success  . ' users imported. ' . $unprocessed . ' remaining.');
        return $this->complete();
    }

    /**
     * @return \yii\web\Response
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     * @throws \yii\web\BadRequestHttpException
     */
    private function importResults()
    {
        $this->createResults($this->limit);
        $unprocessed = $this->countDataByType('results');
        Craft::$app->session->setNotice($this->success  . ' results imported. ' . $unprocessed . ' remaining.');
        return $this->complete();
    }

    /**
     * @return void|\yii\web\Response
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     * @throws \yii\web\BadRequestHttpException
     */
    private function importCompanyManagers()
    {
        $companyManagers = $this->getDataByType('companyManagers', $this->limit);
        if (! $companyManagers) {
            return Craft::$app->session->setNotice('No company managers to process.');
        }
        foreach ($companyManagers as $id => $manager) {
            ## legacyId, legacyCompanyId
            $companyManager = $this->getUserByLegacyId((int)$manager[0]);
            $companyEntry = $this->getCompanyByLegacyId((int)$manager[1]);
            $managerReadOnly = isset($manager[2]) && $manager[2] == '1';

            if ($companyEntry && $companyManager) {
                $companyEntry->setAttributes([
                    'companyPrimaryManagers' => array_merge($companyEntry->companyPrimaryManagers->ids(), [$companyManager->id])
                ]);
                Craft::$app->elements->saveElement($companyEntry);
                ## make sure user is in company manager group
                Craft::$app->users->assignUserToGroups($companyManager->id, [4, 2]);
                $companyManager->setAttributes([
                    ## add manager to company
                    'userCompany' => [$companyEntry->id],
                    'managerReadOnly' => $managerReadOnly
                ]);
                Craft::$app->elements->saveElement($companyManager, false);
                $this->success++;
            }
            $this->setProcessed($id);
        }
        Craft::$app->session->setNotice($this->success . ' managers assigned.');
        return $this->complete();
    }

    /**
     * @return void|\yii\web\Response
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     * @throws \yii\web\BadRequestHttpException
     */
    private function importCompanyUsers()
    {
        $companyUsers = $this->getDataByType('companyUsers', $this->limit);
        if (!$companyUsers) {
            return Craft::$app->session->setNotice('No company users to process.');
        }
        foreach ($companyUsers as $id => $user) {
            $legacyUserId = trim($user[0]);
            $legacyCompanyId = trim($user[1]);
            ## legacyId, legacyCompanyId
            $companyUser = $this->getUserByLegacyId($legacyUserId);
            $companyEntry = $this->getCompanyByLegacyId($legacyCompanyId);
            if ($companyEntry && $companyUser) {
                ## skip if manager
                $managerIds = (array)$companyEntry->companySecondaryManagers->ids();
                if (!in_array($companyUser->id, $managerIds)) {
                    $companyUser->setAttributes([
                        'userCompany' => [$companyEntry->id]
                    ]);
                    Craft::$app->elements->saveElement($companyUser, false);
                    $this->success++;
                }
            }
            $this->setProcessed($id);
        }
        Craft::$app->session->setNotice($this->success . ' users assigned.');
        return $this->complete();
    }

    /**
     * BATCH USERS METHODS
     */

    /**
     * @param $ids
     * @return int
     * @throws \yii\db\Exception
     */
    private function batchSuspendUsers($ids)
    {
        $mysql = "UPDATE {{%users}} SET suspended = '1' WHERE id IN (" . implode(',', $ids) . ")";
        return Craft::$app->db->createCommand($mysql)->execute();
    }

    /**
     * @param $ids
     * @return int
     * @throws \yii\db\Exception
     */
    private function batchRestoreUsers($ids)
    {
        $mysql = "UPDATE {{%users}} SET suspended = '0' WHERE id IN (" . implode(',', $ids) . ")";
        return Craft::$app->db->createCommand($mysql)->execute();
    }

    /**
     * @param $ids
     * @param int $value
     * @return mixed
     * @throws \yii\db\Exception
     */
    private function batchManagerReadOnlyUsers($ids, $value = 1)
    {
        $mysql = "UPDATE {{%content}} SET field_managerReadOnly = '" . $value . "' WHERE elementId IN (" . implode(',', $ids) . ")";
        return Craft::$app->db->createCommand($mysql)->execute();
    }

    /**
     * @param $ids
     * @return int
     * @throws \yii\db\Exception
     */
    private function batchDeleteUsers($ids)
    {
        $mysql = "DELETE FROM {{%users}} WHERE id IN (" . implode(',', $ids) . ")";
        return Craft::$app->db->createCommand($mysql)->execute();
    }


    /**
     * PROCESS METHODS
     */

    /**
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     * @throws \yii\web\BadRequestHttpException
     */
    private function buildHierarchy()
    {
        $criteria = Entry::find();
        $criteria->section = 'companies';
        $criteria->limit = $this->limit;
        $criteria->dataCleanCompanyParent = 0;
        $companies = $criteria->all();
        $total = count($companies);
        ## build array of legacyId => id
        $this->tempCompanyLegacyIds();
        foreach ($companies as $company) {
            $parentId = $this->getCompanyId($company->legacyParentId);
            $data = ['dataCleanCompanyParent' => 1];
            if ($parentId) {
                $data['companyParent'] = [$parentId];
            }
            $company->setAttributes($data);
            Craft::$app->elements->saveElement($company, false);

        }
        Craft::$app->session->setNotice('Company hierarchy created for ' . $total . ' companies.');
        $this->complete('#tab-import-process');
    }

    /**
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     * @throws \yii\web\BadRequestHttpException
     */
    private function assignJobRoles()
    {
        ## build array of legacyJobRoleId => id
        $criteria = Category::find();
        $criteria->group = 'roles';
        $criteria->limit = null;
        $jobRoles = $criteria->all();
        foreach ($jobRoles as $jobRole) {
            $this->jobRoleTemp[$jobRole->legacyId] = $jobRole->id;
        }
        $criteria = User::find();
        $criteria->groupId = [2,3,4];
        $criteria->admin = false;
        $criteria->limit = $this->limit;
        $criteria->dataCleanJobRole = 0;
        $users = $criteria->all();
        $total = count($users);
        foreach ($users as $user) {
            $roleId = $user->legacyJobRoleId ? $this->getRoleId($user->legacyJobRoleId) : null;
            $data = ['dataCleanJobRole' => 1];
            if ($roleId) {
                $data['userRole'] = [$roleId];
            }
            $user->setAttributes($data);
            Craft::$app->elements->saveElement($user, false);
        }
        Craft::$app->session->setNotice('Job roles assigned to ' . $total . ' users.');
        $this->complete('#tab-import-process');
    }

    /**
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     * @throws \yii\web\BadRequestHttpException
     */
    private function setUsernames()
    {
        $criteria = User::find();
        $criteria->groupId = [2,3,4];
        $criteria->admin = false;
        $criteria->limit = $this->limit;
        $criteria->dataCleanUsername = 0;
        $users = $criteria->all();
        foreach ($users as $user) {
            $username = strtolower(str_replace('.', '', $user->firstName));
            if ($user->lastName) {
                $username.= '.' . strtolower(str_replace('.', '', $user->lastName));
            }
            $username = str_replace(' ', '', $username);
            $username = str_replace('..', '.', $username);
            $username = rtrim($username, '.');
            $user->username = $username;
            $user->dataCleanUsername = 1;
            if (Craft::$app->elements->saveElement($user)) {
                $this->success++;
            }
            else {
                $this->log[] = 'Could not save user [' . $user->id . '] ' . json_encode($user->getFirstErrors());
            }
        }
        Craft::$app->session->setNotice('Username updated for ' . $this->success . ' users.');
        $this->complete('#tab-import-process');
    }

    /**
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     * @throws \yii\web\BadRequestHttpException
     */
    private function setPasswords()
    {
        $criteria = User::find();
        $criteria->groupId = [2,3,4];
        $criteria->admin = false;
        $criteria->limit = $this->limit;
        $criteria->dataCleanPassword = 0;
        $users = $criteria->all();
        foreach ($users as $user) {
            $user->newPassword = empty($user->userDateOfBirth) ? '010101' : $user->userDateOfBirth->format('dmy');
            $user->dataCleanPassword = 1;
            if (Craft::$app->elements->saveElement($user)) {
                $this->success++;
            }
            else {
                $this->log[] = 'Could not save user [' . $user->id . '] ' . json_encode($user->getFirstErrors());
            }
        }
        Craft::$app->session->setNotice('Password updated for ' . $this->success . ' users.');
        $this->complete('#tab-import-process');
    }

    /**
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     * @throws \yii\web\BadRequestHttpException
     */
    private function deleteCompanies()
    {
        $this->deleteEntriesBySectionId(3);
        Craft::$app->session->setNotice('All imported companies deleted.');
        $this->complete('#tab-import-delete');
    }

    /**
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     * @throws \yii\web\BadRequestHttpException
     */
    private function deleteResults()
    {
        $this->deleteEntriesBySectionId(10);
        Craft::$app->session->setNotice('All imported results deleted.');
        $this->complete('#tab-import-delete');
    }

    /**
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     * @throws \yii\web\BadRequestHttpException
     */
    private function deleteRoles()
    {
        $mysql = "DELETE {{%categories}} FROM {{%categories}}
          JOIN {{%content}} ON {{%content}}.elementId = {{%categories}}.id
          WHERE {{%categories}}.groupId = 1
          AND {{%content}}.field_dataImported = 1";
        Craft::$app->db->createCommand($mysql)->query();
        Craft::$app->session->setNotice('All imported roles deleted.');
        $this->complete('#tab-import-delete');
    }

    /**
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     * @throws \yii\web\BadRequestHttpException
     */
    private function deleteUsers()
    {
        $mysql = "DELETE {{%users}} FROM {{%users}} 
          JOIN {{%content}} ON {{%content}}.elementId = {{%users}}.id
          WHERE {{%users}}.admin = 0
          AND {{%content}}.field_dataImported = 1";
        Craft::$app->db->createCommand($mysql)->query();
        Craft::$app->session->setNotice('All imported users deleted.');
        $this->complete('#tab-import-delete');
    }

    /**
     * @param $id
     * @return false|int
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    private function setProcessed($id)
    {
        $record = ImportRecord::findOne($id);
        $record->processed = 1;
        return $record->update();
    }

    /**
     * @param $sectionId
     * @throws \yii\db\Exception
     */
    private function deleteEntriesBySectionId ($sectionId)
    {
        $mysql = "DELETE FROM {{%elements}}
            WHERE {{%elements}}.id IN (
              SELECT {{%entries}}.id 
              FROM {{%entries}} 
              JOIN {{%content}} ON {{%content}}.elementId = {{%entries}}.id
              WHERE {{%entries}}.sectionId = '" . $sectionId. "'
              AND {{%content}}.field_dataImported = 1
          );";
        Craft::$app->db->createCommand($mysql)->execute();
    }

    /**
     * @param $type
     * @param null $limit
     * @param int $processed
     * @return array
     */
    private function getDataByType($type, $limit = null, $processed = 0)
    {
        $rows = ImportRecord::find()
            ->where(['type' => $type, 'processed' => $processed])
            ->limit($limit)
            ->all();
        $return = [];
        foreach($rows as $row) {
            $return[$row['id']] = json_decode($row['data']);
        }
        return $return;
    }

    /**
     * @param $type
     * @param int $processed
     * @return int|string
     */
    private function countDataByType($type, $processed = 0)
    {
        return ImportRecord::find()->where(['type' => $type, 'processed' => $processed])->count();
    }

    /**
     * @param $type
     * @return mixed
     */
    private function deleteDataByType($type)
    {
        return ImportRecord::deleteAll(['type' => $type]);
    }

    /**
     * @param $type
     * @return \yii\db\DataReader
     * @throws \yii\db\Exception
     */
    private function resetDataByType($type)
    {
        $mysql = "UPDATE {{%lantra_import}} SET `processed` = 0 WHERE `type` = '" . $type . "';";
        return Craft::$app->db->createCommand($mysql)->query();
    }

    /**
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     */
    private function switchManagers()
    {
        $criteria = Entry::find();
        $criteria->section = 'companies';
        foreach($criteria->all() as $company) {
            $managers = $company->companySecondaryManagers->ids();
            $company->setAttributes([
                'companyPrimaryManagers' => $managers,
                'companySecondaryManagers' => []
            ]);
            Craft::$app->elements->saveElement($company, false);
        }
    }

    /**
     * @param int $limit
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     */
    private function createCompanies($limit = 250)
    {
        $companies = $this->getDataByType('companies', $limit);
        foreach ($companies as $id => $company) {
            ## title, legacyId, legacyParentId
            $title = utf8_encode(trim($company[0]));
            $legacyId = (int)trim($company[1]);
            $legacyParentId = (int)trim($company[2]);

            $entry = new Entry();
            $entry->sectionId = $this->sectionIdCompanies;
            $entry->typeId = $this->typeIdCompany;
            $entry->enabled = true;
            $entry->title = $title;
            $entry->setAttributes([
                'dataImported' => true,
                'legacyId' => $legacyId,
                'legacyParentId' => $legacyParentId
            ]);
            if (Craft::$app->elements->saveElement($entry)) {
                $this->success++;
                $this->setProcessed($id);
            } else {
                Craft::error("Lantra Import: Company: " . json_encode($entry->getFirstErrors()),__METHOD__);
                $this->log[] = 'Could not save company [' . $legacyId . '] ' . json_encode($entry->getFirstErrors());
            }
        }
    }

    /**
     * @param $limit
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    private function createRoles($limit)
    {
        $roles = $this->getDataByType('roles', $limit);
        foreach ($roles as $id => $jobRole) {
            ## title, legacyId
            $title = trim($jobRole[0]);
            $legacyId = (int)trim($jobRole[1]);

            $category = new Category();
            $category->groupId = $this->categoryGroupIdJobRoles;
            $category->title = $title;
            $category->setAttributes([
                'dataImported' => true,
                'legacyId' => $legacyId
            ]);

            if (Craft::$app->elements->saveElement($category)) {
                $this->success++;
                $this->setProcessed($id);
            } else {
                Craft::error("Lantra Import: Role: " . json_encode($category->getFirstErrors()),__METHOD__);
                $this->log[] = 'Could not save role [' . $legacyId . ']' . json_encode($category->getFirstErrors());
            }
        }
    }

    /**
     * @param $limit
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     */
    private function createUsers($limit)
    {
        $users = $this->getDataByType('users', $limit);

        ## build array of roles legacyJobRoleId => id
        $criteria = Category::find();
        $criteria->group = 'roles';
        $criteria->limit = null;
        foreach ($criteria as $jobRole) {
            $this->jobRoleTemp[$jobRole->legacyId] = $jobRole->id;
        }

        foreach ($users as $id => $user) {
            ## username, name, email, legacyId, legacyJobRoleId, userDateOfBirth, userStartDate, userAddress, userMembershipNumber
            $username = $user[0];
            $names = $this->getNames($user[1]);
            $legacyEmail = $user[2];
            $legacyId = (int)trim($user[3]);
            $legacyJobRoleId = (int)trim($user[4]);
            $userDateOfBirth = trim((string)$user[5]);
            $userStartDate = trim((string)$user[6]);
            $userAddress = utf8_encode($user[7]);
            $userMembershipNumber = utf8_encode($user[8]);
            $userDummyEmail = 0;

            if (empty($username) || $username != $legacyEmail) {
                $username = Lantra::$app->users->generateUsername($username, $names[0], $names[1]);
            }

            ## generate an email address
            if (is_null($legacyEmail) || trim($legacyEmail) == '' || @in_array($legacyEmail, $this->emails) || !$this->validEmail($legacyEmail) || Craft::$app->users->getUserByUsernameOrEmail($legacyEmail)) {
                $emailAddress = Lantra::$app->users->generateEmail($names[0], $names[1], $username);
                $userDummyEmail = 1;
            } else {
                $emailAddress = $legacyEmail;
            }

            ## make sure same email not given twice this loop
            $this->emails[] = $legacyEmail;
            $user = new User();
            $user->username = str_replace(' ', '', $username);
            $user->email = $emailAddress;
            $user->firstName = $names[0];
            $user->lastName = $names[1];
            $user->setAttributes([
                'dataImported' => true,
                'legacyId' => $legacyId,
                'legacyEmail' => $legacyEmail,
                'legacyJobRoleId' => $legacyJobRoleId,
                'userAddress' => $userAddress,
                'userMembershipNumber' => $userMembershipNumber,
                'userDummyEmail' => $userDummyEmail
            ]);
            if (strlen($userDateOfBirth) == 10) {
                $user->setAttributes([
                    'userDateOfBirth' => DateTime::createFromFormat('d/m/Y', $userDateOfBirth)
                ]);
            }
            if (strlen($userStartDate) == 10) {
                $user->setAttributes([
                    'userStartDate' => DateTime::createFromFormat('d/m/Y', $userStartDate)
                ]);
            }
            $roleId = $this->getRoleId($legacyJobRoleId);
            if ($roleId) {
                $user->setAttributes(['userRole' => [$roleId]]);
            }
            $groups = [4];
            if (Craft::$app->elements->saveElement($user) && Craft::$app->users->assignUserToGroups($user->id, $groups)) {
                $this->success++;
                $this->setProcessed($id);
            } else {
                Craft::error("Lantra Import: User: [" . $legacyId . '] ' . json_encode($user->getFirstErrors()),__METHOD__);
                $this->log[] = 'Could not save user [' . $legacyId . '] ' . json_encode($user->getFirstErrors());
            }
        }
    }

    /**
     * @param $limit
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     */
    private function createResults($limit)
    {
        $results = $this->getDataByType('results', $limit);
        foreach ($results as $id => $result) {
            ## type, legacyUserId, legacyUnitId, title, postDate, startDate, endDate, expiryDate, resultLocation, resultHours,  resultPoints, resultEndorsedDate, resultStatus, resultNotes, resultEvidence
            $legacyUserId = (int)$result[1];
            $legacyUnitId = (int)$result[2];
            $title = $result[3];
            $postDate = $result[4];
            $resultStartDate = $result[5];
            $resultEndDate = $result[6];
            $expiryDate = $result[7];
            $resultLocation = $result[8];
            $resultHours = $result[9];
            $resultPoints = $result[10];
            $resultEndorsedDate = $result[11];
            $resultStatus = strtolower($result[12]) == 'unendorsed' ? 'pending' : 'endorsed';
            $resultNotes = $result[13];
            $legacyResultFiles = $result[14];
            $author = $this->getUserByLegacyId($legacyUserId);

            if (!$author) {
                $this->log[] = 'Could not save result legacyUserId not found [' . $legacyUserId . ']';
                $this->setProcessed($id);
                continue;
            }

            $unitEntry = $legacyUnitId ? $this->getEntryByLegacyId($legacyUnitId) : null;
            $resultType = $unitEntry ? 'unitResult' : 'userResult';

            $entry = new Entry();
            $entry->sectionId = $this->sectionIdResults;
            $entry->typeId = $resultType == 'unitResult' ? 10 : 17;
            $entry->enabled = true;
            $entry->authorId = $author->id;
            $entry->postDate = DateTime::createFromFormat('d/m/Y', $postDate);

            if (!empty(trim($result[7]))) {
                $entry->expiryDate = DateTime::createFromFormat('d/m/Y', $expiryDate);
            }

            if ($resultType == 'unitResult' && $unitEntry) {
                $entry->setAttributes([
                    'resultUnit' => [$unitEntry->id]
                ]);
                $entry->title = $unitEntry->title;
            } else {
                $entry->title = utf8_encode($title);
            }
            $entry->setAttributes([
                'dataImported' => true,
                'resultOwner' => [$author->id],
                'resultStatus' => $resultStatus,
                'resultStartDate' => DateTime::createFromFormat('d/m/Y', $resultStartDate),
                'resultFinishDate' => DateTime::createFromFormat('d/m/Y',$resultEndDate),
                'resultLocation' => $resultLocation,
                'resultHours' => (int)$resultHours ? (int)$resultHours : null,
                'resultPoints' => (int)$resultPoints ? (int)$resultPoints : null,
                'resultNotes' => utf8_encode($resultNotes),
                'resultEndorsedDate' => $resultEndorsedDate ? DateTime::createFromFormat('d/m/Y', $resultEndorsedDate) : null,
                'legacyResultFiles' => $legacyResultFiles

            ]);
            if (Craft::$app->elements->saveElement($entry)) {
                $this->success++;
                $this->setProcessed($id);
            } else {
                Craft::error("Lantra Import: Result: [". $id . "] " . json_encode($entry->getFirstErrors()),__METHOD__);
                $this->log[] = 'Could not save result legacyUserId [' . $legacyUserId . '] legacyUnitId [' . $legacyUnitId . '] title [' . $title . '] ' .
                    json_encode($entry->getFirstErrors());
            }
        }
    }


    /**
     * @param null $tab
     * @return \yii\web\Response
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\BadRequestHttpException
     */
    private function complete($tab = null)
    {
        Craft::$app->session->setFlash('importLog', $this->log);
        if ($tab) {
            return $this->redirect(Craft::$app->getRequest()->getPathInfo() . $tab);
        }
        return $this->redirectToPostedUrl();
    }

    /**
     * UTILITY METHODS
     */

    /**
     *
     */
    private function tempCompanyLegacyIds()
    {
        ## build array of legacyId => id
        $criteria = Entry::find();
        $criteria->section = 'companies';
        $criteria->limit = null;
        $allCompanies = $criteria->all();
        foreach ($allCompanies as $company) {
            $this->companyTemp[$company->legacyId] = $company->id;
        }
    }

    /**
     * @param $row
     * @return bool
     */
    private function isHeaderRow($row)
    {
        if (! count($row)) {
            return true;
        }
        $first = strtolower($row[0]);
        if ($first == 'legacyId' || $first == 'type' || $first == 'username' || $first == 'title') {
            return true;
        }
        return false;
    }

    /**
     * @param $row
     * @param $type
     * @return bool
     */
    private function validateRowLength($row, $type)
    {
        $lengths = [
            'companies'         => 3,
            'roles'             => 2,
            'users'             => 9,
            'results'           => 15,
            'companyUsers'      => 2,
            'companyManagers'   => 2,
        ];
        return count($row) == $lengths[$type];
    }

    /**
     * @param $dataCleanKey
     * @param bool $dataCleanValue
     * @param string $type
     * @return int
     */
    private function dataCleanTotal($dataCleanKey, $dataCleanValue = false, $type = 'companies') {
        if ($type == 'companies') {
            $criteria = Entry::find();
            $criteria->section = 'companies';
        }
        if ($type == 'users') {
            $criteria = User::find();
            $criteria->groupId = [2,3,4];
            $criteria->admin = false;
        }
        $fieldName = 'dataClean' . $dataCleanKey;
        $criteria->$fieldName = $dataCleanValue ? 1 : 0;
        return $criteria->count();
    }

    /**
     * @param $ids
     * @param string $refId
     * @return array
     * @throws \yii\db\Exception
     */
    private function getUserIdsByRef($ids, $refId = 'legacyId')
    {
        $ids = explode(',', $ids);
        $ids = array_map('trim', $ids);
        $ids = $this->cleanIds($ids);
        if (!count($ids)) {
            return [];
        }
        $userIds = [];
        if ($refId == 'userId') {
            $userIds = $ids;
        }
        elseif ($refId == 'legacyId' && count($ids)) {
            $mysql = "SELECT elementId from {{%content}} WHERE field_legacyId IN (" . implode(',', $ids) . ");";
            $result = Craft::$app->db->createCommand($mysql)->query();
            foreach($result as $row){
                $userIds[] = $row['elementId'];
            }
        }
        return $userIds;
    }

    /**
     * @param $ids
     * @return array
     */
    private function cleanIds($ids)
    {
        $return = [];
        foreach($ids as $id) {
            if ((int) $id) {
                $return[] = $id;
            }
        }
        return $return;
    }


    /**
     * @param $company
     * @param $manager
     * @return bool
     */
    private function isParentCompanyManager($company, $manager)
    {
        $parents = $this->getParents($company);
        $managerIds = [];
        foreach ($parents as $parent) {
            foreach ($parent->companySecondaryManagers as $m) {
                $managerIds[] = $m->id;
            }
        }
        return in_array($manager->id, $managerIds);
    }

    /**
     * @param $company
     * @param array $parents
     * @return array
     */
    private function getParents($company, $parents = [])
    {
        if (!$company->companyParent->count()) {
            return $parents;
        }
        $parents[] = $company->companyParent->one();
        return $this->getParents($company->companyParent->one(), $parents);
    }

    /**
     * @param $fullName
     * @return array
     */
    private function getNames($fullName)
    {
        $name = explode(' ', $fullName);
        if (count($name) == 1) {
            $firstName = $name[0];
            $lastName = '';
        } else if (count($name) == 2) {
            $firstName = $name[0];
            $lastName = $name[1];
        } else {
            $lastName = array_pop($name);
            $firstName = implode(' ', $name);
        }

        return [utf8_encode($firstName), utf8_encode($lastName)];
    }

    /**
     * @param $legacyId
     * @return array|\craft\base\ElementInterface|User|null
     */
    private function getUserByLegacyId($legacyId)
    {
        if (!$legacyId) {
            return null;
        }
        $criteria = User::find();
        $criteria->legacyId = $legacyId;
        $criteria->status = null;
        return $criteria->one();
    }

    /**
     * @param $legacyId
     * @return array|\craft\base\ElementInterface|Entry|null
     */
    private function getEntryByLegacyId($legacyId)
    {
        if (!$legacyId) {
            return null;
        }
        $criteria = Entry::find();
        $criteria->legacyId = $legacyId;
        return $criteria->one();
    }

    /**
     * @param $legacyId
     * @return array|\craft\base\ElementInterface|Entry|null
     */
    private function getCompanyByLegacyId($legacyId)
    {
        if (!$legacyId) {
            return null;
        }
        $criteria = Entry::find();
        $criteria->section = 'companies';
        $criteria->legacyId = $legacyId;
        return $criteria->one();
    }

    /**
     * @param $legacyId
     * @return mixed|null
     */
    private function getCompanyId($legacyId)
    {
        return $legacyId && isset($this->companyTemp[$legacyId]) ? $this->companyTemp[$legacyId] : null;
    }

    /**
     * @param $legacyId
     * @return mixed|null
     */
    private function getRoleId($legacyId)
    {
        return $legacyId && isset($this->jobRoleTemp[$legacyId]) ? $this->jobRoleTemp[$legacyId] : null;
    }

    /**
     * @param $email
     * @return bool
     */
    private function validEmail($email) {
        return (boolean)preg_match(
            '/^[a-zA-Z0-9.!#$%&\'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}' .
            '[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/sD',
            $email);
    }
}
