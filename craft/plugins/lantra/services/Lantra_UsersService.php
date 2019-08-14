<?php
namespace Craft;

class Lantra_UsersService extends BaseApplicationComponent
{
    private $nodeId = 0;
    private $hierarchyFilter = [];

    /**
     * @param $search
     * @param string $userStatus
     * @return array
     * @throws \CException
     */
    public function userCriteria($search = '', $userStatus = 'all', $companyId = false, $limit = 25, $order = 'username') {
        $user = craft()->userSession->getUser();
        $criteria = craft()->elements->getCriteria(ElementType::User);
        $excludeIds = [$user->id];
        if ($userStatus == 'orphaned') {
            $criteria->userCompany = ':empty:';
        }
        elseif ($userStatus == 'suspended') {
            $criteria->status = 'suspended';
        }
        else {
            $criteria->status = null;
        }
        if ($search) {
            $searchIds = $this->searchUserIds(trim($search));
            if (empty($searchIds)) {
                return null;
            }
            $criteria->id = 'or, ' . implode(',', array_diff($searchIds, $excludeIds));
        }
        else {
            $criteria->id = 'and, not ' . implode(', not ', $excludeIds);
        }

        $criteria->admin = 'not 1';
        $criteria->limit = $limit;
        $criteria->order = $order;

        if ($user->isInGroup('companyManagers')) {
            $criteria->group = ['users', 'teamManagers'];
            $criteria->relatedTo = ['targetElement' => $this->getManagerCompanies($user, true, 'companyLabel', true), 'field' => 'userCompany'];
        }
        elseif ($user->isInGroup('teamManagers')) {
            $criteria->group = ['users', 'teamManagers'];
            $criteria->relatedTo = ['targetElement' => $this->getManagerTeams($user, false, true), 'field' => 'userCompany'];
        }
        if ($companyId) {
            $criteria->relatedTo = ['targetElement' => [$companyId], 'field' => 'userCompany'];
        }

        return $criteria;
    }

    /** more efficient way to search users */
    private function searchUserIds($search = '') {
        if (intval($search)) {
            $mysql = 'SELECT u.id FROM {{users}} u 
                JOIN {{content}} AS c ON u.id = c.elementId
                WHERE u.id = "' . $search . '"
                OR c.field_legacyId = "' . $search . '"';
        }
        else {
            $mysql = 'SELECT u.id FROM {{users}} u 
                JOIN {{relations}} AS r ON r.sourceId = u.id
                JOIN {{content}} AS c ON u.id = c.elementId
                JOIN {{content}} AS rc ON r.targetId = rc.elementId
                WHERE u.username LIKE "%' . $search . '%"
                OR u.firstName LIKE "%' . $search . '%"
                OR u.lastName LIKE "%' . $search . '%"
                OR rc.title LIKE "%' . $search . '%"
                OR UPPER(CONCAT_WS(" ", u.firstName, u.lastName)) LIKE UPPER("%' . $search . '%")
                OR c.field_userCompanyName LIKE "%' . $search . '%"';
        }

        $result = craft()->db->createCommand($mysql)->query();
        $ids = [];
        foreach ($result as $row) {
            $ids [] = $row['id'];
        }
        return $ids;
    }

