<?php
namespace Craft;

class Lantra_UsersService extends BaseApplicationComponent
{
    private $nodeId = 0;
    private $hierarchyFilter = [];

    /**
     * Get manager hierarchy [replaced by Lantra_StructureService.php getHierarchy()]
     *
     * @param $user
     * @return array
     * @throws mixed
     */
    function getManagerHierarchy(UserModel $user = null) {
        $this->nodeId = 0;
        if (is_null($user)) {
            $user = craft()->userSession->getUser();
        }
        $return = [];
        $array = [];
        $managerTeams = $this->getManagerTeams($user);
        $managerCompanies = $this->getManagerCompanies($user);
        $type = 'companies';
        // filter individuals company id
        $individualCompany = $this->getIndividualCompany();
        if ($individualCompany) {
            $this->hierarchyFilter[] = $individualCompany->id;
        }
        // admins and scheme managers start with all top level parents
        if ($user->admin or $user->isInGroup('schemeManagers')) {
            $array = $this->getCompaniesByParentId(false);
        }
        // does this user manage companies?
        elseif (count($managerCompanies)) {
            $array = $managerCompanies;
        }
        // does this user manage teams?
        elseif (count($managerTeams)) {
            $array = $managerTeams;
            $type = 'teams';
        }
        foreach ($array as $element) {
            if (!in_array($element->id, $this->hierarchyFilter)) {
                $return[$element->id] = $this->addHierarchyNode($element, $type);
            }
        }
        return $return;
    }

    /**
     * Build a hierarchy array [replaced by Lantra_StructureService.php getHierarchy()]
     *
     * @param object $element
     * @param string $type
     * @param string $prefix
     * @return array
     * @throws mixed
     */
    function addHierarchyNode($element, $type = 'companies', $prefix = '')
    {
        if (in_array($element->id, $this->hierarchyFilter)) {
            return;
        }
        $this->nodeId++;
        $return = [
            'nodeId'    => $this->nodeId,
            'nodeType'  => $type,
            'elementId' => $element->id,
            'managers'  => [],
            'children'  => [],
            'icon'      => 'company'
        ];
        if ($type == 'label') {
            $return['title'] =  $element->title;
        }
        if ($type == 'companies') {
            $return['title'] =  $element->title;
            $return['icon'] = 'group';
            // add the company managers
            $companyManagers = $this->getCompanyMangers($element);
            if (false != $companyManagerCount = count($companyManagers)) {
                $label = (object)['id' => 0, 'title' => 'Company Managers (' . $companyManagerCount . ')'];
                $companyManagersNode = $this->addHierarchyNode($label, 'label');
                foreach ($companyManagers as $user) {
                    $companyManagersNode['children'][$user->id] = $this->addHierarchyNode($user, 'users');
                }
                $return['children']['managers'] = $companyManagersNode;
            }
            // add the company users
            $companyUsers = $this->getCompanyUsers($element->id);
            if (false != $companyUsersCount = count($companyUsers)) {
                $label = (object)['id' => 0, 'title' => 'Company Users (' . $companyUsersCount . ')'];
                $companyUsersNode = $this->addHierarchyNode($label, 'label');
                foreach ($companyUsers as $user) {
                    $companyUsersNode['children'][$user->id] = $this->addHierarchyNode($user, 'users');
                }
                $return['children']['users'] = $companyUsersNode;
            }
            // add the company teams
            $companyTeams = $this->getCompanyTeams($element->id);
            foreach($companyTeams as $team) {
                $return['children'][$team->id] = $this->addHierarchyNode($team, 'teams');
            }
            // add the child companies
            $childCompanies = $this->getCompaniesByParentId($element->id);
            foreach($childCompanies as $childCompany) {
                $return['children'][$childCompany->id] = $this->addHierarchyNode($childCompany, 'companies');
            }
        }
        elseif ($type == 'teams') {
            $return['title'] =  'Team: ' . $element->title;
            $return['icon'] = 'group';
            // Add the team managers
            $teamManagers = $this->getTeamManagers($element);
            if (false != $teamManagersCount = count($teamManagers)) {
                $label = (object)['id' => 0, 'title' => 'Team Managers (' . $teamManagersCount . ')'];
                $teamManagersNode = $this->addHierarchyNode($label, 'label');
                foreach ($teamManagers as $user) {
                    $teamManagersNode['children'][$user->id] = $this->addHierarchyNode($user, 'users');
                }
                $return['children']['managers'] = $teamManagersNode;
            }
            // add the team users
            $teamUsers = $this->getTeamUsers($element->id);
            if (false != $teamUsersCount = count($teamUsers)) {
                $label = (object)['id' => 0, 'title' => 'Team Users (' . $teamUsersCount . ')'];
                $teamUsersNode = $this->addHierarchyNode($label, 'label');
                foreach ($teamUsers as $user) {
                    $teamUsersNode['children'][$user->id] = $this->addHierarchyNode($user, 'users');
                }
                $return['children']['users'] = $teamUsersNode;
            }
        }
        elseif ($type == 'users') {
            $jobRole = $element->userRole->first();
            $return['title'] =  $prefix . $element->getFullName() . ($jobRole ? ' (' . $jobRole->title . ')' : '');
            $return['icon'] = 'person';
        }
        return $return;
    }

