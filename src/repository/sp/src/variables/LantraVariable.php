<?php
/**
 * Lantra Skills Plus for Craft CMS 3.x
 *
 * @link      https://coffeebean.design
 * @copyright Copyright (c) 2020 Coffee Bean Design
 */

namespace lantra\sp\variables;

use Craft;
use craft\db\Query;

use lantra\sp\Plugin as Lantra;
use lantra\sp\helpers\LantraHelper;
use verbb\supertable\elements\SuperTableBlockElement;

class LantraVariable
{
    /**
     * @return mixed
     */
    public function release()
    {
        return LantraHelper::getRelease();
    }

    /**
     * @param $userId
     * @return mixed
     */
    public function userUnitIds($userId)
    {
        $user = (is_null($userId)) ? null : $this->getUser($userId);
        return Lantra::$app->users->getUserUnitIds($user);
    }

    /**
     * @param $resultEntryId
     * @param $customKey
     * @param bool $id
     * @return string
     * @throws Exception
     */
    public function resultCustom($resultEntryId, $customKey, $id = false)
    {
        $field = Craft::$app->fields->getFieldByHandle('resultCustom');
        $criteria = SuperTableBlockElement::find();
        $criteria->ownerId = $resultEntryId;
        $criteria->fieldId = $field->id;
        $blocks = $criteria->all();
        foreach ($blocks as $block) {
            if ($block->customKey == $customKey) {
                return $id ? $block->id : $block->customValue;
            }
        }
        return '';
    }

    /**
     * @return mixed
     */
    public function userResultCache($userIds)
    {
        return Lantra::$app->results->getUserResultCache($userIds);
    }

    /**
     * @return mixed
     */
    public function queue()
    {
        return Lantra::$app->queue->get();
    }

    /**
     * @return mixed
     */
    public function job($elementId)
    {
        return Lantra::$app->queue->job($elementId);
    }

    /**
     * Get setting
     *
     * @param $key
     * @param $default
     * @return mixed
     */
    public function setting($key, $default = '')
    {
        return Lantra::$app->settings->getSetting($key, $default);
    }

    /**
     * @param $attemptEntry
     * @return array
     */
    function getAttemptMeta($attemptEntry)
    {
        $return = [
          'total' => 0,
          'correct' => 0,
          'percent' => 0
        ];

        $return['total'] = count($attemptEntry->attemptAnswers);
        // loop through answers and count correct
        foreach ($attemptEntry->attemptAnswers as $answerBlock) {
            if ($answerBlock->correct) {
                $return['correct']++;
            }
        }
        $return['percent'] = $return['total'] ? round($return['correct'] / $return['total'] * 100) : 0;
        return $return;
    }

    /**
     * @param $accountId
     * @return int|null
     * @throws \craft\errors\AssetConflictException
     * @throws \craft\errors\VolumeObjectExistsException
     */
    public function evidenceFolderId($accountId)
    {
        $user = Craft::$app->users->getUserById($accountId);
        $folder = LantraHelper::userEvidenceFolder($user);
        return $folder && isset($folder->id) ? $folder->id : null;
    }

    /**
     * @param $comment
     * @param null $userId
     * @throws \Exception
     */
    public function readComment($comment, $userId = null)
    {
        if (false == $user = $this->getUser($userId)) {
            return;
        }
        return Lantra::$app->results->readComment($comment, $user->id);
    }

    /**
     * @param $result
     * @param $userId
     * @return int
     */
    public function unreadComments($result, $userId = null)
    {
        if (false == $user = $this->getUser($userId)) {
            return;
        }
        return Lantra::$app->results->unreadComments($result, $user->id);
    }

    /**
     * @param $company
     * @return mixed
     */
    public function companyLabel($company)
    {
        return $company->companyLabel;
    }

    /**
     * @param $company
     * @return mixed
     */
    public function teamLabel($company)
    {
        return Lantra::$app->structure->getTeamLabel($company);
    }


