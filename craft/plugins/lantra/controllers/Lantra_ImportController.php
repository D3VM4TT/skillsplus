<?php

namespace Craft;

class Lantra_ImportRecord extends BaseRecord
{
    /**
     * Return table name.
     *
     * @return string
     */
    public function getTableName()
    {
        return 'lantra_import';
    }

    /**
     * Return table attributes.
     *
     * @return array
     */
    protected function defineAttributes()
    {
        return array(
            'type' => AttributeType::String,
            'data' => AttributeType::String,
            'processed' => AttributeType::Bool
        );
    }
}

class Lantra_ImportModel extends BaseModel
{
    protected function defineAttributes()
    {
        return array(
            'type' => AttributeType::String,
            'data' => AttributeType::String,
            'processed' => AttributeType::Bool
        );
    }
}

class Lantra_ImportController extends Lantra_BaseController
{
    public $allowAnonymous = array('actionIndex', 'actionUpload');

    private $success = 0;
    private $log = [];

    private $sectionIdResults = 10;
    private $sectionIdCompanies = 3;
    private $typeIdCompany = 3;
    private $categoryGroupIdJobRoles = 1;

    private $emails;

    // temp array legacyId => id
    private $companyTemp = [];
    private $jobRoleTemp = [];

    private $limit = 250;

    public function __construct($id, $module = null)
    {
        craft()->userSession->requireAdmin();

        $this->log = craft()->userSession->getFlash('log', []);

        // this might take some time...
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
        $process = craft()->request->getParam('process');
        if ($process) {
            $this->$process();
            craft()->userSession->setFlash('log', $this->log);
            craft()->request->redirect('/admin/lantra/import');
        }
        $this->loadTemplate();
    }

    /**
     * @throws Exception
     * @throws HttpException
     * @throws \CException
     */
    public function actionUpload() {
        $type = craft()->request->getRequiredPost('type');
        $file = \CUploadedFile::getInstanceByName('data');

        if (is_null($file)) {
            craft()->userSession->setError(Craft::t('No file to upload!'));
            $this->loadTemplate();
        }

        $filePath = AssetsHelper::getTempFilePath($file->extensionName);
        $file->saveAs($filePath);
        $csv = array_map('str_getcsv', file($filePath));
        $total = 0;
        $number = 0;
        foreach ($csv as $row) {
            $number++;
            if ($this->isHeaderRow($row)) {
                continue;
            }
            if (! $this->validateRowLength($row, $type)) {
                $this->log[] = 'Invalid row length row number ' . $number;
                continue;
            }
            $record = new Lantra_ImportRecord();
            $record->type = $type;
            $record->data = json_encode($row);
            $record->save();
            $total++;
        }
        @unlink($filePath);
        craft()->userSession->setNotice($type . ' file uploaded, ' . $total . ' added for processing.');
        $this->loadTemplate();
    }

    private function isHeaderRow($row) {
        if (! count($row)) {
            return true;
        }
        $first = strtolower($row[0]);
        if ($first == 'legacyId' || $first == 'type' || $first == 'username' || $first == 'title') {
            return true;
        }
        return false;
    }

    private function validateRowLength($row, $type) {
        $lengths = [
            'companies'         => 3,
            'roles'             => 2,
            'users'             => 8,
            'results'           => 15,
            'companyUsers'      => 2,
            'companyManagers'   => 2,
        ];
        return count($row) == $lengths[$type];
    }

    ## DATA FUNCTIONS ##

    public function removeData() {
        Lantra_ImportRecord::model()->deleteAll();
        craft()->userSession->setNotice(Craft::t('All raw import data removed.'));
    }

    public function resetData() {
        Lantra_ImportRecord::model()->updateAll(['processed' => 0]);
        craft()->userSession->setNotice(Craft::t('All data reset.'));
    }

    public function removeCompanies() {
        $this->deleteDataByType('companies');
        craft()->userSession->setNotice(Craft::t('All companies import data removed.'));
    }

    public function removeRoles() {
        $this->deleteDataByType('roles');
        craft()->userSession->setNotice(Craft::t('All roles import data removed.'));
    }

    public function removeUsers() {
        $this->deleteDataByType('users');
        craft()->userSession->setNotice(Craft::t('All users import data removed.'));
    }

    public function removeResults() {
        $this->deleteDataByType('results');
        craft()->userSession->setNotice(Craft::t('All results import data removed.'));
    }

    public function removeCompanyUsers() {
        $this->deleteDataByType('companyManagers');
        craft()->userSession->setNotice(Craft::t('All company users import data removed.'));
    }

