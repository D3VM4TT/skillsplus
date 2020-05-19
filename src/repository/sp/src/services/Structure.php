<?php
/**
 * Lantra Skills Plus for Craft CMS 3.x
 *
 * @link      https://coffeebean.design
 * @copyright Copyright (c) 2020 Coffee Bean Design
 */

namespace lantra\sp\services;

use Craft;
use craft\base\Component;
use craft\elements\Entry;
use craft\db\Query;
use craft\models\Section;

use lantra\sp\Plugin as Lantra;
use lantra\sp\helpers\LantraHelper;

class Structure extends Component
{
    /**
     * @param $event
     * @param $entry
     */
    public function onBeforeSaveCompany($event, $entry)
    {
        ## check licences
        if (!Lantra::$app->settings->getSetting('lantraDisableLicences') && !Lantra::$app->licences->updateCompanyLicences($entry)){
            $entry->addError('companyRemainingLicences', 'There are insufficient company licences.');
            $event->performAction = false;
        }

        ## update company label
        $entry->setFieldValue('companyLabel', Lantra::$app->structure->getCompanyLabel($entry));
    }

    /**
     * @param $event
     * @param $entry
     */
    public function onSaveCompany($event, $entry)
    {
        $this->saveCompanyChildren($entry);
        ## delete hierarchy cache
        Lantra::$app->structure->clearHierarchyCache();
    }

    /**
     * @param $userId
     */
    public function clearHierarchyCache($userId = null)
    {
        if (is_null($userId)) {
            $userId = Craft::$app->getUser()->getIdentity()->id;
        }
        Craft::$app->cache->delete('lantraHierarchy' . $userId);
    }

    /**
     * @param string $search
     * @param int $limit
     * @param string $order
     * @return \craft\elements\db\ElementQueryInterface|\craft\elements\db\EntryQuery|null
     */
    public function companyCriteria($search = '', $limit = 25, $order = 'companyLabel')
    {
        $user = Craft::$app->getUser()->getIdentity();
        $criteria = Entry::find();
        $criteria->section = 'companies';
        $criteria->limit = $limit;
        $criteria->order = $order;
        $excludeIds = [];
        if ( ! $user->isInGroup('schemeManagers')) {
            $individualCompany = Lantra::$app->users->getIndividualCompany();
            if ($individualCompany && is_object($individualCompany)) {
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
            $mysql = 'SELECT c.elementId as id FROM {{%content}} c                
                WHERE c.elementId = "' . $search . '"
                OR c.field_legacyId = "' . $search . '"';
        }
        else {
            $mysql = 'SELECT c.elementId as id FROM {{%content}} c               
                WHERE c.title LIKE "%' . $search . '%"
                OR c.field_companyLabel LIKE "%' . $search . '%"';
        }

        $result = Craft::$app->db->createCommand($mysql)->query();
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
        return Craft::$app->entries->getEntryById($companyId);
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
        $user = Craft::$app->getUser()->getIdentity();
        if ($user->admin or $user->isInGroup('schemeManagers')) {
            $children[] = $this->createNode('scheme', 'managers', 's', 'Scheme Managers', 'group', true);
            $children = array_merge($children, $this->getJsTreeChildren());
        }
        else {
            // does this user manage teams or companies?
            $managerTeams = Lantra::$app->users->getManagerTeams($user);
            $managerCompanies = Lantra::$app->users->getManagerCompanies($user);

            $children = [];
            if ($managerCompanies && count($managerCompanies)) {
                foreach ($managerCompanies as $company) {
                    // skip companies where they are the manager of the parent too
                    if (Lantra::$app->users->isParentCompanyManager($company, $user)){
                        continue;
                    }
                    $children[] = $this->getHierarchy($company->id, 'companies');
                }
            }
            // does this user manage teams?
            if ($managerTeams && count($managerTeams)) {
                foreach ($managerTeams as $team) {
                    $company = $team->teamCompany->one();
                    $nodeId = $company->id.'t'.$team->id;
                    $children[] = $this->createNode($team->id, 'teams', $nodeId, $team->title, 'group', true);
                }
            }
        }

        return [
            'icon'  => '/assets/img/tree-root.png',
            'text'  => LantraHelper::setting('schemeName'),
            'state' => ['opened' => true],
            'children' => $children
        ];
    }

    private function getJsTreeCompany($companyId) {
        $company = $this->getCompanyById($companyId);
        $managerCount = $companyId ? Lantra::$app->users->getCompanyManagers($company, true) : 0;
        $memberCount = $companyId ? Lantra::$app->users->getCompanyMembers($companyId, true) : 0;
        $teams = $companyId ? Lantra::$app->users->getCompanyTeams($companyId) : [];
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
            $managers = Lantra::$app->users->getSchemeManagers();
        }
        else {
            $entry = Craft::$app->entries->getEntryById($entryId);
            // company managers
            if ($entry->getSection()->id == 3) {
                $managers = Lantra::$app->users->getCompanyManagers($entry);
            } // team managers
            else {
                $managers = Lantra::$app->users->getTeamManagers($entry);
            }
        }
        $return = [];
        foreach($managers as $manager) {
            $nodeId = (isset($entry) ? $entry->id : '') . 'm' . $manager->id;
            $title = $manager->fullname;
            if ($manager->userRole->count()) {
                $title .= ' (' . $manager->userRole->one()->title . ')';
            }
            $return[] = $this->createNode($manager->id, 'user', $nodeId, $title, 'person');
        }
        return $return;
    }