    /**
     * Return full list of users for a team or company
     *
     * @param int $userId
     * @return string
     */
    public function managerHierarchy($userId = null)
    {
        if (false == $user = $this->getUser($userId)) {
            return null;
        }
        return Lantra::$app->users->getManagerHierarchy($user);
    }

    /**
     * Create data for JSTree
     *
     * @param int $userId
     * @param int $currentNode
     * @return string
     */
    public function jsTreeData($userId = null, $currentNode = 0)
    {
        $js = [
            'icon'  => '/assets/img/tree-root.png',
            'text'  => Lantra::$app->settings->getSetting('schemeName'),
            'state' => ['opened' => true],
        ];

        $data = $this->managerHierarchy($userId);
        foreach ($data as $node) {
            $js['children'][] = $this->jsTreeAddNode($node, $currentNode);
        }

        return json_encode($js);
    }

    /**
     * Add a node for JSTree
     *
     * @param array $node
     * @param int $currentNode
     * @return array
     */
    private function jsTreeAddNode($node, $currentNode = 0)
    {
        $array = [
            'elementId' => $node['elementId'],
            'nodeType'  => $node['nodeType'],
            'nodeId'    => $node['nodeId'],
            'text'      => $node['title'],
            'icon'      => '/assets/img/' . $node['icon'] . '.svg',
            "li_attr"   => ['class' => 'type-' . $node['nodeType'], 'id' => 'node-' . $node['nodeId']],
        ];
        if ($node['nodeType'] == 'companies' || $node['nodeType'] == 'teams') {
            foreach($node['managers'] as $manager) {
                $array['children'][] = $this->jsTreeAddNode($manager, $currentNode);
            }
        }
        foreach($node['children'] as $child) {
            $array['children'][] = $this->jsTreeAddNode($child, $currentNode);
        }
        return $array;
    }

    /**
     * Return full list of users for a team or company
     *
     * @param int $entryId
     * @return string
     */
    public function emailList($entryId = null)
    {
        $emails = Lantra::$app->users->getEmails($entryId);
        return implode(';', $emails);
    }

    /**
     * Return count of users for a team or company
     *
     * @param int $entryId
     * @param string
     * @param string
     * @return int
     */
    public function userCount($entryId = null, $status = 'active', $type = 'company')
    {
        $active = count(Lantra::$app->users->getUsersByEntryId($entryId));
        if ($status == 'active') {
            return $active;
        }
        $suspended = Lantra::$app->users->countSuspendedUsers($entryId, $type);
        if ($status == 'suspended') {
            return $suspended;
        }
        return $active + $suspended;
    }

    /**
     * Return criteria based on username, firstName, lastName, userCompany
     *
     * @param $search
     * @param $status
     * @param $companyId
     * @param $limit
     * @param $order
     * @return mixed
     */
    public function userCriteria($search, $status = 'all', $companyId, $limit, $order)
    {
        return Lantra::$app->users->userCriteria($search, $status, $companyId, $limit, $order);
    }

    /**
     * Return criteria based on company name and location
     *
     * @param $search
     * @param $limit
     * @param $order
     * @return mixed
     */
    public function companyCriteria($search, $limit, $order)
    {
        return Lantra::$app->structure->companyCriteria($search, $limit, $order);
    }

    /**
     * Return criteria based on report name
     *
     * @param $search
     * @param $limit
     * @param $order
     * @param $automated
     * @return mixed
     */
    public function reportCriteria($search, $limit, $order, $automated = false)
    {
        return Lantra::$app->reports->reportCriteria($search, $limit, $order, $automated);
    }

    /**
     * @param $task
     * @param null $userId
     * @throws ForbiddenHttpException
     */
    public function requirePermission($task, $userId = null)
    {
        if (!$this->can($task, $userId)) {
            throw new ForbiddenHttpException('User is not permitted to ' . $task . '.');
        }
    }

