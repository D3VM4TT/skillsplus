<?php
namespace Craft;

class Lantra_StructureService extends BaseApplicationComponent
{
    /**
     * @param $userId
     */
    public function clearHierarchyCache($userId) {
        craft()->cache->delete('lantraHierarchy' . $userId);
    }

    /**
     * @param $search
     * @param $limit
     * @param $order
     * @return array
     * @throws \CException
     */
    public function companyCriteria($search = '', $limit = 25, $order = 'companyLabel') {
        $user = craft()->userSession->getUser();
        $criteria = craft()->elements->getCriteria(ElementType::Entry);
        $criteria->section = 'companies';
        $criteria->limit = $limit;
        $criteria->order = $order;
        $excludeIds = [];
        if ( ! $user->isInGroup('schemeManagers')) {
            $individualCompany = craft()->lantra_users->getIndividualCompany();
            if ($individualCompany) {
                $excludeIds[] = $individualCompany->id;
            }
        }
        if ($search) {
            $searchIds = $this->searchCompanyIds(trim($search));
            if (empty($searchIds)) {
                return null;
            }
            $criteria->id = 'or, ' . implode(',', array_diff($searchIds, $excludeIds));
        }
        elseif (count($excludeIds)) {
            $criteria->id = 'and, not ' . implode(', not ', $excludeIds);
        }
        return $criteria;
    }


    /** more efficient way to search companies */
    private function searchCompanyIds($search = '') {
        if (intval($search)) {
            $mysql = 'SELECT c.elementId as id FROM {{content}} c                
                WHERE c.elementId = "' . $search . '"
                OR c.field_legacyId = "' . $search . '"';
        }
        else {
            $mysql = 'SELECT c.elementId as id FROM {{content}} c               
                WHERE c.title LIKE "%' . $search . '%"
                OR c.field_companyLabel LIKE "%' . $search . '%"';
        }

        $result = craft()->db->createCommand($mysql)->query();
        $ids = [];
        foreach ($result as $row) {
            $ids [] = $row['id'];
        }
        return $ids;
    }

    public function getHierarchy($entryId = null, $type = 'companies') {
        if (! $entryId) {
            $return = $this->getJsTreeRoot();
        }
        elseif ($type == 'companies') {
            $return = $this->getJsTreeCompany($entryId);
        }
        elseif ($type == 'users') {
            $return = $this->getJsTreeUsers($entryId);
        }
        elseif ($type == 'teams') {
            $return = $this->getJsTreeTeam($entryId);
        }
        elseif ($type == 'managers') {
            $return = $this->getJsTreeManagers($entryId);
        }
        elseif ($type == 'children') {
            $return = $this->getJsTreeChildren($entryId);
        }
        return $return;
    }

    private function getCompanyById($companyId) {
        return craft()->entries->getEntryById($companyId);
    }

    private function createNode($elementId, $nodeType, $nodeId, $title, $icon, $children = null) {
        $node = [
            'elementId' => $elementId,
            'nodeType'  => $nodeType,
            'nodeId'    => $nodeId,
            'text'      => $title,
            'icon'      => '/assets/img/' . $icon . '.svg',
            "li_attr"   => ['class' => 'type-' . $nodeType, 'id' => 'node-' . $nodeId],
        ];
        if ($children == true) {
            $node['children'] = true;
        }
        return $node;
    }

    private function getJsTreeRoot() {
        $user = craft()->userSession->getUser();
        if ($user->admin or $user->isInGroup('schemeManagers')) {
            $children[] = $this->createNode('scheme', 'managers', 's', 'Scheme Managers', 'group', true);
            $children = array_merge($children, $this->getJsTreeChildren());
        }
        else {
            // does this user manage teams or companies?
            $managerTeams = craft()->lantra_users->getManagerTeams($user);
            $managerCompanies = craft()->lantra_users->getManagerCompanies($user);

            $children = [];
            if ($managerCompanies && count($managerCompanies)) {
                foreach ($managerCompanies as $company) {
                    // skip companies where they are the manager of the parent too
                    if (craft()->lantra_users->isParentCompanyManager($company, $user)){
                        continue;
                    }
                    $children[] = $this->getHierarchy($company->id, 'companies');
                }
            }
            // does this user manage teams?
            if ($managerTeams && count($managerTeams)) {
                foreach ($managerTeams as $team) {
                    $company = $team->teamCompany->first();
                    $nodeId = $company->id.'t'.$team->id;
                    $children[] = $this->createNode($team->id, 'teams', $nodeId, $team->title, 'group', true);
                }
            }
        }

        return [
            'icon'  => '/assets/img/tree-root.png',
            'text'  => craft()->lantra_settings->getSetting('schemeName'),
            'state' => ['opened' => true],
            'children' => $children
        ];
    }

