<?php
/**
 * Lantra Skills Plus for Craft CMS 3.x
 *
 * @link      https:##coffeebean.design
 * @copyright Copyright (c) 2020 Coffee Bean Design
 */

namespace lantra\sp\services;

use Craft;
use craft\base\Component;
use craft\elements\User;
use craft\elements\Entry;
use craft\events\ModelEvent;
use craft\events\UserAssignGroupEvent;
use craft\events\UserEvent;
use craft\elements\db\UserQuery;
use craft\helpers\DateTimeHelper;

use craft\elements\MatrixBlock;
use lantra\sp\Plugin as Lantra;
use lantra\sp\helpers\LantraHelper;

use verbb\supertable\elements\SuperTableBlockElement;
use verbb\supertable\services\SuperTableService;

use DateTime;
use yii\db\Query;

class Users extends Component
{

    /**
     * @param ModelEvent $event
     * @param User $user
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     * @throws \yii\base\NotSupportedException
     * @throws \yii\db\Exception
     */
    public function onSaveUser(ModelEvent $event, User $user)
    {
        Lantra::$app->results->saveUserResultCache($user->id);
    }

    /**
     * @param ModelEvent $event
     * @param User $user
     */
    public function onAssignUser(UserAssignGroupEvent $event, User $user)
    {
        if (Craft::$app->request->getParam('taskbook')) {
            $group = Craft::$app->userGroups->getGroupByHandle('usersTaskbookPending');
            Craft::$app->getUsers()->assignUserToGroups($user->id, [$group->id]);
        }

        if (Craft::$app->request->getParam('membership')) {
            $group = Craft::$app->userGroups->getGroupByHandle('usersMembershipPending');
            Craft::$app->getUsers()->assignUserToGroups($user->id, [$group->id]);
        }
    }

    /**
     * @param ModelEvent $event
     * @param User $user
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     */
    public function onActivateUser(UserEvent $event, User $user)
    {
        ## skip if cli (i.e. install)
        if (Craft::$app->request->isConsoleRequest) {
            return;
        }
    }

    /**
     * @param ModelEvent $event
     * @param User $user
     */
    public function onBeforeSaveUser(ModelEvent $event, User $user)
    {
        ## skip if cli (i.e. install)
        if (Craft::$app->request->isConsoleRequest) {
            return;
        }

        ## automatically set userType for reports
        ## $user->setFieldValue('userType', Lantra::$app->users->canManage($user) ? 'manager' : 'member');

        $userStartDate = $user->userStartDate ? $user->userStartDate->getTimestamp() : false;
        $userExpiryDate = $user->userExpiryDate ? $user->userStartDate->getTimestamp() : false;

        ## validate dates
        if ($userStartDate && $userExpiryDate && $userStartDate > $userExpiryDate) {
            $user->addError('userStartDate', 'Start date cannot be later than expiry date.');
            $event->isValid = false;
        }
        if ($userExpiryDate && $userExpiryDate > $userExpiryDate) {
            $user->addError('userExpiryDate', 'Expiry date cannot be later than expiry date.');
            $event->isValid = false;
        }

        ## set licence source
        $licenceSource = 'None';
        $lantraLicences = !LantraHelper::setting('lantraDisableLicences');
        if ($lantraLicences && $event->isNew && !$user->admin) {
            ## assign company licence if joining a team
            if ($user->userCompany->count() || $user->userTeam->count()) {
                $companyEntry = Lantra::$app->users->userCompany($user);
                if (false == Lantra::$app->licences->assignCompanyLicence($user, $companyEntry)) {
                    $user->addError('userCompany', 'There are insufficient company licences.');
                    $event->isValid = false;
                } else {
                    $licenceSource = 'Company #' . $companyEntry->id;
                }
            }
            ## assign scheme licence
            elseif (false == Lantra::$app->licences->assignSchemeLicence()) {
                $event->isValid = false;
                $user->addError('userCompany', 'There are insufficient scheme licences.');
            } else {
                $licenceSource = 'Scheme';
            }
        }
        $user->userLicenceSource = $licenceSource;

        ## set company from register form
        if (null != $companyId = Craft::$app->request->getParam('registerCompany')) {
            if (null != $companyEntry = Entry::findOne($companyId)) {
                $user->setFieldValue('userCompany', [$companyId]);
            }
        }
    }

    /**
     * @param $event
     */
    public function onBeforeDeleteUser($event)
    {
        $user = Craft::$app->getUser();
        if (!Craft::$app->request->isCpRequest && !$user->isInGroup('schemeManagers') && !$user->admin){
            $event->performAction = false;
        }
    }

    private $nodeId = 0;
    private $hierarchyFilter = [];