    /**
     * @param null $task
     * @param null $userId
     * @return bool
     */
    public function can($task = null, $userId = null)
    {
        if (false == $user = $this->getUser($userId)) {
            return false;
        }
        $permission = false;
        if ($task == 'editCompanies') {
            $section = Craft::$app->sections->getSectionByHandle('companies');
            $permission = 'editEntries:'.$section->uid;
        }
        if ($task == 'editTeams') {
            $section = Craft::$app->sections->getSectionByHandle('teams');
            $permission = 'editEntries:'.$section->uid;
        }
        if ($task == 'editModules') {
            $section = Craft::$app->sections->getSectionByHandle('modules');
            $permission = 'editEntries:'.$section->uid;
        }
        if ($task == 'editReports') {
            $section = Craft::$app->sections->getSectionByHandle('reports');
            $permission = 'editEntries:'.$section->uid;
        }
        if ($task == 'editRoles') {
            $category = Craft::$app->categories->getGroupByHandle('roles');
            $permission = 'editCategories:'.$category->uid;
        }
        if ($task == 'editUsers') {
            $permission = 'editUsers';
        }
        return $permission ? $user->can($permission) : false;
    }

    /**
     * Check whether this user can manage teams or companies
     *
     * @param null $userId
     * @param bool $scheme
     * @return bool
     */
    public function canManage($userId = null, $scheme = false)
    {
        if (false == $user = $this->getUser($userId)) {
            return false;
        }
        return Lantra::$app->users->canManage($user, $scheme);
    }

    /**
     * Check whether this user manages the subordinate
     *
     * @param null $subordinateId
     * @param bool $managerId
     * @param bool $includeHierarchy
     * @return bool
     */
    public function isManager($subordinateId = null, $managerId = null, $includeHierarchy = true)
    {
        $manager = (is_null($managerId)) ? null : $this->getUser($managerId);
        return Lantra::$app->users->isManager($subordinateId, $manager, $includeHierarchy);
    }

    /**
     * @param $resultEntry
     * @param null $managerId
     * @return mixed
     */
    public function canEndorse($resultEntry, $managerId = null) {
        if (false == $manager = $this->getUser($managerId)) {
            return false;
        }
        ## you can't mark your own homework...!
        $owner = $resultEntry->resultOwner->total() ? $resultEntry->resultOwner->one() : $resultEntry->author;
        if ($manager->id == $owner->id) {
            return false;
        }
        return $this->isManager($owner->id, $manager);
    }

    /**
     * @param null $entry
     * @return null
     */
    public function legacyResultFiles($entry = null)
    {
        if (! $entry || ! $entry->legacyResultFiles) {
            return $entry;
        }
        return Lantra::$app->results->legacyResultFiles($entry);
    }

    /**
     * Can add user
     *
     * @param null $userId
     * @return bool
     */
    public function canAddUser($userId = null)
    {
        if (false == $user = $this->getUser($userId)) {
            return false;
        }
        return Lantra::$app->users->canAddUser($user);
    }

    /**
     * Display list of user types
     *
     * @param null $userId
     * @return string
     */
    public function userType($userId = null)
    {
        if (false == $user = $this->getUser($userId)) {
            return '';
        }
        if ($user->admin) {
            $type = 'Lantra Admin';
        }
        else {
            $type = '';
            if ($user->isInGroup('users')) {
                $type .= 'User';
            }
            if ($user->isInGroup('schemeManagers')) {
                $type .= ($type ? ', ': '') . 'Scheme Manager';
            }
            if ($user->isInGroup('companyManagers')) {
                $type .= ($type ? ', ': '') . 'Company Manager';
            }
            if ($user->isInGroup('teamManagers')) {
                $type .= ($type ? ', ': '') . 'Team Manager';
            }
        }
        return $type;
    }

    /**
     * @return false|null|string
     * @throws \yii\db\Exception
     */
    public function totalCompanyLicences()
    {
        $query = (new Query())->from('{{%content}}');
        return $query->sum('field_companyRemainingLicences');
    }

