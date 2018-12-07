<?php

namespace Craft;

class Lantra_ImportController extends Lantra_BaseController {

    public $allowAnonymous = array('actionIndex', 'actionDelete', 'actionCompanies', 'actionRoles', 'actionUsers', 'actionManagers', 'actionCompanyUsers', 'actionAssignJobRoles');
    private $dataPath = '../craft-assets/import/';

    private $companies = [];
    private $roles = [];
    private $users = [];
    private $companyUsers = [];
    private $companyManagers = [];

    private $success = 0;
    private $failed = 0;
    private $log = [];

    private $sectionIdCompanies = 3;
    private $typeIdCompany = 3;
    private $categoryGroupIdJobRoles = 1;

    private $emailDomain = 'lantra.co.uk';
    private $emails;

    // temp array legacyId => id
    private $companyTemp = [];
    private $jobRoleTemp = [];

    private $start;

    public function __construct($id, $module = null)
    {
        craft()->userSession->requireAdmin();

        $this->start = microtime(true);

        // this might take some time...
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time' ,0);
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
        $this->getData('companies');
        $this->getData('roles');
        $this->getData('users');
        $this->getData('companyManagers');
        $this->getData('companyUsers');
        $this->loadTemplate();
    }

    /**
     * Delete existing data
     */
    public function actionDelete()
    {
        $this->deleteData();
        $this->loadTemplate();
    }

    /**
     * Import Lantra Data
     *
     * @throws mixed
     */
    public function actionCompanies()
    {
        $this->getData('companies');
        $this->createCompanies();
        $this->createHierarchy();
        $this->loadTemplate(true);
    }

    /**
     * Import Lantra Data
     *
     * @throws mixed
     */
    public function actionRoles()
    {
        $this->getData('roles');
        $this->createJobRoles();
        $this->loadTemplate(true);
    }

    /**
     * Import Lantra Data
     *
     * @throws mixed
     */
    public function actionUsers()
    {
        $this->getData('users');
        $this->createUsers();
        $this->loadTemplate(true);
    }

    /**
     * Import Lantra Data
     *
     * @throws mixed
     */
    public function actionManagers()
    {
        $this->getData('companyManagers');
        $this->assignCompanyManagers();
        $this->loadTemplate(true);
    }


    /**
     * Import Lantra Data
     *
     * @throws mixed
     */
    public function actionCompanyUsers()
    {
        $this->getData('companyUsers');
        $this->assignCompanyUsers();
        $this->loadTemplate(true);
    }

    /**
     * Import Lantra Data
     *
     * @throws mixed
     */
    public function actionAssignJobRoles()
    {
        $this->assignJobRoles();
        $this->loadTemplate(true);
    }

    /**
     * private methods
     */
    private function getData($file) {
        $this->$file = $this->importCsv($file . '.csv');
    }

    private function loadTemplate($complete = false)
    {
        $variables = [
            'companies' => count($this->companies),
            'jobRoles' => count($this->roles),
            'users' => count($this->users),
            'companyUsers' => count($this->companyUsers),
            'companyManagers' => count($this->companyManagers),
            'complete' => $complete,
            'success' => $this->success,
            'failed' => $this->failed,
            'time' => (int) microtime(true) - $this->start,
            'log' => $this->log
        ];

        $this->renderTemplate('lantra/import', $variables);
    }

    private function importCsv($file)
    {
        $path = $this->dataPath . $file;
        if ( ! is_file($path)) {
            return [];
        }
        return array_map('str_getcsv', file($path));
    }

    private function deleteData()
    {
        /*

        DELETE from craft_elements WHERE id IN (SELECT id FROM craft_entries where sectionId = 3);
        DELETE from craft_categories WHERE groupId = 1;
        DELETE from craft_users WHERE admin = 0;

        */

        return;

        // nuke the companies
        $mysql = "DELETE from {{elements}} WHERE id IN (SELECT id FROM {{entries}} where sectionId = " . $this->sectionIdCompanies . ");";
        craft()->db->createCommand($mysql)->queryAll();
        // nuke the job roles
        $mysql = "DELETE from {{categories}} WHERE groupId = " . $this->categoryGroupIdJobRoles . ";";
        craft()->db->createCommand($mysql)->queryAll();
        // nuke all users (apart from admin)
        $mysql = "DELETE from {{users}} WHERE admin = 0;";
        craft()->db->createCommand($mysql)->queryAll();
    }