    public function removeCompanyManagers() {
        $this->deleteDataByType('companyUsers');
        craft()->userSession->setNotice(Craft::t('All company managers import data removed.'));
    }

    ## IMPORT METHODS ##

    public function importCompanies() {
        $this->createCompanies($this->limit);
        $unprocessed = $this->countDataByType('companies');
        craft()->userSession->setNotice($this->success  . ' companies imported. ' . $unprocessed . ' remaining.');
    }

    public function importRoles() {
        $this->createRoles($this->limit);
        $unprocessed = $this->countDataByType('roles');
        craft()->userSession->setNotice($this->success  . ' roles imported. ' . $unprocessed . ' remaining.');
    }

    public function importUsers() {
        $this->createUsers($this->limit);
        $unprocessed = $this->countDataByType('users');
        craft()->userSession->setNotice($this->success  . ' users imported. ' . $unprocessed . ' remaining.');
    }

    public function importResults() {
        $this->createResults($this->limit);
        $unprocessed = $this->countDataByType('results');
        craft()->userSession->setNotice($this->success  . ' users imported. ' . $unprocessed . ' remaining.');
    }

    ## PROCESS METHODS ##

    public function buildHierarchy() {
        // @todo limit this process too?
        // build array of legacyId => id
        $criteria = craft()->elements->getCriteria(ElementType::Entry);
        $criteria->section = 'companies';
        $criteria->limit = null;
        foreach ($criteria->find() as $company) {
            $this->companyTemp[$company->legacyId] = $company->id;
        }
        $criteria = craft()->elements->getCriteria(ElementType::Entry);
        $criteria->section = 'companies';
        $criteria->limit = null;
        foreach ($criteria->find() as $entryModel) {
            $parentId = $this->getCompanyId($entryModel->legacyParentId);
            if ($parentId) {
                $entryModel->setContentFromPost([
                    'companyParent' => [$parentId]
                ]);
                craft()->entries->saveEntry($entryModel);
            }
        }
        craft()->userSession->setNotice('Company hierarchy created.');
    }

    public function assignJobRoles()  {
        // @todo limit this process too??
        // build array of legacyJobRoleId => id
        $criteria = craft()->elements->getCriteria(ElementType::Category);
        $criteria->group = 'roles';
        $criteria->limit = null;
        foreach ($criteria as $jobRole) {
            $this->jobRoleTemp[$jobRole->legacyId] = $jobRole->id;
        }
        $criteria = craft()->elements->getCriteria(ElementType::User);
        $criteria->limit = null;
        foreach ($criteria->find() as $userModel) {
            if ($userModel->legacyJobRoleId) {
                $roleId = $this->getRoleId($userModel->legacyJobRoleId);
                if ($roleId) {
                    $userModel->getContent()->setAttributes(['userRole' => [$roleId]]);
                    if (craft()->users->saveUser($userModel)) {
                        $this->success++;
                    }
                }
            }
        }
        craft()->userSession->setNotice('Job roles assigned to ' . $this->success . ' users.');
    }

    public function assignCompanyManagers() {
        $companyManagers = $this->getDataByType('companyManagers', $this->limit);
        if (! $companyManagers) {
            return craft()->userSession->setNotice('No company managers to process.');
        }
        foreach ($companyManagers as $id => $manager) {
            // legacyId, legacyCompanyId
            $companyManager = $this->getUserByLegacyId((int)$manager[0]);
            $companyEntry = $this->getCompanyByLegacyId((int)$manager[1]);
            $managerReadOnly = isset($manager[2]) && $manager[2] == '1';

            if ($companyEntry && $companyManager) {
                $companyEntry->setContentFromPost([
                    'companyPrimaryManagers' => array_merge($companyEntry->companyPrimaryManagers->ids(), [$companyManager->id])
                ]);
                craft()->entries->saveEntry($companyEntry);
                // make sure user is in company manager group
                craft()->userGroups->assignUserToGroups($companyManager->id, [4, 2]);
                $companyManager->setContentFromPost([
                    'managerReadOnly' => $managerReadOnly
                ]);
                craft()->elements->saveElement($companyManager, false);
                $this->success++;
            }
            $this->setProcessed($id);
        }
        craft()->userSession->setNotice($this->success . ' managers assigned.');
    }