    /**
     * @param string $search
     * @param string $userStatus
     * @param bool $companyId
     * @param int $limit
     * @param string $order
     * @return \craft\elements\db\ElementQueryInterface|\craft\elements\db\UserQuery
     */
    public function userCriteria($search = '', $userStatus = 'active', $companyId = false, $limit = 25, $order = 'username')
    {
        $user = Craft::$app->getUser()->getIdentity();
        $criteria = User::find();
        $excludeIds = [$user->id];
        if ($userStatus == 'orphaned') {
            $criteria->userCompany = ':empty:';
        } elseif ($userStatus == 'suspended') {
            $criteria->status = 'suspended';
        } elseif ($userStatus == 'all') {
            $criteria->status = null;
        }
        if ($search) {
            $searchIds = $this->searchUserIds(trim($search));
            if (empty($searchIds)) {
                return null;
            }
            $criteria->id = 'or, ' . implode(',', array_diff($searchIds, $excludeIds));
        } else {
            $criteria->id = 'and, not ' . implode(', not ', $excludeIds);
        }

        $criteria->admin = false;
        $criteria->limit = $limit;
        $criteria->order = $order;

        if ($user->isInGroup('companyManagers')) {
            $criteria->group = ['users', 'teamManagers'];
            $criteria->relatedTo = ['targetElement' => $this->getManagerCompanies($user, true, 'companyLabel', true), 'field' => 'userCompany'];
        } elseif ($user->isInGroup('teamManagers')) {
            $criteria->group = ['users', 'teamManagers'];
            $criteria->relatedTo = ['targetElement' => $this->getManagerTeams($user, false, true), 'field' => 'userCompany'];
        }
        else {
            $criteria->group = ['users', 'companyManagers', 'teamManagers'];
        }
        if ($companyId) {
            $criteria->relatedTo = ['targetElement' => [$companyId], 'field' => 'userCompany'];
        }
        return $criteria;
    }

    /** more efficient way to search users */
    private function searchUserIds($search = '')
    {
        $query = (new Query())
            ->select('u.id')
            ->from('{{%users}} u')
            ->leftJoin('{{%content}} AS c', 'c.elementId = u.id');

        if (intval($search)) {
            $where = [
                'or',
                'u.id = "' . $search . '"',
                'c.field_legacyId = "' . $search . '"'
            ];
            $query->where($where);
        } else {
            $where = [
                'or',
                'u.username LIKE "%' . $search . '%"',
                'rc.title LIKE "%' . $search . '%"',
                'rc.title LIKE "%' . $search . '%"',
                'rc.field_companyLabel LIKE "%' . $search . '%"',
                'c.field_userCompanyName LIKE "%' . $search . '%"',
                'UPPER(CONCAT_WS(" ", u.firstName, u.lastName)) LIKE UPPER("%' . $search . '%")'
            ];
            $query->leftJoin('{{%relations}} AS r', 'r.sourceId = u.id')
                ->leftJoin('{{%content}} AS rc', 'rc.elementId = r.targetId')
                ->where($where);
        }

        return $query->column();
    }

    /**
     * @param User|null $user
     * @return array
     */
    function getUserUnitIds(User $user = null)
    {
        if (is_null($user)) {
            $user = Craft::$app->getUser();
        }
        $userUnits = Lantra::$app->results->userUnits($user);
        return array_keys($userUnits);
    }