    /**
     * Get companies by parent company id
     *
     * @param array $companyParentId
     * @return object
     * @throws mixed
     */
    function getCompaniesByParentId($companyParentId) {
        $criteria = craft()->elements->getCriteria(ElementType::Entry);
        $criteria->section = 'companies';
        $criteria->order = 'title';
        if ($companyParentId === false) {
            $criteria->companyParent = ':empty:';
        }
        else {
            $criteria->relatedTo = ['targetElement' => $companyParentId, 'field' => 'companyParent'];
        }
        return $criteria->find();
    }

    /**
     * Get companies by array of ids
     *
     * @param array $companyIds
     * @return object
     * @throws mixed
     */
    function getCompaniesByIds($companyIds) {
        $criteria = craft()->elements->getCriteria(ElementType::Entry);
        $criteria->section = 'companies';
        $criteria->order = 'title';
        $criteria->id = $companyIds;
        return $criteria->find();
    }

    /**
     * Check whether this user can manage teams or companies
     *
     * @param null $user
     * @param bool $scheme
     * @return bool
     */
    function canManage($user = null, $scheme = false) {
        if (is_null($user)) {
            $user = craft()->userSession->getUser();
        }
        if ($scheme) {
            if ($user->admin or $user->isInGroup('schemeManagers')) {
                return true;
            }
        }
        elseif ($user->admin or $user->isInGroup('schemeManagers') or $user->isInGroup('companyManagers') or $user->isInGroup('teamManagers')) {
            return true;
        }
        return false;
    }

    /**
     * Check whether this user manages the subordinate
     *
     * @param null $subordinateId
     * @param mixed $manager
     * @return bool
     * @throws \Exception
     */
    public function isManager($subordinateId = null, $manager = null) {
        if (is_null($manager)) {
            $manager = craft()->userSession->getUser();
        }
        // admins and scheme managers can manage everyone
        if ($manager->admin || $manager->isInGroup('schemeManagers')) {
            return true;
        }
        $subordinateIds = $this->getManagerSubordinateIds($manager);
        if ( ! count($subordinateIds)) {
            return false;
        }
        return $subordinateIds && in_array($subordinateId, $subordinateIds);
    }