    private function createCompanies()
    {
        foreach($this->companies as $company) {
            if (trim($company[0]) == 'title') {
                continue;
            }

            // title, legacyId, legacyParentId
            $title = trim($company[0]);
            $legacyId = (int) trim($company[1]);
            $legacyParentId = (int) trim($company[2]);

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
            }
            else {
                $this->log [] = implode(',', $company);
                $this->failed++;
            }
        }
    }

    private function createHierarchy()
    {
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
    }

    private function createJobRoles()
    {
        foreach($this->roles as $jobRole) {
            if (trim($jobRole[0]) == 'title') {
                continue;
            }

            // title, legacyId
            $title = trim($jobRole[0]);
            $legacyId = (int) trim($jobRole[1]);

            $categoryModel = new CategoryModel();
            $categoryModel->groupId = $this->categoryGroupIdJobRoles;
            $categoryModel->getContent()->title = $title;
            $categoryModel->setContentFromPost([
                'legacyId' => $legacyId
            ]);

            if (craft()->categories->saveCategory($categoryModel)) {
                $this->success++;
            } else {
                $this->log [] = implode(',', $jobRole);
                $this->failed++;
            }
        }
    }

    private function createUsers()
    {
        // build array of legacyJobRoleId => id
        $criteria = craft()->elements->getCriteria(ElementType::Category);
        $criteria->group = 'roles';
        $criteria->limit = null;

        foreach ($criteria as $jobRole) {
            $this->jobRoleTemp[$jobRole->legacyId] = $jobRole->id;
        }

        $x = 1;
        foreach ($this->users as $user) {
            // skip headers
            if (trim($user[0]) == 'name') {
                continue;
            }

            // name, email, legacyId, legacyJobRoleId, userDateOfBirth, userStartDate, userAddress
            $names = $this->getNames($user[0]);
            $legacyEmail = $user[1];
            $legacyId = (int) trim($user[2]);
            $legacyJobRoleId = (int) trim($user[3]);
            $userDateOfBirth = trim((string)$user[4]);
            $userStartDate = trim((string)$user[5]);
            $userAddress = $user[6];

            $emailAddress = $this->getEmail($legacyEmail);

            $userModel = new UserModel();
            $userModel->email = $emailAddress;
            $userModel->username = $emailAddress;
            $userModel->firstName = $names[0];
            $userModel->lastName = $names[1];

            $userModel->getContent()->setAttributes([
                'legacyId' => $legacyId,
                'legacyEmail' => $legacyEmail,
                'legacyJobRoleId' => $legacyJobRoleId,
                'userAddress' => $userAddress
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
                $this->log [] = implode(',', $user);
                $this->log [] = implode(',', $userModel->getAllErrors());
                $this->failed++;
            }
            $x++;
        }
    }

    private function assignJobRoles()
    {
        // build array of legacyJobRoleId => id
        $criteria = craft()->elements->getCriteria(ElementType::Category);
        $criteria->group = 'roles';
        $criteria->limit = null;

        foreach ($criteria as $jobRole) {
            $this->jobRoleTemp[$jobRole->legacyId] = $jobRole->id;
        }

        $criteria = craft()->elements->getCriteria(ElementType::User);
        $criteria->limit = null;

        $x = 1;
        foreach($criteria->find() as $userModel) {
            if ($userModel->legacyJobRoleId)
            {
                $roleId = $this->getRoleId($userModel->legacyJobRoleId);
                if ($roleId) {
                    $userModel->getContent()->setAttributes(['userRole' => [$roleId]]);
                    if (craft()->users->saveUser($userModel)) {
                        $this->success++;
                    } else {
                        $this->log [] = implode(',', $userModel->getAllErrors());
                        $this->failed++;
                    }
                    $x++;
                }
            }
        }
    }

    private function assignCompanyManagers()
    {
        foreach($this->companyManagers as $manager) {

            // legacyId, legacyCompanyId
            $companyManager = $this->getUserByLegacyId((int) $manager[0]);
            $companyEntry = $this->getCompanyByLegacyId((int) $manager[1]);

            if ($companyEntry && $companyManager) {
                $companyEntry->setContentFromPost([
                    'companySecondaryManagers' => array_merge($companyEntry->companySecondaryManagers->ids(), [$companyManager->id])
                ]);
                craft()->entries->saveEntry($companyEntry);
                // make sure user is in company manager group
                craft()->userGroups->assignUserToGroups($companyManager->id, [4, 2]);
                $this->success++;
            }
        }
    }

    private function assignCompanyUsers()
    {
        foreach($this->companyUsers as $row) {

            $legacyUserId = trim($row[0]);
            $legacyCompanyId = trim($row[1]);

            // legacyId, legacyCompanyId
            $companyUser = $this->getUserByLegacyId($legacyUserId);
            $companyEntry = $this->getCompanyByLegacyId($legacyCompanyId);

            if ($companyEntry && $companyUser) {
                // skip if manager
                $managerIds = (array) $companyEntry->companySecondaryManagers->ids();
                if ( ! in_array($companyUser->id, $managerIds)) {
                    $companyUser->setContentFromPost([
                        'userCompany' => [$companyEntry->id]
                    ]);
                    craft()->elements->saveElement($companyUser, false);
                    $this->success++;
                }
                else {
                    $this->failed++;
                }
            }
        }
    }

    private function getEmail($email)
    {
        // generate an email address
        if (is_null($email) || trim($email) == '' || @in_array($email, $this->emails) || ! $this->validEmail($email)) {

            return rand(100000000, 999999999) . '@' . $this->emailDomain;
        }
        // make sure same email not given twice
        $this->emails[] = $email;
        return $email;
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

        return [$firstName, $lastName];
    }

    private function getUserByLegacyId($legacyId)
    {
        if ( ! $legacyId) {
            return null;
        }
        $criteria = craft()->elements->getCriteria(ElementType::User);
        $criteria->legacyId = $legacyId;
        return $criteria->first();
    }

    private function getCompanyByLegacyId($legacyId)
    {
        if ( ! $legacyId) {
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

        return (boolean) preg_match(
            '/^[a-zA-Z0-9.!#$%&\'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}' .
            '[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/sD',
            $email);
    }
}