    public function assignCompanyUsers() {
        $companyUsers = $this->getDataByType('companyUsers', $this->limit);
        if (! $companyUsers) {
            return craft()->userSession->setNotice('No company users to process.');
        }
        foreach ($companyUsers as $id => $user) {
            $legacyUserId = trim($user[0]);
            $legacyCompanyId = trim($user[1]);
            // legacyId, legacyCompanyId
            $companyUser = $this->getUserByLegacyId($legacyUserId);
            $companyEntry = $this->getCompanyByLegacyId($legacyCompanyId);
            if ($companyEntry && $companyUser) {
                // skip if manager
                $managerIds = (array)$companyEntry->companySecondaryManagers->ids();
                if (!in_array($companyUser->id, $managerIds)) {
                    $companyUser->setContentFromPost([
                        'userCompany' => [$companyEntry->id]
                    ]);
                    craft()->elements->saveElement($companyUser, false);
                    $this->success++;
                }
            }
            $this->setProcessed($id);
        }
        craft()->userSession->setNotice($this->success . ' users assigned.');
    }

    ## DELETE METHODS ##

    public function deleteCompanies() {
        $this->deleteEntriesBySectionId(3);
        craft()->userSession->setNotice('All Craft companies deleted.');
    }

    public function deleteResults() {
        $this->deleteEntriesBySectionId(10);
        craft()->userSession->setNotice('All Craft results deleted.');
    }

    public function deleteRoles() {
        $mysql = "DELETE from {{categories}} WHERE groupId = 1";
        craft()->db->createCommand($mysql)->query();
        craft()->userSession->setNotice('All Craft Roles deleted.');
    }

    public function deleteUsers() {
        $mysql = "DELETE from {{users}} WHERE admin = 0;";
        craft()->db->createCommand($mysql)->query();
        craft()->userSession->setNotice('All Craft Users deleted.');
    }

    public function cleanHierarchy() {
        $criteria = craft()->elements->getCriteria(ElementType::Entry);
        $criteria->section = 'companies';
        $criteria->companyUpdated = false;
        $criteria->order = 'title';
        $criteria->limit = 200;
        $count = 0;

        foreach ($criteria->find() as $company) {
            $managerIds = [];
            foreach ($company->companySecondaryManagers as $m) {
                if (!$this->isParentCompanyManager($company, $m)) {
                    $managerIds[] = $m->id;
                }
            }
            $company->setContentFromPost([
                'companyUpdated' => true,
                'companyPrimaryManagers' => $managerIds
            ]);
            if (craft()->entries->saveEntry($company)) {
                $count++;
            }
        }
    }

    ## PRIVATE METHODS ##

    private function setProcessed($id) {
        return Lantra_ImportRecord::model()->updateByPk($id, ['processed' => 1]);
    }

    private function deleteEntriesBySectionId ($sectionId) {
        $mysql = "DELETE from {{elements}} WHERE {{elements}}.id IN (SELECT {{entries}}.id FROM {{entries}} where sectionId = '" . $sectionId. "');";
        craft()->db->createCommand($mysql)->query();
    }

    private function getDataByType($type, $limit = null, $processed = 0) {
        $criteria = new \CDbCriteria();
        $criteria->condition = 'type = :type AND processed = :processed';
        $criteria->params = array(':type' => $type, ':processed' => $processed);
        $criteria->limit = $limit;
        $rows = Lantra_ImportRecord::model()->findAll($criteria);
        $return = [];
        foreach($rows as $row) {
            $return[$row['id']] = json_decode($row['data']);
        }
        return $return;
    }

    private function countDataByType($type, $processed = 0) {
        return Lantra_ImportRecord::model()->countByAttributes(['type' => $type, 'processed' => $processed]);
    }

    private function deleteDataByType($type) {
        return Lantra_ImportRecord::model()->deleteAllByAttributes(['type' => $type]);
    }

    private function resetDataByType($type) {
        return Lantra_ImportRecord::model()->updateAll(['processed' => 0], ['type' => $type]);
    }

    private function switchManagers() {
        $criteria = craft()->elements->getCriteria(ElementType::Entry);
        $criteria->section = 'companies';
        foreach($criteria->find() as $company) {
            $managers = $company->companySecondaryManagers->ids();
            $company->setContentFromPost([
                'companyPrimaryManagers' => $managers,
                'companySecondaryManagers' => []
            ]);
            craft()->elements->saveElement($company, false);
        }
    }