    /**
     * Return user company (team company)
     *
     * @param null $userId
     * @return BaseElementModel|null
     * @throws Exception
     */
    public function userCompany($userId = null)
    {
        if (false == $user = $this->getUser($userId)) {
            return null;
        }
        return Lantra::$app->users->userCompany($user);
    }

    /**
     * Return user managers
     *
     * @param null $userId
     * @param int $level
     * @param bool $includeHierarchy
     * @return BaseElementModel|null
     * @throws Mixed
     */
    public function userManagers($userId = null, $level = 0, $includeHierarchy = true)
    {
        if (false == $user = $this->getUser($userId)) {
            return null;
        }
        if ($level > 0) {
            return Lantra::$app->users->getUserManagerByLevel($user, $level);
        }
        return Lantra::$app->users->getUserMangers($user, $includeHierarchy);
    }

    /**
     * Return company users
     *
     * @param null $companyId
     * @return BaseElementModel|null
     * @throws Exception
     */
    public function companyUsers($companyId = null)
    {
        return Lantra::$app->users->getCompanyUsers($companyId);
    }

    /**
     * Display remaining attempts
     *
     * @param Entry $unitEntry
     * @param Entry $resultEntry
     * @param null $userId
     * @return int|string
     */
    public function remainingAttempts($unitEntry, $resultEntry = null, $userId = null)
    {
        if (false == $user = $this->getUser($userId)) {
            return 0;
        }
        if (is_null($resultEntry)) {
            $resultEntry = Lantra::$app->results->getUnitResult($user->id, $unitEntry->id);
        }
        return Lantra::$app->attempts->remainingAttempts($unitEntry, $resultEntry, $user->id);
    }

    /**
     * Get manager companies
     *
     * @param null $userId
     * @param bool $includeChildren
     * @return BaseElementModel|null
     * @throws Exception
     */
    public function managerCompanies($userId = null, $includeChildren = false)
    {
        if (false == $user = $this->getUser($userId)) {
            return null;
        }
        return Lantra::$app->users->getManagerCompanies($user, $includeChildren);
    }

    /**
     * Return manager teams
     *
     * @param null $userId
     * @param bool $includeCompanyTeams
     * @return BaseElementModel|null
     * @throws Exception
     */
    public function managerTeams($userId = null, $includeCompanyTeams = false)
    {
        if (false == $user = $this->getUser($userId)) {
            return null;
        }
        return Lantra::$app->users->getManagerTeams($user, $includeCompanyTeams);
    }

    /**
     * Return available teams (company licences available)
     *
     * @param null $userId
     * @return array|null
     * @throws Exception
     */
    public function availableTeams($userId = null)
    {
        if (false == $user = $this->getUser($userId)) {
            return null;
        }
        return Lantra::$app->users->getAvailableTeams($user);
    }

    /**
     * Return all subordinate users for a manager
     *
     * @param null $userId
     * @param bool $includeHierarchy
     * @return mixed
     * @throws Exception
     */
    public function managerSubordinates($userId = null, $includeHierarchy = false)
    {
        if (false == $user = $this->getUser($userId)) {
            return null;
        }
        return Lantra::$app->users->getManagerSubordinates($user, $includeHierarchy);
    }

    /**
     * @param $company
     * @return string
     */
    public function hierarchyLabel($company)
    {
        return str_replace(' > ' . $company->title, '', $company->companyLabel);
    }

    /**
     * Return all result entries requiring endorsement for a manager
     *
     * @param null $userId
     * @param int $limit
     * @param bool $count
     * @return mixed
     * @throws Exception
     */
    public function managerEndorsementResults($userId = null, $limit = 10, $count = false)
    {
        if (false == $user = $this->getUser($userId)) {
            return null;
        }
        return Lantra::$app->results->getManagerEndorsementResults($user, ($count == false ? $limit : null), $count);
    }