    /**
     * Get manager hierarchy [replaced by Lantra_StructureService.php getHierarchy()]
     *
     * @param $user
     * @return array
     * @throws mixed
     */
    function getManagerHierarchy(User $user = null)
    {
        $this->nodeId = 0;
        if (is_null($user)) {
            $user = Craft::$app->getUser();
        }
        $return = [];
        $array = [];
        $managerTeams = $this->getManagerTeams($user);
        $managerCompanies = $this->getManagerCompanies($user);
        $type = 'companies';
        ## filter individuals company id
        $individualCompany = $this->getIndividualCompany();
        if ($individualCompany) {
            $this->hierarchyFilter[] = $individualCompany->id;
        }
        ## admins and scheme managers start with all top level parents
        if ($user->admin or $user->isInGroup('schemeManagers')) {
            $array = $this->getCompaniesByParentId(false);
        } ## does this user manage companies?
        elseif (count($managerCompanies)) {
            $array = $managerCompanies;
        } ## does this user manage teams?
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
            'nodeId' => $this->nodeId,
            'nodeType' => $type,
            'elementId' => $element->id,
            'managers' => [],
            'children' => [],
            'icon' => 'company'
        ];
        if ($type == 'label') {
            $return['title'] = $element->title;
        }
        if ($type == 'companies') {
            $return['title'] = $element->title;
            $return['icon'] = 'group';
            ## add the company managers
            $companyManagers = $this->getCompanyMangers($element);
            if (false != $companyManagerCount = count($companyManagers)) {
                $label = (object)['id' => 0, 'title' => 'Company Managers (' . $companyManagerCount . ')'];
                $companyManagersNode = $this->addHierarchyNode($label, 'label');
                foreach ($companyManagers as $user) {
                    $companyManagersNode['children'][$user->id] = $this->addHierarchyNode($user, 'users');
                }
                $return['children']['managers'] = $companyManagersNode;
            }
            ## add the company users
            $companyUsers = $this->getCompanyUsers($element->id);
            if (false != $companyUsersCount = count($companyUsers)) {
                $label = (object)['id' => 0, 'title' => 'Company Users (' . $companyUsersCount . ')'];
                $companyUsersNode = $this->addHierarchyNode($label, 'label');
                foreach ($companyUsers as $user) {
                    $companyUsersNode['children'][$user->id] = $this->addHierarchyNode($user, 'users');
                }
                $return['children']['users'] = $companyUsersNode;
            }
            ## add the company teams
            $companyTeams = $this->getCompanyTeams($element->id);
            foreach ($companyTeams as $team) {
                $return['children'][$team->id] = $this->addHierarchyNode($team, 'teams');
            }
            ## add the child companies
            $childCompanies = $this->getCompaniesByParentId($element->id);
            foreach ($childCompanies as $childCompany) {
                $return['children'][$childCompany->id] = $this->addHierarchyNode($childCompany, 'companies');
            }
        } elseif ($type == 'teams') {
            $return['title'] = 'Team: ' . $element->title;
            $return['icon'] = 'group';
            ## Add the team managers
            $teamManagers = $this->getTeamManagers($element);
            if (false != $teamManagersCount = count($teamManagers)) {
                $label = (object)['id' => 0, 'title' => 'Team Managers (' . $teamManagersCount . ')'];
                $teamManagersNode = $this->addHierarchyNode($label, 'label');
                foreach ($teamManagers as $user) {
                    $teamManagersNode['children'][$user->id] = $this->addHierarchyNode($user, 'users');
                }
                $return['children']['managers'] = $teamManagersNode;
            }
            ## add the team users
            $teamUsers = $this->getTeamUsers($element->id);
            if (false != $teamUsersCount = count($teamUsers)) {
                $label = (object)['id' => 0, 'title' => 'Team Users (' . $teamUsersCount . ')'];
                $teamUsersNode = $this->addHierarchyNode($label, 'label');
                foreach ($teamUsers as $user) {
                    $teamUsersNode['children'][$user->id] = $this->addHierarchyNode($user, 'users');
                }
                $return['children']['users'] = $teamUsersNode;
            }
        } elseif ($type == 'users') {
            $jobRole = $element->userRole->one();
            $return['title'] = $prefix . $element->getFullName() . ($jobRole ? ' (' . $jobRole->title . ')' : '');
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
    function getCompaniesByParentId($companyParentId)
    {
        $criteria = Entry::find();
        $criteria->section = 'companies';
        $criteria->order = 'title';
        if ($companyParentId === false) {
            $criteria->companyParent = ':empty:';
        } else {
            $criteria->relatedTo = ['targetElement' => $companyParentId, 'field' => 'companyParent'];
        }
        return $criteria->all();
    }

    /**
     * Get companies by array of ids
     *
     * @param array $companyIds
     * @return object
     * @throws mixed
     */
    function getCompaniesByIds($companyIds)
    {
        $criteria = Entry::find();
        $criteria->section = 'companies';
        $criteria->order = 'title';
        $criteria->id = $companyIds;
        return $criteria->all();
    }

    /**
     * Check whether this user can manage teams or companies
     *
     * @param null $user
     * @param bool $scheme
     * @return bool
     */
    function canManage($user = null, $scheme = false)
    {
        if (is_null($user)) {
            $user = Craft::$app->getUser();
        }
        if ($scheme) {
            if ($user->admin or $user->isInGroup('schemeManagers')) {
                return true;
            }
        } elseif ($user->admin or $user->isInGroup('schemeManagers') or $user->isInGroup('companyManagers') or $user->isInGroup('teamManagers')) {
            return true;
        }
        return false;
    }

    /**
     * @param null $user
     * @return bool
     */
    function isLantraAdmin($user = null)
    {
        if (is_null($user)) {
            $user = Craft::$app->getUser();
        }
        return $user->admin or $user->isInGroup('schemeManagers');
    }

    /**
     * Check whether this user has dashboard
     *
     * @param null $user
     * @return bool
     */
    function hasDashboard($user = null)
    {
        $dashboard = false;
        if (is_null($user)) {
            $user = Craft::$app->getUser();
        }
        foreach($user->userRole as $role) {
            if ($role->isDashboard) {
                $dashboard = true;
                break;
            }
        }
        return $dashboard;
    }

    /**
     * Check whether this user is external
     *
     * @param null $user
     * @return bool
     */
    function isExternal($user = null)
    {
        $external = false;
        if (is_null($user)) {
            $user = Craft::$app->getUser();
        }
        foreach($user->userRole as $role) {
            if ($role->isExternal) {
                $external = true;
                break;
            }
        }
        return $external;
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
    public function isManager($subordinateId = null, $manager = null, $includeHierarchy = true)
    {
        if (is_null($manager) && false == $manager = Craft::$app->getUser()->getIdentity()) {
            return false;
        }
        ## admins and scheme managers can manage everyone
        if ($manager->admin || $manager->isInGroup('schemeManagers')) {
            return true;
        }
        ## check whether can assess or review
        if (Lantra::$app->packages->isPackageManager($subordinateId, $manager)) {
            return true;
        }
        $subordinateIds = $this->getManagerSubordinateIds($manager, $includeHierarchy);
        if (!count($subordinateIds)) {
            return false;
        }
        return $subordinateIds && in_array($subordinateId, $subordinateIds);
    }

    /**
     * @param $company
     * @param null $manager
     * @return bool
     * @throws \CException
     */
    public function isCompanyManager($company, $manager = null)
    {
        if (is_null($manager)) {
            $manager = Craft::$app->getUser();
        }
        ## admins and scheme managers can manage everyone
        if ($manager->admin || $manager->isInGroup('schemeManagers')) {
            return true;
        }
        $companyManagers = $this->getCompanyManagers($company);
        foreach ($companyManagers as $companyManager) {
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
     * @throws \CException
     */
    public function isParentCompanyManager($company, $manager = null)
    {
        if (!$company->companyParent->count()) {
            return false;
        }
        if (is_null($manager)) {
            $manager = Craft::$app->getUser();
        }
        return $this->isCompanyManager($company->companyParent->one(), $manager);
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
            $user = Craft::$app->getUser();
        }
        if (!$user) {
            return null;
        }
        ## if user belongs to a company directly
        $userCompany = $user->userCompany->one();
        if ($userCompany) {
            return $userCompany;
        }
        ## check team company
        $team = $user->userTeam->one();
        if (!$team) {
            return null;
        }
        $teamCompany = $team->teamCompany;
        return $teamCompany ? $teamCompany->one() : null;
    }

    /** Get all company users
     *
     * @param int $companyId
     * @param bool $count
     * @return object
     * @throws Exception
     */
    public function getCompanyUsers($companyId, $count = false)
    {
        $criteria = User::find();
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
    public function countSuspendedUsers($entryId, $type = 'company')
    {
        $criteria = User::find();
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
    public function getCompanyMembers($companyId, $count = false)
    {
        $company = Craft::$app->entries->getEntryById($companyId);
        $companyManagerIds = $company ? $this->getCompanyManagerIds($company) : [];
        if (!count($companyManagerIds)) {
            return $this->getCompanyUsers($companyId, $count);
        }
        $criteria = User::find();
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
    public function getTeamUsers($teamId, $count = false)
    {
        $criteria = User::find();
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
    public function getTeamMembers($teamId, $count = false)
    {
        $team = Craft::$app->entries->getEntryById($teamId);
        $teamManagerIds = $team ? $this->getTeamManagerIds($team) : [];
        if (!count($teamManagerIds)) {
            return $this->getTeamUsers($teamId, $count);
        }
        $criteria = User::find();
        $criteria->relatedTo = ['targetElement' => $teamId, 'field' => 'userTeam'];
        $criteria->order = 'lastName';
        $criteria->limit = null;
        $criteria->id = 'and not ' . implode(', not ', $teamManagerIds);
        return $count ? $criteria->count() : $criteria;
    }

    /**
     * @param Entry $entry
     * @param bool $count
     * @return array|int
     */
    public function getManagers(Entry $entry, $count = false)
    {
        $return = [];
        $primaryManagerIds = [];
        $primaryManagers = $entry->companyPrimaryManagers->orderBy('lastName')->all();
        foreach ($primaryManagers as $manager) {
            $return[] = $manager;
            $primaryManagerIds[] = $manager->id;
        }
        $secondaryManagers = $entry->companySecondaryManagers->orderBy('lastName')->all();
        foreach ($secondaryManagers as $manager) {
            ## avoid duplicates from primary
            if (!in_array($manager->id, $primaryManagerIds)) {
                $return[] = $manager;
            }
        }
        return $count ? count($return) : $return;
    }

    /**
     * @param Entry $entry
     * @return array
     * @throws \CException
     */
    public function getManagerIds(Entry $entry)
    {
        $ids = [];
        foreach ($this->getCompanyManagers($entry) as $manager) {
            $ids[] = $manager->id;
        }
        return $ids;
    }

    /**
     * @param array $companyIds
     * @return array
     * @throws Exception
     * @throws \CException
     */
    public function getMultipleCompanyManagers($companyIds = [])
    {
        $ancestorIds = [];
        foreach ($companyIds as $companyId) {
            $ancestorIds[] = $companyId;
            $ancestorIds = array_merge($ancestorIds, Lantra::$app->structure->getCompanyAncestors($companyId));
        }
        $criteria = Entry::find();
        $criteria->section = 'companies';
        $criteria->order = 'title';
        $criteria->id = $ancestorIds;
        $managers = [];
        foreach ($criteria->all() as $company) {
            foreach ($this->getCompanyManagers($company) as $manager) {
                ## avoid duplicates
                if (!isset($managers[$manager->id])) {
                    $managers[$manager->id] = $manager;
                }
            }
        }
        return $this->sortManagers($managers);
    }

    private function sortManagers($managers = [])
    {
        usort($managers, function($a, $b) {
            return strcmp(strtolower($a->lastName), strtolower($b->lastName));
        });

        return $managers;
    }

    /**
     * @param Entry $company
     * @param bool $count
     * @return mixed
     * @throws \CException
     */
    public function getCompanyManagers(Entry $company, $count = false)
    {
        return $this->getManagers($company, $count);
    }

    /**
     * @param Entry $company
     * @return array
     * @throws \CException
     */
    public function getCompanyManagerIds(Entry $company)
    {
        return $this->getManagerIds($company);
    }

    /**
     * @param Entry $team
     * @param bool $count
     * @return mixed
     * @throws \CException
     */
    public function getTeamManagers(Entry $team, $count = false)
    {
        return $this->getManagers($team, $count);
    }

    /**
     * @param Entry $team
     * @return array
     * @throws \CException
     */
    public function getTeamManagerIds(Entry $team)
    {
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
        $criteria = Entry::find();
        $criteria->section = 'companies';
        $criteria->order = 'title';
        $criteria->relatedTo = ['targetElement' => $entryId, 'field' => 'companyParent'];
        $ids = $criteria->ids();
        if (count($ids)) {
            foreach ($ids as $id) {
                $return[] = $id;
                ## add children companies recursively
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
        $criteria = Entry::find();
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
        $criteria = Entry::find();
        $criteria->section = 'teams';
        $criteria->relatedTo = ['targetElement' => $companyId, 'field' => 'teamCompany'];
        $criteria->order = 'title';
        return $count ? $criteria->count() : $criteria->all();
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
        $criteria = Entry::find();
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
    function getCompanyManagerCompanyIds(User $user, $type = 'both')
    {
        if (is_null($user)) {
            $user = Craft::$app->getUser();
        }
        $criteria = Entry::find();
        $criteria->section = 'companies';
        if ($type == 'primary') {
            $criteria->relatedTo = ['targetElement' => $user->id, 'field' => 'companyPrimaryManagers'];

        } elseif ($type == 'secondary') {
            $criteria->relatedTo = ['targetElement' => $user->id, 'field' => 'companySecondaryManagers'];
        } else {
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
     * @param User $user
     * @return string
     * @throws Exception
     */
    public function getManagerFirstCompany(User $user)
    {
        $companies = $this->getManagerCompanies($user);
        return $companies ? $companies[0] : null;
    }

    /**
     * @param $company
     * @param $user
     * @param string $type
     * @throws \Exception
     */
    public function removeCompanyManager($company, $user, $type = 'both')
    {

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

        $company->setFieldValue('companyPrimaryManagers', $primaryManagerIds);
        $company->setFieldValue('companySecondaryManagers', $secondaryManagerIds);

        Craft::$app->elements->saveElement($company);
    }

    /**
     * Get manager companies
     *
     * @param User $user
     * @param bool $includeChildren
     * @param string $order
     * @param bool $ids
     * @return BaseElementModel|null
     * @throws Exception
     */
    function getManagerCompanies(User $user, $includeChildren = false, $order = 'companyLabel', $ids = false)
    {
        $companyIds = $this->getCompanyManagerCompanyIds($user);
        if ($includeChildren) {
            $parentIds = $companyIds;
            foreach ($parentIds as $companyId) {
                $companyIds = array_merge($companyIds, $this->getCompanyChildrenIds($companyId));
            }
        }
        if (!count($companyIds)) {
            return null;
        }
        $criteria = Entry::find();
        $criteria->section = 'companies';
        $criteria->limit = null;
        $criteria->id = $companyIds;
        $criteria->order = $order;
        return $ids ? $criteria->ids() : $criteria->all();
    }

    /**
     * Returns team ids where user is primary or secondary manager
     *
     * @param $user
     * @return array
     * @throws Exception
     */
    function getTeamManagerTeamIds(User $user)
    {
        if (is_null($user)) {
            $user = Craft::$app->getUser();
        }
        $criteria = Entry::find();
        $criteria->section = 'teams';
        $criteria->relatedTo = ['or', ['targetElement' => $user, 'field' => 'teamPrimaryManager'], ['targetElement' => $user, 'field' => 'teamSecondaryManagers']];
        return $criteria->ids();
    }

    /**
     * Return all team ids for a manager
     *
     * @param User $user
     * @param bool $includeHierarchy
     * @return array
     * @throws Exception
     */
    function getManagerTeamIds(User $user, $includeHierarchy = false)
    {
        if (is_null($user)) {
            $user = Craft::$app->getUser();
        }
        ## add the scheme manager teams (all of them)
        if ($includeHierarchy && $user->isInGroup('schemeManagers')) {
            return $this->getSchemeTeamIds();
        }
        $return = [];
        ## add the direct teamPrimaryManager and teamSecondaryManager teams
        if (FALSE != $teamManagerTeamIds = $this->getTeamManagerTeamIds($user)) {
            $return = array_merge($return, $teamManagerTeamIds);
        }
        ## add the company teams
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
     * @param User $user
     * @param bool $includeCompanyTeams
     * @return array
     * @throws Exception
     */
    function getManagerTeams(User $user, $includeCompanyTeams = false, $ids = false)
    {
        $teamIds = $this->getManagerTeamIds($user, $includeCompanyTeams);
        if (!count($teamIds)) {
            return null;
        }
        $criteria = Entry::find();
        $criteria->section = 'teams';
        $criteria->limit = null;
        $criteria->id = $teamIds;
        $criteria->fixedOrder = true;
        return $ids ? $criteria->ids() : $criteria->all();
    }

    /**
     * Get available teams for a user
     *
     * @param User $user
     * @return array
     * @throws mixed
     */
    function getAvailableTeams(User $user)
    {
        $teams = $this->getManagerTeams($user, ($user->isInGroup('companyManagers') || $user->isInGroup('schemeManagers')));
        if (empty($teams)) {
            return null;
        }
        $return = [];
        foreach ($teams as $team) {
            if (Lantra::$app->licences->getTeamCompanyLicences($team)) {
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
    function getManagerSubordinateIds(User $user, $includeHierarchy = true)
    {
        if (is_null($user)) {
            $user = Craft::$app->getUser();
        }
        $return = [];
        if (!$this->canManage($user)) {
            return $return;
        }
        ## get companies that this user manages
        $companyIds = $this->getCompanyManagerCompanyIds($user);
        if ($includeHierarchy) {
            $companyChildrenIds = [];
            foreach ($companyIds as $companyId) {
                $companyChildrenIds = array_merge($companyChildrenIds, $this->getCompanyChildrenIds($companyId));
            }
            $companyIds = array_merge($companyIds, $companyChildrenIds);
        }
        $teamIds = $this->getManagerTeamIds($user, $includeHierarchy);
        ## get all users who belong to any of the manager's companies or teams
        $criteria = User::find();
        $criteria->limit = null;
        $criteria->relatedTo = ['or', ['targetElement' => $companyIds, 'field' => 'userCompany'], ['targetElement' => $teamIds, 'field' => 'userTeam']];
        return $criteria->ids();
    }

    /**
     * Return all subordinate users for a manager
     *
     * @param User $user
     * @param bool $includeHierarchy
     * @return mixed
     * @throws Exception
     */
    public function getManagerSubordinates(User $user, $includeHierarchy = false)
    {
        $subordinateIds = $this->getManagerSubordinateIds($user, $includeHierarchy);
        if (!count($subordinateIds)) {
            return null;
        }
        $criteria = User::find();
        $criteria->limit = null;
        $criteria->id = $subordinateIds;
        $criteria->order = 'lastName asc';
        return $criteria->all();
    }

    /**
     * @param $userIds
     * @return array
     * @throws \yii\db\Exception
     */
    public function getReportUsers($userIds)
    {

        $mysql = 'SELECT 
            u.id,           
            CONCAT(u.firstName, " ", u.lastName) as fullName,
            u.email,    
            c.field_userType as userType,        
            c.field_userCompanyName as companyName,
            cc.field_companyLabel as companyLabel,
            c.field_userDateOfBirth as userDateOfBirth,
            c.field_userStartDate as userStartDate,
            c.field_userAddress as userAddress,
            rj.targetId as roleId,
            rc.targetId as companyId    
            FROM craft_users AS u
            LEFT JOIN craft_content as c ON c.elementId = u.id
            LEFT JOIN craft_relations as rj ON rj.sourceId = u.id      
            LEFT JOIN craft_relations as rc ON rc.sourceId = u.id  
            LEFT JOIN craft_content as cc ON cc.elementId = rc.targetId
            WHERE rj.fieldId = 30
            AND rc.fieldId = 128
            AND u.id IN(' . implode(',', $userIds) . ')';

        $rows = Craft::$app->db->createCommand($mysql)->query();
        $return = [];
        $format = 'd-m-Y';
        foreach ($rows as $user) {
            if ($user['userDateOfBirth']) {
                $dateObject = DateTimeHelper::toDateTime($user['userDateOfBirth']);
                $user['userDateOfBirth'] = $dateObject->format($format);
            }
            if ($user['userStartDate']) {
                $dateObject = DateTimeHelper::toDateTime($user['userStartDate']);
                $user['userStartDate'] = $dateObject->format($format);
            }
            $return[$user['id']] = (object)$user;
        }
        return $return;
    }

    /**
     * @param null $userId
     * @param int $limit
     * @param string $search
     * @param null $relatedTo
     * @param null $lastLoginDate
     * @return \craft\elements\db\ElementQueryInterface|UserQuery|null
     */
    public function getManagerUsers($userId = null, $limit = 10, $search = '', $relatedTo = null, $lastLoginDate = null)
    {
        if (!is_null($userId)) {
            $manager = Craft::$app->users->getUserById($userId);
        } else {
            $manager = Craft::$app->getUser()->getIdentity();
        }
        if (!$manager) {
            return null;
        }
        ## build the criteria model
        $criteria = User::find();
        $criteria->limit = $limit;
        $criteria->order = 'lastName asc';
        $criteria->admin = false;
        if ($search) {
            $criteria->search = $search;
        }
        if ($relatedTo) {
            $criteria->relatedTo = $relatedTo;
        }
        if ($lastLoginDate) {
            $criteria->lastLoginDate = $lastLoginDate;
        }
        ## get the subordinate ids if not admin or scheme manager
        if (!$manager->admin && !$manager->isInGroup('schemeManagers')) {
            $subordinateIds = $this->getManagerSubordinateIds($manager, true);
            if (!count($subordinateIds)) {
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
        $criteria = User::find();
        $criteria->groupId = LantraHelper::userGroupId('schemeManagers');
        $criteria->limit = null;
        return $first ? $criteria->one() : $criteria->all();
    }

    /**
     * Return all the scheme manager emails
     *
     * @return array
     */
    function getSchemeManagersEmails()
    {
        $schemeManagers = $this->getSchemeManagers();
        $emails = [];
        foreach ($schemeManagers as $schemeManager) {
            $emails[] = $schemeManager->email;
        }
        return $emails;
    }

    /**
     * @param User $user
     * @param int $level
     * @return array|mixed
     */
    function getUserManagerByLevel(User $user, $level = 1)
    {
        $managers = $this->getUserMangers($user, true);
        foreach ($managers as $manager) {
            $managerLevel = $manager->managerLevel->value;
            if ($managerLevel >= $level) {
                return $manager;
            }
        }
        ## default to first scheme manager
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
    function getUserMangers(User $user, $includeHierarchy = false)
    {
        $return = [];
        $company = $user->userCompany->one();
        if ($company) {
            foreach ($company->companyPrimaryManagers as $primaryManager) {
                $return[$primaryManager->id] = $primaryManager;
            }
            foreach ($company->companySecondaryManagers as $secondaryManager) {
                $return[$secondaryManager->id] = $secondaryManager;
            }
        } else {
            $team = $user->userTeam->one();
            if (!$team) {
                ## default to scheme managers
                return $this->getSchemeManagers();
            }
            $company = $team->teamCompany->one();
            $primaryManager = $team->teamPrimaryManager->one();
            $return[$primaryManager->id] = $primaryManager;
            foreach ($team->teamSecondaryManagers as $secondaryManager) {
                $return[$secondaryManager->id] = $secondaryManager;
            }
        }
        ## loop up the company parents and add primary managers
        $companyParent = $company->companyParent->one();
        if ($includeHierarchy && $companyParent) {
            while ($company != null) {
                foreach ($company->companyPrimaryManagers as $primaryManager) {
                    $return[$primaryManager->id] = $primaryManager;
                }
                $company = $company->companyParent->one();
            }
        }
        return $return;
    }

    /**
     * Check whether they can add a new user
     *
     * @param User $user
     * @return bool
     * @throws Exception
     */
    function canAddUser(User $user)
    {
        if ($user->managerReadOnly) {
            return false;
        }
        ## check there are scheme licences available
        if ($user->admin or $user->isInGroup('SchemeManager')) {
            return (bool)Lantra::$app->licences->getSchemeLicences();
        } else {
            $availableTeams = $this->getAvailableTeams($user, $user->isInGroup('CompanyManager'));
            return (bool)$availableTeams ? count($availableTeams) : false;
        }
    }

    /**
     * @param User $user
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     */
    public function addUserToIndividualCompany(User $user)
    {
        ## get the individualCompany
        $company = $this->getIndividualCompany();
        if ($company) {
            $user->userCompany = [$company->id];
            Craft::$app->elements->saveElement($user);
        }
    }

    /**
     * @param User $user
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     */
    public function addUserToIndividualJobRole(User $user)
    {
        ## get the jobRole
        $jobRole = $this->getIndividualJobRole();
        if ($jobRole) {
            $user->userRole = [$jobRole->id];
            Craft::$app->elements->saveElement($user);
        }
    }

    /**
     * Activate individual user (add to Users and Individuals groups)
     *
     * @param $user
     * @throws \Exception
     */
    public function activateIndividualUser(User $user)
    {
        Craft::$app->users->assignUserToGroups($user->id, [4, 5]);
    }

    /**
     * Deactivate individual user (removed from Users group)
     *
     * @param $user
     * @throws \Exception
     */
    public function deactivateIndividualUser(User $user)
    {
        Craft::$app->users->assignUserToGroups($user->id, [5]);
    }

    /**
     * @param User $user
     * @param $days
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     */
    public function setUserExpiryDate(User $user, $days)
    {
        ## set date in future
        $user->userExpiryDate = strtotime('+' . $days . ' days');
        Craft::$app->elements->saveElement($user);
    }

    /** Get individual company
     *
     * @return null
     */
    public function getIndividualCompany()
    {
        return Lantra::$app->settings->getSetting('individualCompany');
    }

    /** Get emails
     *
     * @param int $entryId
     * @return array
     * @throws mixed
     */
    public function getEmails($entryId)
    {
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
    public function getUsersByEntryId($entryId)
    {
        $entry = Craft::$app->entries->getEntryById($entryId);
        $sectionIdCompanies = LantraHelper::sectionId('companies');
        $sectionIdTeams = LantraHelper::sectionId('teams');

        if (!$entry || ($entry->sectionId != $sectionIdCompanies && $entry->sectionId != $sectionIdTeams)) {
            return (object)[];
        }
        ## get company users
        if ($entry->sectionId == $sectionIdCompanies) {
            return $this->getCompanyUsers($entryId, false, true);
        }
        ## get team users
        return $this->getTeamUsers($entryId, false, true);
    }

    /** Get individual job role
     *
     * @return null
     */
    public function getIndividualJobRole()
    {
        return Lantra::$app->settings->getSetting('individualJobRole');
    }

    /** Get expired users
     *
     * @return object
     * @throws Exception
     */
    public function getExpiredUsers()
    {
        return $this->getExpiringUsers(time());
    }

    /** Get expiring users
     *
     * @param $expiryDate
     * @return UserQuery
     * @throws Exception
     */
    public function getExpiringUsers($expiryDate) : UserQuery
    {
        $criteria = User::find();
        $criteria->userExpiryDate = '< ' . $expiryDate;
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
    public function generateEmail($firstName = null, $lastName = null, $handle = null)
    {
        $domain = Lantra::$app->settings->getSetting('schemeEmailDomain', 'lantra.co.uk');
        $email = mt_rand(1000000, 9999999) . '@' . $domain;
        return Craft::$app->users->getUserByUsernameOrEmail($email) ? $this->generateEmail($firstName, $lastName, $handle) : $email;
    }

    /**
     * Generate a dummy username for user
     *
     * @param $username
     * @param null $firstName
     * @param null $lastName
     * @return string
     */
    public function generateUsername($username, $firstName = null, $lastName = null)
    {
        if ($username) {
            ## remove all characters except A-Z, a-z, 0-9, dots, @, hyphens and spaces, replace spaces with dots
            $username = preg_replace('/\s+/', '.', preg_replace('/[^A-Za-z0-9@\. -]/', '', strtolower($username)));
            if (!$username || Craft::$app->users->getUserByUsernameOrEmail($username)) {
                $username = $username . mt_rand(100000, 999999);
            }
        } elseif ($firstName && $lastName) {
            $handle = preg_replace('/\s+/', '', strtolower(trim($firstName) . '.' . trim($lastName)));
            $username = $handle . '.' . mt_rand(100000, 999999);
        }

        if (!$username) {
            $username = 'user.' . mt_rand(100000, 999999);
        }

        return $username;
    }

    /**
     * @param $companyIds
     * @param $user
     * @param string $type
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     */
    public function setManager($companyIds, $user, $type = 'primary')
    {
        ## remove from existing
        foreach ($this->getCompanyManagerCompanyIds($user, $type) as $companyId) {
            if (!in_array($companyId, $companyIds)) {
                if (null == $company = Craft::$app->entries->getEntryById($companyId)) {
                    continue;
                }
                if ($type == 'primary') {
                    $primaryManagerIds = $company->companyPrimaryManagers->ids();
                    ## remove userId from array
                    if (($key = array_search($user->id, $primaryManagerIds)) !== false) {
                        unset($primaryManagerIds[$key]);
                    }
                    $company->setFieldValue('companyPrimaryManagers', $primaryManagerIds);
                    Craft::$app->elements->saveElement($company, false);
                } else {
                    $secondaryManagerIds = $company->companySecondaryManagers->ids();
                    ## remove userId from array
                    if (($key = array_search($user->id, $secondaryManagerIds)) !== false) {
                        unset($secondaryManagerIds[$key]);
                    }
                    $company->setFieldValue('companySecondaryManagers', $secondaryManagerIds);
                    Craft::$app->elements->saveElement($company, false);
                }
            }
        }
        ## add to new
        foreach ($companyIds as $companyId) {
            if (null == $company = Craft::$app->entries->getEntryById($companyId)) {
                continue;
            }
            $primaryManagerIds = $company->companyPrimaryManagers->count() ? $company->companyPrimaryManagers->ids() : [];
            $secondaryManagerIds = $company->companySecondaryManagers->count() ? $company->companySecondaryManagers->ids() : [];
            if ($type == 'primary' && !in_array($user->id, $primaryManagerIds)) {
                $primaryManagerIds[] = $user->id;
                $company->setFieldValue('companyPrimaryManagers', $primaryManagerIds);
                Craft::$app->elements->saveElement($company);
            }
            if ($type == 'secondary' && !in_array($user->id, $secondaryManagerIds)) {
                $secondaryManagerIds[] = $user->id;
                $company->setFieldValue('companySecondaryManagers', $secondaryManagerIds);
                Craft::$app->elements->saveElement($company, false);
            }
        }
    }

    /**
     * @param $moduleEntry
     * @return array|\craft\base\ElementInterface[]|User[]
     */
    public function getModuleUsers($moduleEntry)
    {
        if (!$moduleEntry->moduleRoles) {
            return [];
        }
        $criteria = User::find();
        $criteria->limit = null;
        $criteria->relatedTo = ['targetElement' => $moduleEntry->moduleRoles->ids(), 'field' => 'userRole'];
        return $criteria->all();
    }

    /**
     *
     */
    function getUserPayments()
    {
        $field = Craft::$app->fields->getFieldByHandle('userPayments');
        $criteria = MatrixBlock::find();
        $criteria->fieldId = $field->id;
        return $criteria;
    }
}