    private function createCompanies($limit = 250) {
        $companies = $this->getDataByType('companies', $limit);
        foreach ($companies as $id => $company) {
            // title, legacyId, legacyParentId
            $title = utf8_encode(trim($company[0]));
            $legacyId = (int)trim($company[1]);
            $legacyParentId = (int)trim($company[2]);

            $entryModel = new EntryModel();
            $entryModel->sectionId = $this->sectionIdCompanies;
            $entryModel->typeId = $this->typeIdCompany;
            $entryModel->enabled = true;
            $entryModel->getContent()->title = $title;
            $entryModel->setContentFromPost([
                'legacyId' => $legacyId,
                'legacyParentId' => $legacyParentId
            ]);
            if (craft()->entries->saveEntry($entryModel)) {
                $this->success++;
            } else {
                $this->log[] = 'Could not save company ' . $title;
            }
            $this->setProcessed($id);
        }
    }

    private function createRoles($limit) {
        $roles = $this->getDataByType('roles', $limit);
        foreach ($roles as $id => $jobRole) {
            // title, legacyId
            $title = trim($jobRole[0]);
            $legacyId = (int)trim($jobRole[1]);

            $categoryModel = new CategoryModel();
            $categoryModel->groupId = $this->categoryGroupIdJobRoles;
            $categoryModel->getContent()->title = $title;
            $categoryModel->setContentFromPost([
                'legacyId' => $legacyId
            ]);

            if (craft()->categories->saveCategory($categoryModel)) {
                $this->success++;
            } else {
                $this->log[] = 'Could not save role ' . $title;
            }
            $this->setProcessed($id);
        }
    }

    private function createUsers($limit) {
        $users = $this->getDataByType('users', $limit);

        // build array of roles legacyJobRoleId => id
        $criteria = craft()->elements->getCriteria(ElementType::Category);
        $criteria->group = 'roles';
        $criteria->limit = null;
        foreach ($criteria as $jobRole) {
            $this->jobRoleTemp[$jobRole->legacyId] = $jobRole->id;
        }

        foreach ($users as $id => $user) {
            // username, name, email, legacyId, legacyJobRoleId, userDateOfBirth, userStartDate, userAddress
            $username = $user[0];
            $names = $this->getNames($user[1]);
            $legacyEmail = $user[2];
            $legacyId = (int)trim($user[3]);
            $legacyJobRoleId = (int)trim($user[4]);
            $userDateOfBirth = trim((string)$user[5]);
            $userStartDate = trim((string)$user[6]);
            $userAddress = utf8_encode($user[7]);
            $userDummyEmail = 0;

            if ($username != $legacyEmail) {
                $username = craft()->lantra_users->generateUsername($username, $names[0], $names[1]);
            }

            // generate an email address
            if (is_null($legacyEmail) || trim($legacyEmail) == '' || @in_array($legacyEmail, $this->emails) || !$this->validEmail($legacyEmail)) {
                $emailAddress = craft()->lantra_users->generateEmail($names[0], $names[1], $username);
                $userDummyEmail = 1;
            } else {
                $emailAddress = $legacyEmail;
            }

            // make sure same email not given twice
            $this->emails[] = $legacyEmail;
            $userModel = new UserModel();
            $userModel->username = $username;
            $userModel->email = $emailAddress;
            $userModel->firstName = $names[0];
            $userModel->lastName = $names[1];
            $userModel->getContent()->setAttributes([
                'legacyId' => $legacyId,
                'legacyEmail' => $legacyEmail,
                'legacyJobRoleId' => $legacyJobRoleId,
                'userAddress' => $userAddress,
                'userDummyEmail' => $userDummyEmail
            ]);
            if (strlen($userDateOfBirth) == 10) {
                $userModel->getContent()->setAttributes([
                    'userDateOfBirth' => DateTime::createFromFormat('d/m/Y', $userDateOfBirth)
                ]);
            }
            if (strlen($userStartDate) == 10) {
                $userModel->getContent()->setAttributes([
                    'userStartDate' => DateTime::createFromFormat('d/m/Y', $userStartDate)
                ]);
            }
            $roleId = $this->getRoleId($user[5]);
            if ($roleId) {
                $userModel->getContent()->setAttributes(['userRole' => [$roleId]]);
            }
            $groups = [4];
            if (craft()->users->saveUser($userModel) && craft()->userGroups->assignUserToGroups($userModel->id, $groups)) {
                $this->success++;
            } else {
                $this->log[] = 'Could not save user ' . $legacyId;
            }
            $this->setProcessed($id);
        }
    }

