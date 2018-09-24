<?php

namespace Craft;

class Lantra_ImportController extends Lantra_BaseController {

    public $allowAnonymous = array('actionIndex', 'actionCompanies', 'actionRoles', 'actionUsers');
    private $dataPath = '../import/';

    private $companies = [];
    private $roles = [];
    private $users = [];

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
        $this->assignCompanyManagers();
        $this->loadTemplate(true);
    }

    private function getData($file) {
        $this->$file = $this->importCsv($file . '.csv');
    }

    private function loadTemplate($complete = false)
    {
        $variables = [
            'companies' => count($this->companies),
            'jobRoles' => count($this->roles),
            'users' => count($this->users),
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

    private function deleteData() {

        return;

        /*

        SQL to nuke all data:

        DELETE from craft_elements WHERE id IN (SELECT id FROM craft_entries where sectionId = 3);
        DELETE from craft_categories where groupId = 1;
        DELETE from craft_users WHERE admin = 0;

         */

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
            $entryModel = new EntryModel();
            $entryModel->sectionId = $this->sectionIdCompanies;
            $entryModel->typeId = $this->typeIdCompany;
            $entryModel->enabled = true;
            $entryModel->getContent()->title = $company[0];
            $entryModel->setContentFromPost([
                'legacyId' => (int) trim($company[1]),
                'legacyParentId' => (int) trim($company[2]),
                'legacyManagerEmail' => $company[3]
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
            $categoryModel = new CategoryModel();
            $categoryModel->groupId = $this->categoryGroupIdJobRoles;
            $categoryModel->getContent()->title = trim($jobRole[1]);
            $categoryModel->setContentFromPost([
                'legacyId' => (int) trim($jobRole[0])
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
        foreach($this->users as $user) {
            if ($x == 250) {
                break;
            }
            $names = $this->getNames($user[0]);
            $emailAddress = $this->getEmail($user[1]);

            $userModel = new UserModel();
            $userModel->email = $emailAddress;
            $userModel->username = $emailAddress;
            $userModel->firstName = $names[0];
            $userModel->lastName = $names[1];

            $userModel->getContent()->setAttributes([
                'legacyId' => (int) $user[2],
                'legacyEmail' => $user[1],
                'legacyCompanyId' => (int) trim($user[3]),
                'legacyGroup' => $user[4],
                'legacyJobRoleId' => (int) trim($user[5]),
                'userAddress' => trim($user[8])
            ]);

            if (strlen(trim($user[6]) == 10)) {
                $userModel->getContent()->setAttributes([
                    'userDateOfBirth' => DateTime::createFromFormat('d/m/Y', $user[6])
                ]);
            }

            if (strlen(trim($user[7]) == 10)) {
                $userModel->getContent()->setAttributes([
                    'userStartDate' => DateTime::createFromFormat('d/m/Y', $user[7])
                ]);
            }

            $companyId = $this->getCompanyId($user[3]);
            if ($companyId) {
                $userModel->getContent()->setAttributes(['userCompany' => [$companyId]]);
            }

            $roleId = $this->getRoleId($user[5]);
            if ($roleId) {
                $userModel->getContent()->setAttributes(['userJobRole' => [$roleId]]);
            }

            $groups = [4];
            // add user to managers group
            if ($user[4] == 'Manager') {
                $groups[] = 2;
            }
            // add user to scheme managers group
            if ($user[4] == 'Administrator') {
                $groups[] = 1;
            }

            if (craft()->users->saveUser($userModel) && craft()->userGroups->assignUserToGroups($userModel->id, $groups)) {
                $this->success++;
            }
            else {
                $this->log [] = implode(',', $user);
                $this->log [] = implode(',', $userModel->getAllErrors());
                $this->failed++;
            }
            $x++;
        }
    }

    private function assignCompanyManagers()
    {
        $criteria = craft()->elements->getCriteria(ElementType::Entry);
        $criteria->section = 'companies';
        $criteria->limit = null;

        foreach ($criteria->find() as $entryModel)
        {
            $userCriteria = craft()->elements->getCriteria(ElementType::User);
            $userCriteria->legacyEmail = $entryModel->legacyManagerEmail;
            $user = $userCriteria->first();
            if ($user) {
                $entryModel->setContentFromPost([
                    'companyManager' => [$user->id]
                ]);
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