    private function getJsTreeCompany($companyId) {
        $company = $this->getCompanyById($companyId);
        $managerCount = $companyId ? craft()->lantra_users->getCompanyManagers($company, true) : 0;
        $memberCount = $companyId ? craft()->lantra_users->getCompanyMembers($companyId, true) : 0;
        $teams = $companyId ? craft()->lantra_users->getCompanyTeams($companyId) : [];
        $childrenCount = $this->getCompanyChildren($companyId, true);

        $return = $this->createNode($companyId ? $companyId : 0, 'company', $companyId, $company->title, 'company');

        if ($managerCount) {
            $title = 'Managers (' . $managerCount . ')';
            $return['children'][]  = $this->createNode($companyId, 'managers', $companyId . 'm', $title, 'group', true);
        }
        if ($memberCount) {
            $title = 'Members (' . $memberCount . ')';
            $return['children'][] = $this->createNode($companyId, 'users', $companyId . 'u', $title, 'group', true);
        }
        if ($childrenCount) {
            $title = 'Companies (' . $childrenCount . ')';
            $return['children'][] = $this->createNode($companyId, 'children', $companyId . 'c', $title, 'company',true);
        }
        foreach($teams as $team) {
            $nodeId = $companyId.'t'.$team->id;
            $return['children'][] = $this->createNode($team->id, 'teams', $nodeId, $team->title, 'group', true);
        }
        return $return;
    }

    private function getJsTreeManagers($entryId) {
        if ($entryId == 'scheme') {
            $managers = craft()->lantra_users->getSchemeManagers();
        }
        else {
            $entry = craft()->entries->getEntryById($entryId);
            // company managers
            if ($entry->getSection()->id == 3) {
                $managers = craft()->lantra_users->getCompanyManagers($entry);
            } // team managers
            else {
                $managers = craft()->lantra_users->getTeamManagers($entry);
            }
        }
        $return = [];
        foreach($managers as $manager) {
            $nodeId = (isset($entry) ? $entry->id : '') . 'm' . $manager->id;
            $title = $manager->fullname;
            if ($manager->userRole->total()) {
                $title .= ' (' . $manager->userRole->first()->title . ')';
            }
            $return[] = $this->createNode($manager->id, 'user', $nodeId, $title, 'person');
        }
        return $return;
    }

    private function getJsTreeTeam($teamId) {
        $team =  craft()->entries->getEntryById($teamId);
        $managerCount = craft()->lantra_users->getTeamManagers($team, true);
        $return[]  = $this->createNode($teamId, 'managers', $team->id . 'm', 'Managers (' . $managerCount . ')', 'group', true);
        $users = $this->getJsTreeUsers($teamId);
        $return = array_merge($return, $users);
        return $return;
    }

    private function getJsTreeUsers($entryId) {
        $entry =  craft()->entries->getEntryById($entryId);
        // company members
        if ($entry->getSection()->id == 3) {
            $members = craft()->lantra_users->getCompanyMembers($entry->id);
        }
        // team members
        else {
            $members = craft()->lantra_users->getTeamMembers($entry->id);
        }
        $return = [];
        foreach($members as $user) {
            $nodeId = $entryId.'u'.$user->id;
            $title = $user->fullname;
            if ($user->userRole->total()) {
                $title .= ' (' . $user->userRole->first()->title . ')';
            }
            $return[] = $this->createNode($user->id, 'user', $nodeId, $title, 'person');
        }
        return $return;
    }