    private function createResults($limit) {
        $results = $this->getDataByType('results', $limit);
        foreach ($results as $id => $result) {
            // type, legacyUserId, legacyUnitId, title, postDate, startDate, endDate, expiryDate, resultLocation, resultHours,  resultValue, resultEndorsedDate, resultStatus, resultNotes, resultEvidence
            $legacyUserId = (int)$result[1];
            $legacyUnitId = (int)$result[2];
            $title = $result[3];
            $postDate = $result[4];
            $resultStartDate = $result[5];
            $resultEndDate = $result[6];
            $expiryDate = $result[7];
            $resultLocation = $result[8];
            $resultHours = $result[9];
            $resultValue = $result[10];
            $resultEndorsedDate = $result[11];
            $resultStatus = strtolower($result[12]) == 'unendorsed' ? 'pending' : 'endorsed';
            $resultNotes = $result[13];
            $legacyResultFiles = $result[14];
            $author = $this->getUserByLegacyId($legacyUserId);

            if (!$author) {
                $this->log[] = 'legacyUserId not found ' . $legacyUserId;
                $this->setProcessed($id);
                continue;
            }

            $unitEntry = $legacyUnitId ? $this->getEntryByLegacyId($legacyUnitId) : null;
            $resultType = $unitEntry ? 'unitResult' : 'userResult';

            $entryModel = new EntryModel();
            $entryModel->sectionId = $this->sectionIdResults;
            $entryModel->typeId = $resultType == 'unitResult' ? 10 : 17;
            $entryModel->enabled = true;
            $entryModel->authorId = $author->id;
            $entryModel->postDate = DateTime::createFromFormat('d/m/Y', $postDate);

            if (!empty(trim($result[7]))) {
                $entryModel->expiryDate = DateTime::createFromFormat('d/m/Y', $expiryDate);
            }

            if ($resultType == 'unitResult') {
                $entryModel->setContentFromPost([
                    'resultUnit' => [$unitEntry->id]
                ]);
                $entryModel->getContent()->title = $unitEntry->title;
            } else {
                $entryModel->getContent()->title = utf8_encode($title);
            }
            $entryModel->setContentFromPost([
                'resultOwner' => [$author->id],
                'resultStatus' => $resultStatus,
                'resultStartDate' => DateTime::createFromFormat('d/m/Y', $resultStartDate),
                'resultFinishDate' => DateTime::createFromFormat('d/m/Y',$resultEndDate),
                'resultLocation' => $resultLocation,
                'resultHours' => (int)$resultHours ? (int)$resultHours : null,
                'resultValue' => (int)$resultValue ? (int)$resultValue : null,
                'resultNotes' => utf8_encode($resultNotes),
                'resultEndorsedDate' => $resultEndorsedDate ? DateTime::createFromFormat('d/m/Y', $resultEndorsedDate) : null,
                'legacyResultFiles' => $legacyResultFiles

            ]);
            if (craft()->entries->saveEntry($entryModel)) {
                $this->success++;
            } else {
                $this->log[] = 'Could not save result';
            }
            $this->setProcessed($id);
        }
    }

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
            'log' => $this->log,
            'limit' => $this->limit
        ];

        $this->renderTemplate('lantra/import/index', $variables);
    }

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

    private function getParents($company, $parents = [])
    {
        if (!$company->companyParent->count()) {
            return $parents;
        }
        $parents[] = $company->companyParent->first();
        return $this->getParents($company->companyParent->first(), $parents);
    }

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

    private function getUserByLegacyId($legacyId)
    {
        if (!$legacyId) {
            return null;
        }
        $criteria = craft()->elements->getCriteria(ElementType::User);
        $criteria->legacyId = $legacyId;
        $criteria->status = null;
        return $criteria->first();
    }

    private function getEntryByLegacyId($legacyId)
    {
        if (!$legacyId) {
            return null;
        }
        $criteria = craft()->elements->getCriteria(ElementType::Entry);
        $criteria->legacyId = $legacyId;
        return $criteria->first();
    }

    private function getCompanyByLegacyId($legacyId)
    {
        if (!$legacyId) {
            return null;
        }
        $criteria = craft()->elements->getCriteria(ElementType::Entry);
        $criteria->section = 'companies';
        $criteria->legacyId = $legacyId;
        return $criteria->first();
    }

    private function getCompanyId($legacyId)
    {
        return $legacyId && isset($this->companyTemp[$legacyId]) ? $this->companyTemp[$legacyId] : null;
    }

    private function getRoleId($legacyId)
    {
        return $legacyId && isset($this->jobRoleTemp[$legacyId]) ? $this->jobRoleTemp[$legacyId] : null;
    }

    private function validEmail($email) {
        return (boolean)preg_match(
            '/^[a-zA-Z0-9.!#$%&\'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}' .
            '[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/sD',
            $email);
    }
}