    /**
     * @param UserModel|null $user
     * @return array
     */
    function getUserUnitIds(UserModel $user = null) {
        if (is_null($user)) {
            $user = craft()->userSession->getUser();
        }
        $userUnits = craft()->lantra_results->userUnits($user);
        return array_keys($userUnits);
    }

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
     * @param bool $includeHierarchy
     * @return bool
     * @throws \Exception
     */
    public function isManager($subordinateId = null, $manager = null, $includeHierarchy = true) {
        if (is_null($manager)) {
            $manager = craft()->userSession->getUser();
        }
        // admins and scheme managers can manage everyone
        if ($manager->admin || $manager->isInGroup('schemeManagers')) {
            return true;
        }
        $subordinateIds = $this->getManagerSubordinateIds($manager, $includeHierarchy);
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

    /**
     * @param $entryId
     * @param $type
     * @return int
     * @throws Exception
     */
    public function countSuspendedUsers($entryId, $type = 'company') {
        $criteria = craft()->elements->getCriteria(ElementType::User);
        $fieldName = 'user' . ucwords($type);
        $criteria->relatedTo = ['targetElement' => $entryId, 'field' => $fieldName];
        $criteria->order = 'lastName';
        $criteria->limit = null;
        $criteria->status = 'suspended';
        return $criteria->count();
    }

    /**
     * @param $companyId
     * @param bool $count
     * @return ElementCriteriaModel|int|object
     * @throws Exception
     * @throws \CException
     */
    public function getCompanyMembers($companyId, $count = false) {
        $company = craft()->entries->getEntryById($companyId);
        $companyManagerIds = $company ? $this->getCompanyManagerIds($company) : [];
        if (! count($companyManagerIds)) {
            return $this->getCompanyUsers($companyId, $count);
        }
        $criteria = craft()->elements->getCriteria(ElementType::User);
        $criteria->relatedTo = ['targetElement' => $companyId, 'field' => 'userCompany'];
        $criteria->order = 'lastName';
        $criteria->limit = null;
        $criteria->id = 'and, not ' . implode(', not ', $companyManagerIds);
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


    /**
     * @param $teamId
     * @param bool $count
     * @return ElementCriteriaModel|int|object
     * @throws Exception
     * @throws \CException
     */
    public function getTeamMembers($teamId, $count = false) {
        $team = craft()->entries->getEntryById($teamId);
        $teamManagerIds = $team ? $this->getTeamManagerIds($team) : [];
        if (! count($teamManagerIds)) {
            return $this->getTeamUsers($teamId, $count);
        }
        $criteria = craft()->elements->getCriteria(ElementType::User);
        $criteria->relatedTo = ['targetElement' => $teamId, 'field' => 'userTeam'];
        $criteria->order = 'lastName';
        $criteria->limit = null;
        $criteria->id = 'and not ' . implode(', not ', $teamManagerIds);
        return $count ? $criteria->count() : $criteria;
    }

    /**
     * @param EntryModel $entry
     * @param bool $count
     * @return array|int
     */
    public function getManagers(EntryModel $entry, $count = false) {
        $return = [];
        $primaryManagerIds = [];
        foreach ($entry->companyPrimaryManagers as $manager){
            $return[] = $manager;
            $primaryManagerIds[] = $manager->id;
        }
        foreach ($entry->companySecondaryManagers as $manager ){
            // avoid duplicates from primary
            if (! in_array($manager->id, $primaryManagerIds)) {
                $return[] = $manager;
            }
        }
        return $count ? count($return) : $return;
    }

    /**
     * @param EntryModel $entry
     * @return array
     * @throws \CException
     */
    public function getManagerIds(EntryModel $entry) {
        $ids = [];
        foreach($this->getCompanyManagers($entry) as $manager) {
            $ids[] = $manager->id;
        }
        return $ids;
    }

    /**
     * @param EntryModel $company
     * @param bool $count
     * @return mixed
     * @throws \CException
     */
    public function getCompanyManagers(EntryModel $company, $count = false) {
        return $this->getManagers($company, $count);
    }

    /**
     * @param EntryModel $company
     * @return array
     * @throws \CException
     */
    public function getCompanyManagerIds(EntryModel $company) {
        return $this->getManagerIds($company);
    }

    /**
     * @param EntryModel $team
     * @param bool $count
     * @return mixed
     * @throws \CException
     */
    public function getTeamManagers(EntryModel $team, $count = false) {
        return $this->getManagers($team, $count);
    }

    /**
     * @param EntryModel $team
     * @return array
     * @throws \CException
     */
    public function getTeamManagerIds(EntryModel $team) {
        return $this->getManagerIds($team);
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
            $criteria->relatedTo = ['targetElement' => $user->id, 'field' => 'companyPrimaryManagers'];

        }
        elseif ($type == 'secondary')
        {
            $criteria->relatedTo = ['targetElement' => $user->id, 'field' => 'companySecondaryManagers'];
        }
        else
        {
            $criteria->relatedTo = [
                'or',
                ['targetElement' => $user->id, 'field' => 'companyPrimaryManagers'],
                ['targetElement' => $user->id, 'field' => 'companySecondaryManagers'],
            ];
        }
        $criteria->order = 'title';
        return $criteria->ids();
    }

    /**
     * @param UserModel $user
     * @return string
     * @throws Exception
     */
    public function getManagerFirstCompany(UserModel $user) {
        $companies = $this->getManagerCompanies($user);
        return $companies ? $companies[0] : null;
    }

    /**
     * @param $company
     * @param $user
     * @param string $type
     * @throws \Exception
     */
    public function removeCompanyManager($company, $user, $type = 'both') {

        $primaryManagerIds = $company->companyPrimaryManagers->ids();
        $secondaryManagerIds = $company->companySecondaryManagers->ids();

        if (($type == 'both' || $type == 'primary') && in_array($user->id, $primaryManagerIds)) {
            $key = array_search($user->id, $primaryManagerIds);
            unset($primaryManagerIds[$key]);
        }
        if (($type == 'both' || $type == 'secondary') && in_array($user->id, $secondaryManagerIds)) {
            $key = array_search($user->id, $secondaryManagerIds);
            unset($secondaryManagerIds[$key]);
        }

        $company->setContentFromPost ([
            'companyPrimaryManagers' => $primaryManagerIds,
            'companySecondaryManagers' => $secondaryManagerIds
        ]);

        craft()->entries->saveEntry($company);
    }

    /**
     * Get manager companies
     *
     * @param UserModel $user
     * @param bool $includeChildren
     * @param string $order
     * @param bool $ids
     * @return BaseElementModel|null
     * @throws Exception
     */
    function getManagerCompanies(UserModel $user, $includeChildren = false, $order = 'companyLabel', $ids = false) {
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
        $criteria->order = $order;
        return $ids ? $criteria->ids() : $criteria->find();
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
    function getManagerTeams(UserModel $user, $includeCompanyTeams = false, $ids = false) {
        $teamIds = $this->getManagerTeamIds($user, $includeCompanyTeams);
        if (!count($teamIds)) {
            return null;
        }
        $criteria = craft()->elements->getCriteria(ElementType::Entry);
        $criteria->section = 'teams';
        $criteria->limit = null;
        $criteria->id = $teamIds;
        $criteria->fixedOrder = true;
        return $ids ? $criteria->ids() : $criteria->find();
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
        if ($includeHierarchy) {
            $companyChildrenIds = [];
            foreach($companyIds as $companyId) {
                $companyChildrenIds = array_merge($companyChildrenIds, $this->getCompanyChildrenIds($companyId));
            }
            $companyIds = array_merge($companyIds, $companyChildrenIds);
        }
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
     * @param string $relatedTo
     * @return ElementCriteriaModel|null
     * @throws mixed
     */
    public function getManagerUsers($userId = null, $limit = 10, $search = '', $relatedTo = null) {
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
        $criteria->admin = 'not 1';
        if ($search) {
            $criteria->search = $search;
        }
        if ($relatedTo) {
            $criteria->relatedTo = $relatedTo;
        }
        // get the subordinate ids if not admin or scheme manager
        if ( ! $manager->admin && ! $manager->isInGroup('schemeManagers')) {
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
            foreach ($company->companyPrimaryManagers as $primaryManager) {
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
                foreach ($company->companyPrimaryManagers as $primaryManager) {
                    $return[$primaryManager->id] = $primaryManager;
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
        return craft()->lantra_settings->getSetting('individualCompany');
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
            return $this->getCompanyUsers($entryId, false, true);
        }
        // get team users
        return $this->getTeamUsers($entryId, false, true);
    }

    /** Get individual job role
     *
     * @return null
     */
    public function getIndividualJobRole() {
        return craft()->lantra_settings->getSetting('individualJobRole');
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
        $domain = craft()->lantra_settings->getConfig('schemeEmailDomain', 'lantra.co.uk');
        $email = mt_rand(1000000, 9999999) . '@' . $domain;
        return craft()->users->getUserByUsernameOrEmail($email) ? $this->generateEmail($firstName, $lastName, $handle) : $email;
    }

    /**
     * @param null $firstName
     * @param null $lastName
     * @return string
     */
    public function generateUsername($username, $firstName = null, $lastName = null) {
        if ($username) {
            // remove all characters except A-Z, a-z, 0-9, dots, @, hyphens and spaces, replace spaces with dots
            $username = preg_replace('/\s+/', '.', preg_replace('/[^A-Za-z0-9@\. -]/', '', strtolower($username)));
            if ( ! $username || craft()->users->getUserByUsernameOrEmail($username)) {
                $username = $username . mt_rand(100000, 999999);
            }
        }
        elseif ($firstName && $lastName) {
            $handle =  preg_replace('/\s+/', '', strtolower(trim($firstName) . '.' . trim($lastName)));
            $username = $handle . '.' . mt_rand(100000, 999999);
        }

        if ( ! $username) {
            $username = 'user.'. mt_rand(100000, 999999);
        }

        return $username;
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
                    $primaryManagerIds = $company->companyPrimaryManagers->ids();
                    // remove userId from array
                    if (($key = array_search($user->id, $primaryManagerIds)) !== false) {
                        unset($primaryManagerIds[$key]);
                    }
                    $company->setContentFromPost(['companyPrimaryManagers' => $primaryManagerIds]);
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
            $primaryManagerIds = $company->companyPrimaryManagers->total() ? $company->companyPrimaryManagers->ids() : [];
            $secondaryManagerIds = $company->companySecondaryManagers->total() ? $company->companySecondaryManagers->ids() : [];
            if ($type == 'primary' && ! in_array($user->id, $primaryManagerIds))
            {
                $primaryManagerIds[] = $user->id;
                $company->setContentFromPost(['companyPrimaryManagers' => $primaryManagerIds]);
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