    /**
     * @param $company
     * @param null $manager
     * @return bool
     * @throws Exception
     */
    public function isCompanyManager($company, $manager = null) {
        if (is_null($manager)) {
            $manager = craft()->userSession->getUser();
        }
        // admins and scheme managers can manage everyone
        if ($manager->admin || $manager->isInGroup('schemeManagers')) {
            return true;
        }
        $companyManagers = $this->getCompanyMangers($company);
        foreach($companyManagers as $companyManager) {
            if ($manager->id == $companyManager->id) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param $company
     * @param null $manager
     * @return bool
     * @throws Exception
     */
    public function isParentCompanyManager($company, $manager = null) {
        if ( ! $company->companyParent->count()) {
            return false;
        }
        if (is_null($manager)) {
            $manager = craft()->userSession->getUser();
        }
        return $this->isCompanyManager($company->companyParent->first(), $manager);
    }

    /**
     * Return user company (team company)
     *
     * @param null $user
     * @return BaseElementModel|null
     * @throws Exception
     */
    public function userCompany($user = null)
    {
        if (is_null($user)) {
            $user = craft()->userSession->getUser();
        }
        if ( ! $user) {
            return null;
        }
        // if user belongs to a company directly
        $userCompany = $user->userCompany->first();
        if ($userCompany) {
            return $userCompany;
        }
        // check team company
        $team = $user->userTeam->first();
        if ( ! $team) {
            return null;
        }
        $teamCompany = $team->teamCompany;
        return $teamCompany ? $teamCompany->first() : null;
    }

    /** Get all company users
     *
     * @param int $companyId
     * @param bool $count
     * @return object
     * @throws Exception
     */
    public function getCompanyUsers($companyId, $count = false) {
        $criteria = craft()->elements->getCriteria(ElementType::User);
        $criteria->relatedTo = ['targetElement' => $companyId, 'field' => 'userCompany'];
        $criteria->order = 'lastName';
        $criteria->limit = null;
        return $count ? $criteria->count() : $criteria;
    }

    /** Get all team users
     *
     * @param int $teamId
     * @param bool $count
     * @return object
     * @throws Exception
     */
    public function getTeamUsers($teamId, $count = false) {
        $criteria = craft()->elements->getCriteria(ElementType::User);
        $criteria->relatedTo = ['targetElement' => $teamId, 'field' => 'userTeam'];
        $criteria->limit = null;
        $criteria->order = 'lastName';
        return $count ? $criteria->count() : $criteria;
    }

    /** Get all company managers
     *
     * @param EntryModel $team
     * @return array
     * @throws Exception
     */
    public function getCompanyMangers(EntryModel $company, $count = false) {
        $return = [];
        if ($company->companyPrimaryManager->first()) {
            $return[] = $company->companyPrimaryManager->first();
        }
        foreach ($company->companySecondaryManagers as $manager ){
            $return[] = $manager;
        }
        return $count ? count($return) : $return;
    }

    /** Get all team managers
     *
     * @param EntryModel $team
     * @return array
     * @throws Exception
     */
    public function getTeamManagers(EntryModel $team, $count = false) {
        $return = [];
        if ($team->teamPrimaryManager->first()) {
            $return[] = $team->teamPrimaryManager->first();
        }
        foreach ($team->teamSecondaryManagers as $manager ){
            $return[] = $manager;
        }
        return $count ? count($return) : $return;
    }
    /**
     * Return all company ids (recursive)
     *
     * @param $entryId
     * @return array
     * @throws Exception
     */
    function getCompanyChildrenIds($entryId)
    {
        $return = [];
        $criteria = craft()->elements->getCriteria(ElementType::Entry);
        $criteria->section = 'companies';
        $criteria->order = 'title';
        $criteria->relatedTo = ['targetElement' => $entryId, 'field' => 'companyParent'];
        $ids = $criteria->ids();
        if (count($ids)) {
            foreach($ids as $id) {
                $return[] = $id;
                // add children companies recursively
                $childrenIds = $this->getCompanyChildrenIds($id);
                if (count($childrenIds)) {
                    $return = array_merge($return, $childrenIds);
                }
            }
        }
        return $return;
    }

    /**
     * Returns all team ids for the whole scheme
     *
     * @return array
     * @throws Exception
     */
    function getSchemeTeamIds()
    {
        $criteria = craft()->elements->getCriteria(ElementType::Entry);
        $criteria->section = 'teams';
        $criteria->order = 'title';
        return $criteria->ids();
    }

    /**
     * Returns all teams for a company
     *
     * @param $companyId
     * @return array
     * @throws Exception
     */
    function getCompanyTeams($companyId, $count = false)
    {
        $criteria = craft()->elements->getCriteria(ElementType::Entry);
        $criteria->section = 'teams';
        $criteria->relatedTo = ['targetElement' => $companyId, 'field' => 'teamCompany'];
        $criteria->order = 'title';
        return $count ? $criteria->count() : $criteria->find();
    }

    /**
     * Returns all team ids for a company
     *
     * @param $companyId
     * @return array
     * @throws Exception
     */
    function getCompanyTeamIds($companyId)
    {
        $criteria = craft()->elements->getCriteria(ElementType::Entry);
        $criteria->section = 'teams';
        $criteria->relatedTo = ['targetElement' => $companyId, 'field' => 'teamCompany'];
        $criteria->order = 'title';
        return $criteria->ids();
    }

    /**
     * Return company ids where user is a company manager
     *
     * @param $user
     * @param $type
     * @return array
     * @throws Exception
     */
    function getCompanyManagerCompanyIds(UserModel $user, $type = 'both')
    {
        if (is_null($user)) {
            $user = craft()->userSession->getUser();
        }
        $criteria = craft()->elements->getCriteria(ElementType::Entry);
        $criteria->section = 'companies';
        if ($type == 'primary')
        {
            $criteria->relatedTo = ['targetElement' => $user->id, 'field' => 'companyPrimaryManager'];

        }
        elseif ($type == 'secondary')
        {
            $criteria->relatedTo = ['targetElement' => $user->id, 'field' => 'companySecondaryManagers'];
        }
        else
        {
            $criteria->relatedTo = [
                'or',
                ['targetElement' => $user->id, 'field' => 'companyPrimaryManager'],
                ['targetElement' => $user->id, 'field' => 'companySecondaryManagers'],
            ];
        }
        $criteria->order = 'title';
        return $criteria->ids();
    }

    /**
     * Get manager companies
     *
     * @param UserModel $user
     * @param bool $includeChildren
     * @return BaseElementModel|null
     * @throws Exception
     */
    function getManagerCompanies(UserModel $user, $includeChildren = false) {
        $companyIds = $this->getCompanyManagerCompanyIds($user);
        if ($includeChildren) {
            $parentIds = $companyIds;
            foreach ($parentIds as $companyId) {
                $companyIds = array_merge($companyIds, $this->getCompanyChildrenIds($companyId));
            }
        }
        if ( ! count($companyIds)) {
            return null;
        }
        $criteria = craft()->elements->getCriteria(ElementType::Entry);
        $criteria->section = 'companies';
        $criteria->limit = null;
        $criteria->id = $companyIds;
        $criteria->fixedOrder = true;
        return $criteria->find();
    }

    /**
     * Returns team ids where user is primary or secondary manager
     *
     * @param $user
     * @return array
     * @throws Exception
     */
    function getTeamManagerTeamIds(UserModel $user)
    {
        if (is_null($user)) {
            $user = craft()->userSession->getUser();
        }
        $criteria = craft()->elements->getCriteria(ElementType::Entry);
        $criteria->section = 'teams';
        $criteria->relatedTo = ['or', ['targetElement' => $user, 'field' => 'teamPrimaryManager'], ['targetElement' => $user, 'field' => 'teamSecondaryManagers']];
        return $criteria->ids();
    }

    /**
     * Return all team ids for a manager
     *
     * @param UserModel $user
     * @param bool $includeHierarchy
     * @return array
     * @throws Exception
     */
    function getManagerTeamIds(UserModel $user, $includeHierarchy = false) {
        if (is_null($user)) {
            $user = craft()->userSession->getUser();
        }
        // add the scheme manager teams (all of them)
        if ($includeHierarchy && $user->isInGroup('schemeManagers')) {
            return $this->getSchemeTeamIds();
        }
        $return = [];
        // add the direct teamPrimaryManager and teamSecondaryManager teams
        if (FALSE != $teamManagerTeamIds = $this->getTeamManagerTeamIds($user)) {
            $return = array_merge($return, $teamManagerTeamIds);
        }
        // add the company teams
        if ($includeHierarchy && $user->isInGroup('companyManagers')) {
            $companyIds = $this->getCompanyManagerCompanyIds($user);
            foreach ($companyIds as $companyId) {
                if (FALSE != $companyManagerTeamIds = $this->getCompanyTeamIds($companyId)) {
                    $return = array_merge($return, $companyManagerTeamIds);
                }
            }
        }
        return $return;
    }

    /**
     * Return all teams for a manager
     *
     * @param UserModel $user
     * @param bool $includeCompanyTeams
     * @return array
     * @throws Exception
     */
    function getManagerTeams(UserModel $user, $includeCompanyTeams = false) {
        $teamIds = $this->getManagerTeamIds($user, $includeCompanyTeams);
        if (!count($teamIds)) {
            return null;
        }
        $criteria = craft()->elements->getCriteria(ElementType::Entry);
        $criteria->section = 'teams';
        $criteria->limit = null;
        $criteria->id = $teamIds;
        $criteria->fixedOrder = true;
        return $criteria->find();
    }

    /**
     * Get available teams for a user
     *
     * @param UserModel $user
     * @return array
     * @throws mixed
     */
    function getAvailableTeams(UserModel $user) {
        $teams = $this->getManagerTeams($user, ($user->isInGroup('companyManagers') || $user->isInGroup('schemeManagers')));
        if (empty($teams)){
            return null;
        }
        $return = [];
        foreach ($teams as $team) {
            if (craft()->lantra_licence->getTeamCompanyLicences($team)) {
                $return[] = $team;
            }
        }
        return $return;
    }

    /**
     * Returns all user ids that belong to teams (or companies) managed by a manager
     *
     * @param $user
     * @param $includeHierarchy
     * @return array
     * @throws Exception
     */
    function getManagerSubordinateIds(UserModel $user, $includeHierarchy = true) {
        if (is_null($user)) {
            $user = craft()->userSession->getUser();
        }
        $return = [];
        if ( ! $this->canManage($user)) {
            return $return;
        }
        // get companies that this user manages
        $companyIds = $this->getCompanyManagerCompanyIds($user);
        $teamIds = $this->getManagerTeamIds($user, $includeHierarchy);
        // get all users who belong to any of the manager's companies or teams
        $criteria = craft()->elements->getCriteria(ElementType::User);
        $criteria->relatedTo = ['or', ['targetElement' => $companyIds, 'field' => 'userCompany'], ['targetElement' => $teamIds, 'field' => 'userTeam']];
        return $criteria->ids();
    }

    /**
     * Return all subordinate users for a manager
     *
     * @param UserModel $user
     * @param bool $includeHierarchy
     * @return mixed
     * @throws Exception
     */
    public function getManagerSubordinates(UserModel $user, $includeHierarchy = false) {
        $subordinateIds = $this->getManagerSubordinateIds($user, $includeHierarchy);
        if ( ! count($subordinateIds)) {
            return null;
        }
        $criteria = craft()->elements->getCriteria(ElementType::User);
        $criteria->limit = null;
        $criteria->id = $subordinateIds;
        $criteria->order = 'lastName asc';
        return $criteria->find();
    }

    /**
     * Return subordinate users (as criteria for report) for a manager (similar to above)
     *
     * @param null $userId
     * @param int $limit
     * @param string $search
     * @return ElementCriteriaModel|null
     * @throws mixed
     */
    public function getManagerUsers($userId = null, $limit = 10, $search = '') {
        if ( ! is_null($userId)) {
            $manager = craft()->users->getUserById($userId);
        }
        else {
            $manager = craft()->userSession->getUser();
        }
        if ( ! $manager) {
            return null;
        }
        // build the criteria model
        $criteria = craft()->elements->getCriteria(ElementType::User);
        $criteria->limit = $limit;
        $criteria->order = 'lastName asc';
        if ($search) {
            $criteria->search = $search;
        }
        // get the subordinate ids if not admin or scheme manager
        if ( ! $manager->admin && ! $manager->isInGroup('SchemeManager')) {
            $subordinateIds = $this->getManagerSubordinateIds($manager, true);
            if ( ! count($subordinateIds)) {
                return null;
            }
            $criteria->id = $subordinateIds;
        }
        return $criteria;
    }

    /**
     * Return all the scheme managers
     *
     * @return array
     * @throws Exception
     */
    function getSchemeManagers($first = false)
    {
        $criteria = craft()->elements->getCriteria(ElementType::User);
        $criteria->groupId = 1;
        $criteria->limit = null;
        return $first ? $criteria->first() : $criteria->find();
    }

    /**
     * Get the user manager for specific level
     *
     * @return UserModel
     * @throws Exception
     */
    function getUserManagerByLevel(UserModel $user, $level = 1)
    {
        $managers = $this->getUserMangers($user, true);
        foreach($managers as $manager) {
            $managerLevel = $manager->managerLevel->value;
            if ($managerLevel >= $level) {
                return $manager;
            }
        }
        // default to first scheme manager
        return $this->getSchemeManagers(true);
    }

    /**
     * Returns all managers for a user
     *
     * @param $user
     * @param $includeHierarchy
     * @return array
     * @throws Mixed
     */
    function getUserMangers(UserModel $user, $includeHierarchy = false)
    {
        $return = [];
        $company = $user->userCompany->first();
        if ($company) {
            $primaryManager = $company->companyPrimaryManager->first();
            if($primaryManager)
            {
                $return[$primaryManager->id] = $primaryManager;
            }
            foreach ($company->companySecondaryManagers as $secondaryManager) {
                $return[$secondaryManager->id] = $secondaryManager;
            }
        }
        else {
            $team = $user->userTeam->first();
            if ( ! $team) {
                // default to scheme managers
                return $this->getSchemeManagers();
            }
            $company = $team->teamCompany->first();
            $primaryManager = $team->teamPrimaryManager->first();
            $return[$primaryManager->id] = $primaryManager;
            foreach ($team->teamSecondaryManagers as $secondaryManager) {
                $return[$secondaryManager->id] = $secondaryManager;
            }
        }
        // loop up the company parents and add primary managers
        $companyParent = $company->companyParent->first();
        if ($includeHierarchy && $companyParent) {
            while ($company != null) {
                $companyPrimaryManager = $company->companyPrimaryManager->first();
                if ($companyPrimaryManager) {
                    $return[$companyPrimaryManager->id] = $companyPrimaryManager;
                }
                $company = $company->companyParent->first();
            }
        }
        return $return;
    }

    /**
     * Check whether they can add a new user
     *
     * @param UserModel $user
     * @return bool
     * @throws Exception
     */
    function canAddUser(UserModel $user) {
        if ($user->managerReadOnly) {
            return false;
        }
        // check there are scheme licences available
        if ($user->admin or $user->isInGroup('SchemeManager')) {
           return (bool) craft()->lantra_licence->getSchemeLicences();
        }
        else {
            $availableTeams = $this->getAvailableTeams($user, $user->isInGroup('CompanyManager'));
            return (bool) $availableTeams ? count($availableTeams) : false;
        }
    }

    /**
     * Add user to Lantra individual company
     *
     * @param $user
     * @throws \Exception
     */
    public function addUserToIndividualCompany(UserModel $user) {
        // get the individualCompany
        $company = $this->getIndividualCompany();
        if ($company) {
            $user->setContentFromPost(['userCompany' => array($company->id)]);
            craft()->users->saveUser($user);
        }
    }

    /**
     * Add user to Lantra default job role
     *
     * @param $user
     * @throws \Exception
     */
    public function addUserToIndividualJobRole(UserModel $user) {
        // get the jobRole
        $jobRole = $this->getIndividualJobRole();
        // @todo error reporting?
        if($jobRole) {
            $user->setContentFromPost(['userRole' => array($jobRole->id)]);
            craft()->users->saveUser($user);
        }
    }

    /**
     * Activate individual user (add to Users and Individuals groups)
     *
     * @param $user
     * @throws \Exception
     */
    public function activateIndividualUser(UserModel $user) {
        craft()->userGroups->assignUserToGroups($user->id, array(4, 5));
    }

    /**
     * Deactivate individual user (removed from Users group)
     *
     * @param $user
     * @throws \Exception
     */
    public function deactivateIndividualUser(UserModel $user) {
        craft()->userGroups->assignUserToGroups($user->id, array(5));
    }

    /**
     * Set user expiry days
     *
     * @param $user
     * @param $days
     * @throws \Exception
     */
    public function setUserExpiryDate(UserModel $user, $days) {
        // set date in future
        $user->setContentFromPost(['userExpiryDate' => strtotime('+' . $days . ' days')]);
        craft()->users->saveUser($user);
    }

    /** Get individual company
     *
     * @return null
     */
    public function getIndividualCompany() {
        $globalsScheme = craft()->globals->getSetByHandle('globalsScheme');
        return $globalsScheme->individualCompany->first();
    }

    /** Get emails
     *
     * @param int $entryId
     * @return array
     * @throws mixed
     */
    public function getEmails($entryId) {
        $return = [];
        foreach ($this->getUsersByEntryId($entryId) as $user) {
            $return[] = $user->email;
        }
        return $return;
    }

    /** Get users for a team or company
     *
     * @param int $entryId
     * @return object
     * @throws mixed
     */
    public function getUsersByEntryId($entryId) {
        $entry = craft()->entries->getEntryById($entryId);
        if ( ! $entry || ($entry->sectionId != 3 && $entry->sectionId != 5)) {
            return (object) [];
        }
        // get company users
        if ($entry->sectionId == 3) {
            return $this->getCompanyUsers($entryId);
        }
        // get team users
        return $this->getTeamUsers($entryId);
    }

    /** Get individual job role
     *
     * @return null
     */
    public function getIndividualJobRole() {
        $globalsScheme = craft()->globals->getSetByHandle('globalsScheme');
        return $globalsScheme->individualJobRole->first();
    }

    /** Get expired users
     *
     * @return object
     * @throws Exception
     */
    public function getExpiredUsers() {
        return $this->getExpiringUsers(time());
    }

    /** Get expiring users
     *
     * @param $expiryDate
     * @return object
     * @throws Exception
     */
    public function getExpiringUsers($expiryDate) {
        $criteria = craft()->elements->getCriteria(ElementType::User);
        $criteria->userExpiryDate = '< '. $expiryDate;
        $criteria->limit = null;
        return $criteria;
    }

    /** Generate a dummy email address for user
     *
     * @param $firstName
     * @param $lastName
     * @param $handle
     * @return string
     * @throws Exception
     */
    public function generateEmail($firstName = null, $lastName = null, $handle = null) {
        $globalsScheme = craft()->globals->getSetByHandle('globalsScheme');
        if (is_null($handle)) {
            $handle = $this->generateUsername($firstName, $lastName);
        }
        $domain = $globalsScheme->schemeEmailDomain ? $globalsScheme->schemeEmailDomain : 'lantra.co.uk';
        return $handle . '@' . $domain;
    }

    /**
     * @param null $firstName
     * @param null $lastName
     * @return string
     */
    public function generateUsername($firstName = null, $lastName = null) {
        if ($firstName && $lastName) {
            $handle =  preg_replace('/\s+/', '', strtolower(trim($firstName) . '.' . trim($lastName)));
            return $handle . '.' . mt_rand(1000, 9999);
        }
        return 'user.'. mt_rand(1000, 9999);
    }

    /**
     * @param $companyIds
     * @param $user
     * @param $type
     * @throws Exception
     */
    public function setManager($companyIds, $user, $type = 'primary')
    {
        // remove from existing
        foreach($this->getCompanyManagerCompanyIds($user, $type) as $companyId)
        {
            if ( ! in_array($companyId, $companyIds))
            {
                $company = craft()->entries->getEntryById($companyId);
                if ($type == 'primary')
                {
                    $company->setContentFromPost(['companyPrimaryManager' => []]);
                    craft()->elements->saveElement($company);
                }
                else
                {
                    $secondaryManagerIds = $company->companySecondaryManagers->ids();
                    // remove userId from array
                    if (($key = array_search($user->id, $secondaryManagerIds)) !== false) {
                        unset($secondaryManagerIds[$key]);
                    }
                    $company->setContentFromPost(['companySecondaryManagers' => $secondaryManagerIds]);
                    craft()->elements->saveElement($company);
                }
            }
        }
        // add to new
        foreach($companyIds as $companyId) {
            $company = craft()->entries->getEntryById($companyId);
            $primaryManagerId = $company->companyPrimaryManager->total() ? $company->companyPrimaryManager->first()->id : null;
            $secondaryManagerIds = $company->companySecondaryManagers->total() ? $company->companySecondaryManagers->ids() : [];
            if ($type == 'primary' && $user->id != $primaryManagerId) {
                $company->setContentFromPost(['companyPrimaryManager' => [$user->id]]);
                craft()->elements->saveElement($company);
            }
            if ($type == 'secondary' && ! in_array($user->id, $secondaryManagerIds))
            {
                $secondaryManagerIds[] = $user->id;
                $company->setContentFromPost(['companySecondaryManagers' => $secondaryManagerIds]);
                craft()->elements->saveElement($company);
            }
        }
    }
}
