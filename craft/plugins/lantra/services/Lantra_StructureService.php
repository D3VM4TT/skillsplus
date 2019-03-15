<?php
namespace Craft;

class Lantra_StructureService extends BaseApplicationComponent
{
    public function getHierarchy($companyId = null, $type = 'companies') {
        if (! $companyId) {
            $return = $this->getJsTreeRoot();
        }
        elseif ($type == 'companies') {
            $return = $this->getJsTreeCompanies($companyId);
        }
        elseif ($type == 'users') {
            $return = $this->getJsTreeUsers($companyId);
        }
        elseif ($type == 'teams') {
            $return = $this->getJsTreeTeams($companyId);
        }
        elseif ($type == 'managers') {
            $return = $this->getJsTreeManagers($companyId);
        }
        elseif ($type == 'children') {
            $return = $this->getJsTreeChildren($companyId);
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
        $globalsTheme = craft()->globals->getSetByHandle('globalsTheme');
        return [
            'icon'  => '/assets/img/tree-root.png',
            'text'  => $globalsTheme->schemeName,
            'state' => ['opened' => true],
            'children' => $this->getJsTreeChildren()
        ];
    }

    private function getJsTreeCompanies($companyId) {
        $company = $this->getCompanyById($companyId);
        $managerCount = $companyId ? craft()->lantra_users->getCompanyMangers($company, true) : 0;
        $userCount = $companyId ? craft()->lantra_users->getCompanyUsers($companyId, true) : 0;
        $teamCount = $companyId ? craft()->lantra_users->getCompanyTeams($companyId, true) : 0;
        $childrenCount = $this->getCompanyChildren($companyId, true);

        $return = $this->createNode($companyId ? $companyId : 0, 'company', $companyId, $company->title, 'company');

        if ($managerCount) {
            $title = 'Managers (' . $managerCount . ')';
            $return['children'][]  = $this->createNode($companyId, 'managers', $companyId . 'm', $title, 'group', true);
        }
        if ($teamCount) {
            $title = 'Teams (' . $teamCount . ')';
            $return['children'][]  = $this->createNode($companyId, 'teams', $companyId . 't', $title, 'company', true);
        }
        if ($userCount) {
            $title = 'Users (' . $userCount . ')';
            $return['children'][] = $this->createNode($companyId, 'users', $companyId . 'u', $title, 'group', true);
        }
        if ($childrenCount) {
            $title = 'Companies (' . $childrenCount . ')';
            $return['children'][] = $this->createNode($companyId, 'children', $companyId . 'c', $title, 'company',true);
        }

        return $return;
    }

    private function getJsTreeManagers($companyId) {
        $company = $this->getCompanyById($companyId);
        $managers = craft()->lantra_users->getCompanyMangers($company);
        $return = [];
        foreach($managers as $manager) {
            $nodeId = $companyId.'m'.$manager->id;
            $return[] = $this->createNode($manager->id, 'user', $nodeId, $manager->fullname, 'person');
        }
        return $return;
    }

    private function getJsTreeTeams($companyId) {
        $teams = craft()->lantra_users->getCompanyTeams($companyId);
        $return = [];
        foreach($teams as $team) {
            $nodeId = $companyId.'t'.$team->id;
            $return[] = $this->createNode($team->id, 'team', $nodeId, $team->title, 'group');
        }
        return $return;
    }

    private function getJsTreeUsers($companyId) {
        $users = craft()->lantra_users->getCompanyUsers($companyId);
        $return = [];
        foreach($users as $user) {
            $nodeId = $companyId.'u'.$user->id;
            $return[] = $this->createNode($user->id, 'user', $nodeId, $user->fullname, 'person');
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
    private function getCompanyChildren($companyId = null, $count = false) {

        $criteria = craft()->elements->getCriteria(ElementType::Entry);
        $criteria->section = 'companies';
        $criteria->limit = null;
        if ($companyId) {
            $criteria->relatedTo = ['targetElement' => $companyId, 'field' => 'companyParent'];
        }
        else {
            $criteria->id = $this->getTopCompanyIds();
        }
        return $count ? $criteria->count() : $criteria->find();

    }

    private function getTopCompanyIds() {

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