    /**
     * Return all users requiring endorsement for a manager
     *
     * @param null $userId
     * @param int $limit
     * @param bool $count
     * @param bool $directSubordinates
     * @return mixed
     */
    public function managerEndorsementUsers($userId = null, $limit = 10, $count = false, $directSubordinates = false)
    {
        if (false == $user = $this->getUser($userId)) {
            return null;
        }
        return Lantra::$app->results->getManagerEndorsementUsers($user, ($count == false ? $limit : null), $count, $directSubordinates);
    }

    /**
     * @param null $userId
     * @param bool $directSubordinates
     * @return array|int|null
     * @throws \yii\db\Exception
     */
    public function managerCountEndorsementUsers($userId = null, $directSubordinates = false)
    {
        if (false == $user = $this->getUser($userId)) {
            return null;
        }
        return Lantra::$app->results->countManagerEndorsementUsers($user, $directSubordinates);
    }

    /**
     * Return a manager report
     *
     * @param string $reportType
     * @param null $userId
     * @param mixed $days
     * @param mixed $search
     * @param int $limit
     * @param bool $count
     * @return mixed
     * @throws Exception
     */
    public function managerReport($reportType = 'users', $userId = null,  $days = 'all', $search = '', $limit = 10, $count = false)
    {
        if (false == $user = $this->getUser($userId)) {
            return null;
        }
        $criteria = null;
        switch ($reportType) {
            case 'units-required':
                $criteria = Lantra::$app->results->getManagerUnitRequiredResults($user->id);
                break;
            case 'units-blocked':
                $criteria = Lantra::$app->results->getManagerUnitBlockedResults($user->id, $days, $limit, $search);
            break;
            case 'units-expiring':
                $criteria = Lantra::$app->results->getManagerUnitExpiringResults($user->id, $days, $limit, $search);
            break;
            case 'units-endorsed':
                $criteria = Lantra::$app->results->getManagerUnitEndorsedResults($user->id, $days, $limit, $search);
                break;
            case 'modules-active':
                $criteria = Lantra::$app->results->getManagerModuleActiveResults($user->id, $days, $limit, $search);
            break;
            case 'modules-expiring':
                $criteria = Lantra::$app->results->getManagerModuleExpiringResults($user->id, $days, $limit, $search);
            break;
            case 'modules-completed':
                $criteria = Lantra::$app->results->getManagerModuleCompletedResults($user->id, $days, $limit, $search);
            break;
            case 'users':
                $criteria = Lantra::$app->users->getManagerUsers($user->id, $limit, $search);
            break;
        }
        if ($criteria) {
            return ($count) ? $criteria->count() : $criteria;
        }
        return null;
    }

    /**
     * Export a results report
     *
     * @param string $reportType
     * @param int $days
     * @param string $search
     * @throws mixed
     * @return string
    */
    public function exportReport($reportType = 'users', $days = 28, $search = '')
    {
        $data = [];
        if (false != $results = $this->managerReport($reportType, null, $days, $search, false)) {
            foreach ($results as $row) {
                if ($reportType == 'users') {
                    $data[] = [
                        $row->getFullName(),
                        $row->email,
                        $row->userTeam->one(),
                    ];
                }
                else {
                    $company = $row->author->userCompany->one();
                    $title = $row->title;
                    if ($row->type == 'unitResult') {
                        $title = $row->resultUnit->one()->title;
                    }
                    elseif ($row->type == 'moduleResult' && $row->resultModule->count()) {
                        $title = $row->resultModule->one()->title;
                    }
                    $data[] = [
                        $row->author->getFullName(),
                        $company ? $company->title : '~',
                        $title,
                        $row->postDate->format('d-m-Y'),
                        $row->expiryDate ? $row->expiryDate->format('d-m-Y') : '',
                    ];
                }
            }
        }
        $this->sendReport($reportType, $data);
    }

    /**
     * Get the individual company id
     *
     * @return int
     */
    public function individualCompanyId()
    {
        $company = Lantra::$app->users->getIndividualCompany();
        return ($company) ? $company->id : null;
    }