    private function getJsTreeTeam($teamId) {
        $team =  Craft::$app->entries->getEntryById($teamId);
        $managerCount = Lantra::$app->users->getTeamManagers($team, true);
        $return[]  = $this->createNode($teamId, 'managers', $team->id . 'm', 'Managers (' . $managerCount . ')', 'group', true);
        $users = $this->getJsTreeUsers($teamId);
        $return = array_merge($return, $users);
        return $return;
    }

    private function getJsTreeUsers($entryId) {
        $entry =  Craft::$app->entries->getEntryById($entryId);
        // company members
        if ($entry->getSection()->id == 3) {
            $members = Lantra::$app->users->getCompanyMembers($entry->id);
        }
        // team members
        else {
            $members = Lantra::$app->users->getTeamMembers($entry->id);
        }
        $return = [];
        foreach($members as $user) {
            $nodeId = $entryId.'u'.$user->id;
            $title = $user->fullname;
            if ($user->userRole->count()) {
                $title .= ' (' . $user->userRole->one()->title . ')';
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

        $criteria = Entry::find();
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
        return $count ? $criteria->count() : $criteria->all();

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
        return $company->companyParent->count() ? $company->companyParent->one() : null;
    }

    public function getCompanyAncestors($companyId = null) {
        if (isset($this->_companyAncestors[$companyId])) {
            return $this->_companyAncestors[$companyId];
        }
        $ancestors = [];
        $company = Craft::$app->entries->getEntryById($companyId);
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

    /**
     * @return array
     */
    public function getTopCompanyIds() {
        $query = (new Query())
            ->from('{{%entries}} e')
            ->where('sectionId=3')
            ->andWhere('e.id NOT IN (SELECT DISTINCT sourceId from {{%relations}} WHERE fieldId = 4)')
            ->all();

        $return = [];
        foreach ($query as $row) {
            $return[] = $row['id'];
        }
        return $return;
    }

    /**
     * @param $company
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     */
    public function saveCompanyChildren($company)
    {
        $children = $this->getCompanyChildren($company, null, null);
        if ($children) {
            foreach ($children as $child) {
                Craft::$app->elements->saveElement($child, false);
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

        if ($company->companyParent->count())
        {
            $parent = $company->companyParent->one();
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
        $teamCompany = $team->teamCompany->one();
        $label = $team->title;
        if ( ! $teamCompany){
            return $label;
        }
        return $this->prependCompanyParent($teamCompany, $label);
    }

    /**
     * @throws \Throwable
     * @throws \craft\errors\SectionNotFoundException
     */
    private function structureCompanies()
    {
        $companySection = Craft::$app->getSections()->getSectionById(3);
        $companySection->type = 'structure';
        Craft::$app->getSections()->saveSection($companySection);

        $criteria = Entry::find();
        $criteria->section = 'companies';
        $criteria->limit = null;
        $companies = $criteria->all();

        foreach ($companies as $company) {
            if ($company->companyParent->count()) {
                $parent = $company->companyParent->one();
                $company->newParentId = $parent->id;
                Craft::$app->elements->saveElement($company);
            }
        }
    }
}