    private function getJsTreeChildren($companyId = null) {
        $companies = $this->getCompanyChildren($companyId);
        $return = [];
        foreach($companies as $company) {
            $return[] = $this->getHierarchy($company->id, 'companies');
        }
        return $return;
    }

    /**
     * @param null $companyId
     * @param bool $count
     * @return array|int
     * @throws Exception
     * @throws \CException
     */
    public function getCompanyChildren($companyId = null, $count = false, $status = 'live') {

        $criteria = craft()->elements->getCriteria(ElementType::Entry);
        $criteria->section = 'companies';
        $criteria->limit = null;
        $criteria->order = 'title';
        $criteria->status = $status;
        if ($companyId) {
            $criteria->relatedTo = ['targetElement' => $companyId, 'field' => 'companyParent'];
        }
        else {
            $criteria->id = $this->getTopCompanyIds();
        }
        return $count ? $criteria->count() : $criteria->find();

    }

    /* cache children */
    private $_companyDescendants = [];

    public function getCompanyDescendants($companyId = null) {
        if (isset($this->_companyDescendants[$companyId])) {
            return $this->_companyDescendants[$companyId];
        }
        $descendants = [];
        if (null != $children = $this->getCompanyChildren($companyId)) {
            foreach($children as $child) {
                $descendants[] = $child->id;
                $descendants = array_merge($descendants, $this->getCompanyDescendants($child->id));
            }
        }
        $this->_companyDescendants[$companyId] = $descendants;
        return $descendants;
    }

    /* cache parents */
    private $_companyAncestors = [];

    public function getCompanyParent($company) {
        return $company->companyParent->count() ? $company->companyParent->first() : null;
    }

    public function getCompanyAncestors($companyId = null) {
        if (isset($this->_companyAncestors[$companyId])) {
            return $this->_companyAncestors[$companyId];
        }
        $ancestors = [];
        $company = craft()->entries->getEntryById($companyId);
        if (null != $parent = $this->getCompanyParent($company)) {
            $ancestors[] = $parent->id;
            $ancestors = array_merge($ancestors, $this->getCompanyAncestors($parent->id));
        }
        $this->_companyAncestors[$companyId] = $ancestors;
        return $ancestors;
    }

    public function appendCompanyDescendants($companyIds = []) {
        $return = $companyIds;
        foreach($companyIds as $id) {
            foreach( $this->getCompanyDescendants($id) as $descendantId) {
                if ( ! in_array($descendantId, $return)) {
                    $return[] = $descendantId;
                }
            }
        }
        return $return;
    }

    public function getTopCompanyIds() {
        // just return all companies without a parent
        $query = craft()->db->createCommand()
            ->select('e.id' )
            ->from('entries e')
            ->where('sectionId = 3')
            ->andWhere('e.id NOT IN (SELECT DISTINCT sourceId from craft_relations WHERE fieldId = 4)')
            ->queryAll();

        $return = [];
        foreach ($query as $row) {
            $return[] = $row['id'];
        }
        return $return;
    }

    /**
     * @param $company
     * @throws Exception
     * @throws \CException
     */
    public function saveCompanyChildren($company) {
        $children = $this->getCompanyChildren($company, null, null);
        if ($children) {
            foreach ($children as $child) {
                craft()->entries->saveEntry($child);
            }
        }
    }

    /**
     * @return string
    */
    public function getCompanyLabel($company)
    {
        if ( ! $company) {
            return '';
        }
        return $this->prependCompanyParent($company, $company->title);
    }

    /**
     * @param $company
     * @param $label
     * @return string
     * @throws mixed
     */
    private function prependCompanyParent($company, $label) {

        if ($company->companyParent->total())
        {
            $parent = $company->companyParent->first();
            $label = $parent->title . ' > ' . $label;
            $label = $this->prependCompanyParent($parent, $label);
        }

        return $label;
    }

    /**
     * @return string
     */
    public function getTeamLabel($team)
    {
        if ( ! $team) {
            return '';
        }
        $teamCompany = $team->teamCompany->first();
        $label = $team->title;
        if ( ! $teamCompany){
            return $label;
        }
        return $this->prependCompanyParent($teamCompany, $label);
    }

}