    /**
     * @param $moduleId
     * @param null $userId
     * @return null
     */
    public function currentModuleResult($moduleId, $userId = null)
    {
        if (false == $user = $this->getUser($userId)) {
            return null;
        }
        return Lantra::$app->results->getModuleResult($user->id, $moduleId, true);
    }

    /**
     * @param $moduleId
     * @param null $userId
     * @return null
     */
    public function allModuleResults($moduleId, $userId = null)
    {
        if (false == $user = $this->getUser($userId)) {
            return null;
        }
        return Lantra::$app->results->getUserModuleResults($moduleId, $user->id);
    }

    /**
     * @param $resultEntry
     * @return \lantra\sp\models\CyclePeriod
     */
    public function currentCycle($resultEntry)
    {
        return LantraHelper::getResultCycle($resultEntry, true);
    }

    /**
     * @param $resultEntry
     * @return \lantra\sp\models\CyclePeriod
     */
    public function resultCycle($resultEntry)
    {
        return LantraHelper::getResultCycle($resultEntry);
    }

    /**
     * @param $moduleEntry
     * @return array
     */
    public function allCycles($moduleEntry)
    {
        return LantraHelper::getModuleCycles($moduleEntry);
    }

    /**
     * @param $userId
     * @param $unitId
     * @param null $moduleResultId
     * @param int $limit
     * @return mixed
     */
    public function unitResultsQuery($userId, $unitId, $limit = 1, $moduleResultId = null)
    {
        return Lantra::$app->results->getUnitResultsQuery($userId, $unitId, $limit, $moduleResultId);
    }

    /**
     * @param $moduleId
     * @param null $userId
     * @return null
     */
    public function cycleModuleResults($moduleId, $userId = null)
    {
        if (false == $user = $this->getUser($userId)) {
            return null;
        }
        return Lantra::$app->cycles->getModuleCycleResults($moduleId, $user->id);
    }

    /**
     * @param $cycle
     * @param $moduleId
     * @param null $userId
     * @return null
     */
    public function cycleResult($cycle, $moduleId, $userId = null)
    {
        if (false == $user = $this->getUser($userId)) {
            return null;
        }
        return Lantra::$app->cycles->getCycleResult($cycle, $moduleId, $user->id);
    }

    /**
     * @param $moduleResult
     * @return string
     */
    public function remainingText($moduleResult)
    {
        return Lantra::$app->results->remainingText($moduleResult);
    }


    /**
     * @param $unitEntry
     * @param null $moduleEntry
     * @return float|int|null
     */
    public function unitPoints($unitEntry, $moduleEntry = null)
    {
        return Lantra::$app->results->getUnitPoints($unitEntry, $moduleEntry);
    }

    /**
     * Get report data
     *
     * @param $entryId
     * @return mixed
     */
    public function getReportData($entryId)
    {
        $reportEntry = Craft::$app->entries->getEntryById($entryId);
        return ($reportEntry) ? Lantra::$app->reports->getReportData($reportEntry) : null;
    }

    /**
     * Send the csv report to the browser
     *
     * @param $reportType
     * @param $data
     * @throws HttpException
     */
    private function sendReport($reportType, $data)
    {
        ob_start();
        $export = fopen('php://output', 'w');
        foreach ($data as $row) {
            fputcsv($export, $row);
        }
        fclose($export);
        $content = ob_get_clean();
        $content = str_replace("\n", "\r\n", $content);
        Craft::$app->request->sendFile('report-' . $reportType . '.csv', $content, array('forceDownload' => true, 'mimeType' => 'text/csv'));
    }

    /**
     * Get the user
     *
     * @param null $userId
     * @return UserModel
     */
    private function getUser($userId = null)
    {
        if (is_object($userId)) {
            return $userId;
        }
        elseif (is_null($userId)) {
            return $user = Craft::$app->getUser()->getIdentity();
        }
        else {
            return $user = Craft::$app->users->getUserById($userId);
        }
    }
}
