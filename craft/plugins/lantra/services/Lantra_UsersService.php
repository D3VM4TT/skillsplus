<?php
namespace Craft;

class Lantra_UsersService extends BaseApplicationComponent
{
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
     * @return object
     * @throws Exception
     */
    public function getCompanyUsers($companyId) {
        $criteria = craft()->elements->getCriteria(ElementType::User);
        $criteria->relatedTo = ['targetElement' => $companyId, 'field' => 'userCompany'];
        $criteria->limit = null;
        return $criteria;
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
        $individualTeam = $this->getIndividualTeam();
        if ($individualTeam) {
            $criteria->id = 'not ' . $individualTeam->id;
        }
        return $criteria->ids();
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
     * @return array
     * @throws Exception
     */
    function getCompanyManagerCompanyIds(UserModel $user)
    {
        if (is_null($user)) {
            $user = craft()->userSession->getUser();
        }
        $criteria = craft()->elements->getCriteria(ElementType::Entry);
        $criteria->section = 'companies';
        $criteria->relatedTo = ['targetElement' => $user->id, 'field' => 'companyManager'];
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
        $companyIds = craft()->lantra_users->getCompanyManagerCompanyIds($user, $includeChildren);
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
        // get the subordinate ids
        $subordinateIds = $this->getManagerSubordinateIds($manager, true);
        if ( ! count($subordinateIds)) {
            return null;
        }
        // build the criteria model
        $criteria = craft()->elements->getCriteria(ElementType::User);
        $criteria->limit = $limit;
        $criteria->id = $subordinateIds;
        $criteria->order = 'lastName asc';
        if ($search) {
            $criteria->search = $search;
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
            $manager = $company->companyManager->first();
            $return[$manager->id] = $manager;
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
        // loop up the company parents and add managers
        $companyParent = $company->companyParent->first();
        if ($includeHierarchy && $companyParent) {
            while ($company != null) {
                $companyManager = $company->companyManager->first();
                if ($companyManager) {
                    $return[$companyManager->id] = $companyManager;
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
     * Add user to Lantra individual team
     *
     * @param $user
     * @throws \Exception
     */
    public function addUserToIndividualTeam(UserModel $user) {
        // get the individualTeam
        $team = $this->getIndividualTeam();
        // @todo error reporting?
        if($team) {
            $user->setContentFromPost(['userTeam' => array($team->id)]);
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

    /** Get individual team
     *
     * @return null
     */
    public function getIndividualTeam() {
        $globalsScheme = craft()->globals->getSetByHandle('globalsScheme');
        return $globalsScheme->individualTeam->first();
    }

    /** Get individual company
     *
     * @return null
     */
    public function getIndividualCompany() {
        $team = $this->getIndividualTeam();
        return $team ? $team->teamCompany->first() : null;
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
}
