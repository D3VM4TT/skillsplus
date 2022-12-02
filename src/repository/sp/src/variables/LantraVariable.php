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
use craft\elements\Entry;

use craft\elements\User;
use craft\helpers\StringHelper;
use craft\helpers\UrlHelper;
use lantra\sp\Plugin as Lantra;
use lantra\sp\helpers\LantraHelper;
use lantra\sp\helpers\CycleHelper;
use lantra\sp\helpers\RecordHelper;
use lantra\sp\helpers\ReportHelper;
use lantra\sp\models\RecordItem;

use verbb\supertable\elements\SuperTableBlockElement;
use yii\web\ForbiddenHttpException;

class LantraVariable
{

    /**
     * @param null $userId
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     */
    public function syncUserPayments($userId = null)
    {
        $user = LantraHelper::getUser($userId);
        Lantra::$app->users->syncUserPayments($user);
    }

    /**
     * @param null $level
     * @return string
     */
    public function skillLevel($level = null, $endorsed = false, $upskiller = false)
    {
        if (!$level) {
            return '';
        }

        $return = '<span class="skill">';

        $title = $level->title . ($endorsed ? ' [endorsed]' : ' [not endorsed]');
        $class = 'sm-dot sm-' . ($endorsed ? 'endorsed' : 'unendorsed');
        $style = 'border-color:' . $level->skillLevelColour . ';background-color: ' . $level->skillLevelColour;
        $number = $level->skillLevelNumber !== null ? number_format($level->skillLevelNumber, 1) : '';

        $return .= '<span title="' . $title  . '" class="' . $class . '" style="' . $style . '">' . $number . '</span>';

        if ($upskiller) {
            $return .= '<span class="upskiller"><img src="/assets/img/upskiller.svg" title="Upskiller" /></span>';
        }

        $return .= '</span>';

        return $return;
    }

    /**
     * @param null $module
     */
    public function skillsMatrixIds($module = null, $unitGroupId = 'all', $unitId = 'all', $minSkillLevel = 'none', $maxSkillLevel = 'none', $status = 'all', $users = 'default')
    {
        return Lantra::$app->modules->skillsMatrixIds($module, $unitGroupId, $unitId, $minSkillLevel, $maxSkillLevel, $status, $users);
    }

    /**
     * @param $userId
     * @param $unitId
     * @param string $minSkillLevel
     * @param string $maxSkillLevel
     */
    public function getUserSkillResult($userId, $unitId, $minSkillLevel = 'none', $maxSkillLevel = 'none', $filterStatus = 'all')
    {
        return Lantra::$app->results->getUserSkillResult($userId, $unitId, $minSkillLevel, $maxSkillLevel, $filterStatus);
    }

    /**
     * @param $module
     * @param null $userId
     * @return mixed
     */
    public function isUserModule($module, $userId = null)
    {
        return Lantra::$app->modules->isUserModule($module, $this->getUser($userId));
    }

    /**
     * @return \lantra\spbase\models\Site
     */
    public function siteLicence($cache = true)
    {
        return Lantra::$app->spbase->getSite($cache);
    }

    /**
     * @param null $resultEntry
     * @return string|null
     */
    public function certificateUrl($resultEntry = null)
    {
        return LantraHelper::certificateUrl($resultEntry);
    }

    /**
     * @param null $url
     * @return string
     */
    public function returnRef($url = null)
    {
        return LantraHelper::returnRef($url);;
    }

    /**
     * @param $field
     * @param $type
     * @param int $limit
     * @return array
     */
    private function _customFields($field, $type, $limit = 0)
    {
        $fields = LantraHelper::setting($field, []);
        $return = [];
        foreach($fields as $row) {
            if ($limit && count($return) == $limit) {
                return $return;
            }
            if (isset($row[$type]) && $row[$type]) {
                $return[] = $row;
            }
        }
        return $return;
    }

    /**
     * @param $type
     * @param $limit
     * @return array
     */
    public function userCustomFields($type, $limit = 0)
    {
        return $this->_customFields('userEditCustomFields', $type, $limit);
    }

    /**
     * @param $type
     * @param $limit
     * @return array
     */
    public function userProfileFields($type, $limit = 0)
    {
        return $this->_customFields('userProfileFields', $type, $limit);
    }

    /**
     * @param $field
     * @param $type
     * @return bool
     */
    public function userProfileField($field, $type)
    {
        $field = LantraHelper::userProfileField($field);
        return $field ? $field[$type] : false;
    }

    /**
     * @param $field
     * @param string $default
     * @return string
     */
    public function userProfileLabel($field, $default = '')
    {
        $field = LantraHelper::userProfileField($field);
        return $field ? $field['label'] : $default;
    }

    /**
     * @param $type
     * @param $limit
     * @return array
     */
    public function productCustomFields($type, $limit = 0)
    {
        return $this->_customFields('productCustomFields', $type, $limit);
    }

    /**
     * @param $fields
     * @param $type
     * @return array
     */
    public function customFieldsByType($fields = [], $type)
    {
        $return = [];
        if (is_array($fields) && count($fields)) {
            foreach ($fields as $row) {
                if (isset($row[$type]) && $row[$type]) {
                    $return[] = $row;
                }
            }
        }
        return $return;
    }

    /**
     * @param $entry
     * @param $customName
     * @return mixed|null
     */
    public function customBlockValue($entry, $customName)
    {
        $customBlock = $entry->customFields->customName($customName)->one();
        return $customBlock ? $customBlock->customValue : null;
    }

    /**
     * @param $userId
     * @param $packageId
     * @return string
     */
    public function packageUrl($userId, $packageId = null)
    {
        return LantraHelper::packageUrl($userId, $packageId);
    }

    /**
     * @param null $userId
     * @return array
     */
    public function getUnpaidPackages($userId = null)
    {
        if (false == $user = $this->getUser($userId)) {
            return;
        }
        return Lantra::$app->packages->getUnpaidPackages($user);
    }

    /**
     * @param null $userId
     * @return array
     */
    public function getOptionalModuleGroups($userId = null)
    {
        if (false == $user = $this->getUser($userId)) {
            return;
        }
        return Lantra::$app->packages->getOptionalModuleGroups($user);
    }

    /**
     * @param null $userId
     * @return array
     */
    public function getResitModuleGroups($userId = null)
    {
        if (false == $user = $this->getUser($userId)) {
            return;
        }
        return Lantra::$app->packages->getResitModuleGroups($user);
    }

    /**
     * @param $user
     * @return null
     */
    public function membership($user = null)
    {
        return LantraHelper::getMembership($user);
    }

    /**
     * @param null $product
     * @param int $amount
     * @param string $label
     * @param string $return
     * @param array $custom
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @throws \yii\base\ErrorException
     * @throws \yii\base\Exception
     */
    public function payPalButton($product = null, $amount = 0, $label = 'Pay Now', $return = '', $custom = [])
    {
        return Lantra::$app->paypal->getButton($product, $amount, $label, $return, $custom);
    }

    /**
     * @param $userId
     * @param $amount
     * @param $reference
     * @param array $meta
     * @param string $label
     * @return \Psr\Http\Message\ResponseInterface|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function basePaypalButton($userId, $amount, $reference, $meta = [], $label = 'Pay Now')
    {
        $meta['reference'] = $reference;
        return Lantra::$app->spbase->getPaypalButton($userId, $amount, $meta, $label);
    }

    /**
     * @param null $handle
     * @return SuperTableBlockTypeModel|null
     */
    public function spBlockType($handle = null)
    {
        return LantraHelper::spBlockType($handle);
    }

    /**
     * @param int $userId
     * @param string $return
     * @return array
     */
    public function recordResults($userId = null, $return = 'results')
    {
        $user = (is_null($userId)) ? null : $this->getUser($userId);
        $results = [];
        foreach($user->userRole->all() as $jobRole) {
            $jobRoleResults = Lantra::$app->results->getJobRoleUserResults($jobRole->id, $user->id);
            $results = array_merge($results, $jobRoleResults);
        }
        if ($user->record->packages()) {
            foreach ($user->record->packages() as $packageItem) {
                $packageResults = Lantra::$app->results->getPackageUserResults($packageItem->elementId, $user->id, 'both');
                $results = array_merge($results, $packageResults);
            }
        }
        if ($return == 'results') {
            return $results;
        }
        $ids = [];
        foreach ($results as $result) {
            $ids[] = $result->id;
        }
        return $ids;
    }

    /**

    /**
     * @param SuperTableBlockElement $step
     * @return \craft\elements\db\ElementQueryInterface|\craft\elements\db\UserQuery|null
     */
    public function getStepManagers(SuperTableBlockElement $step)
    {
        return Lantra::$app->packages->getStepManagers($step);
    }

    /**
     * @param null $userId
     * @return null
     */
    public function getUserPackages($userId = null)
    {
        $user = (is_null($userId)) ? null : $this->getUser($userId);
        return Lantra::$app->packages->getUserPackages($user);
    }

    /**
     * @param Entry|null $package
     * @param $moduleGroupId
     * @return null
     */
    public function getModuleGroupRow($package = null, $moduleGroupId)
    {
        return Lantra::$app->packages->getModuleGroupRow($package, $moduleGroupId);
    }

    /**
     * @param Entry $taskbook
     * @param string $type
     * @return mixed
     */
    public function getModuleGroupCost(Entry $taskbook, $type = 'optional')
    {
        return Lantra::$app->packages->getModuleGroupCost($taskbook, $type);
    }

    /**
     * @param Entry $package
     * @param $complete
     * @return array
     */
    public function countPackageUnits(Entry $package, $complete = false, $status = 'draft')
    {
        return Lantra::$app->packages->countPackageUnits($package,  $complete, $status);
    }

    /**
     * @param Entry $package
     * @return array
     */
    public function getPackageModules(Entry $package)
    {
        return Lantra::$app->packages->getPackageModules($package);
    }

    /**
     * @param $packageId
     * @param null $userId
     * @return Entry|null
     */
    public function getUserPackage($packageId, $userId = null)
    {
        $user = (is_null($userId)) ? null : $this->getUser($userId);
        return Lantra::$app->packages->getUserPackage($packageId, $user);
    }

    /**
     * @return array
     */
    public function taskbookLevels()
    {
        $levels = self::setting('taskbookLevelLabels');
        return $levels && is_array($levels) ? $levels : [];
    }

    /**
     * @return string
     */
    public function taskbookLevel($level)
    {
        $levels = $this->taskbookLevels();
        foreach($levels as $l) {
            if ($l['value'] == $level) {
                return $l['label'];
            }
        }
        return $level;
    }

    /**
     * @param RecordItem $recordItem
     * @return bool
     */
    public function totalUnits(RecordItem $recordItem)
    {
        return RecordHelper::totalUnits($recordItem);
    }

    /**
     * @param RecordItem $recordItem
     * @return bool
     */
    public function totalComplete($recordItem, $userId = null, $packageId = null, $moduleGroupId = null)
    {
        return RecordHelper::totalComplete($recordItem, $this->getUser($userId), null, $packageId, $moduleGroupId);
    }

    /**
     * @param RecordItem $recordItem
     * @return bool
     */
    public function totalEndorsed($recordItem, $userId = null, $packageId = null, $moduleGroupId = null)
    {
        return RecordHelper::totalEndorsed($recordItem, $this->getUser($userId), $packageId, $moduleGroupId);
    }

    /**
     * @param RecordItem $recordItem
     * @return bool
     */
    public function totalPending($recordItem, $userId = null, $packageId = null, $moduleGroupId = null)
    {
        return RecordHelper::totalPending($recordItem, $this->getUser($userId), $packageId, $moduleGroupId);
    }

    /**
     * @param RecordItem $recordItem
     * @return bool
     */
    public function hasAssessmentUnit($recordItem)
    {
        return RecordHelper::hasAssessmentUnit($recordItem);
    }

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
     * @param null $return
     * @return mixed
     */
    public function settingReports($return = null)
    {
        $reports = Lantra::$app->settings->getSetting('reports');
        $active = [];
        foreach($reports as $k => $report) {
            if ($report['active'] && $this->canAccessReport($report)) {
                $active[$k] = $report;
            }
        }
        ## return bool
        if (is_null($return)) {
            return count($active) > 0;
        }
        ## return array
        if ($return == 'array') {
            return $active;
        }
        ## return specific report
        return isset($active[$return]) ? $active[$return] : null;
    }

    /**
     * @param null $report
     * @return bool
     */
    private function canAccessReport($report = null)
    {
        $user = $this->getUser();
        if ($user->admin || $user->isInGroup('schemeManagers')) {
            return true;
        }
        if ($user->isInGroup('companyManagers') && $report['group'] == 'companyManagers') {
            return true;
        }
        if ($report['group'] == 'userRole' && is_countable($report['roles']) && count($report['roles'])) {
            $reportRoleIds = [];
            foreach ($report['roles'] as $category) {
                $reportRoleIds[] = $category->id;
            }
            $userRoleIds = $user->userRole->ids();
            return count(array_intersect($reportRoleIds, $userRoleIds));
        }
        return false;
    }

    /**
     * @param $reportEntry
     * @return bool
     */
    public function isStandardReport($reportEntry)
    {
        return ReportHelper::isStandardReport($reportEntry);
    }

    /**
     * @param $reportType
     * @param null $key
     * @return null
     */
    public function reportTypeSetting($reportType, $key = null)
    {
        return ReportHelper::reportTypeSetting($reportType, $key);
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
     * @param $userId
     * @return int|null
     * @throws \craft\errors\AssetConflictException
     * @throws \craft\errors\VolumeObjectExistsException
     */
    public function evidenceFolderId($userId = null)
    {
        if (false == $user = $this->getUser($userId)) {
            return;
        }
        $folder = LantraHelper::userEvidenceFolder($user);
        return $folder && isset($folder->id) ? $folder->id : null;
    }

    /**
     * @param string $kind
     * @return mixed
     */
    public function assetIcon($kind = '')
    {
        return LantraHelper::assetIcon($kind);
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
     * @param $search
     * @param $limit
     * @param $order
     * @return mixed
     */
    public function productCriteria($search, $limit, $order)
    {
        return Lantra::$app->products->productCriteria($search, $limit, $order);
    }

    /**
     * @param $productId
     * @return array|bool|\craft\base\ElementInterface[]|Entry[]|int|string|null
     */
    public function productResultsCriteria($productId)
    {
        return Lantra::$app->results->getProductResultsCriteria($productId);
    }

    /**
     * @param $date
     * @return mixed|string
     */
    public function productExpiryStatus($date)
    {
        return ReportHelper::expiryStatus($date);
    }

    /**
     * @param $search
     * @param null $limit
     * @param string $order
     * @param null $managerId
     * @param null $filterBy
     * @param string $filterValue
     * @return \craft\elements\db\ElementQueryInterface|\craft\elements\db\EntryQuery
     */
    public function packagesCriteria($search, $limit = null, $order = 'lastName',  $filterBy = null, $filterValue = 'all', $dateFrom = null, $dateTo = null, $companyId = null, $managerId = null)
    {
        return Lantra::$app->packages->packagesCriteria($search, $limit, $order, $filterBy, $filterValue, $dateFrom, $dateTo, $companyId, $this->getUser($managerId));
    }

    /**
     * @param null $managerId
     * @param null $filterBy
     * @param string $filterValue
     * @return int|string
     */
    public function packagesCount($filterBy = null, $filterValue = 'all', $managerId = null)
    {
        $criteria = $this->packagesCriteria('', null, 'lastName', $filterBy, $filterValue, null, null, null, $this->getUser($managerId));
        return $criteria ? $criteria->count() : 0;
    }

    /**
     * @param $userId
     * @param $taskbookId
     * @return bool
     */
    public function packageExists($userId, $taskbookId)
    {
        return Lantra::$app->packages->userPackageExists($this->getUser($userId), $taskbookId);
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
        $user = $this->getUser($userId);
        return Lantra::$app->reports->getStandardReportData($reportType, $user->id, $days, $search, $limit, $count);
    }

    /**
     * @param $search
     * @param $limit
     * @param $order
     * @param bool $automated
     * @return \craft\elements\db\ElementQueryInterface|\craft\elements\db\EntryQuery|null
     * @throws \yii\db\Exception
     */
    public function reportCriteria($search, $limit, $order, $automated = false)
    {
        return Lantra::$app->reports->reportCriteria($search, $limit, $order, $automated);
    }

    /**
     * @param $reportEntry
     * @return \lantra\sp\services\ElementCriteriaModel|null
     */
    public function reportDataCriteria($reportEntry, $limit)
    {
        return Lantra::$app->reports->reportDataCriteria($reportEntry, $limit);
    }

    /**
     * @param $reportEntry
     * @param bool $html
     * @return string
     */
    public function reportHeader($reportEntry, $html = true)
    {
        return ReportHelper::reportHeader($reportEntry, $html);
    }

    /**
     * @param $reportEntry
     * @param bool $html
     * @return string
     */
    public function reportRow($reportEntry, $row, $html = true)
    {
        return ReportHelper::reportRow($reportEntry, $row, $html);
    }

    /**
     * @param $reportEntry
     * @return string
     */
    public function reportTypeLabel($reportEntry)
    {
        if ($reportEntry->reportType == 'standardResults') {
            return 'Results (' . ($reportEntry->reportResultStandardType == 'endorsed' ? 'Endorsed' : 'Expiring') . ')';
        }
        elseif ($reportEntry->reportType == 'standardCpd') {
            return 'CPD';
        }
        return ucwords(str_replace('standard', '', $reportEntry->reportType));
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

        if (in_array($task, ['editCompanies', 'editTeams', 'editModules', 'editReports', 'editTaskbooks', 'editProducts'])){
            $section = strtolower(ltrim($task, 'edit'));
            $permission = 'editEntries:' . LantraHelper::sectionUid($section);
        }

        if ($task == 'editUsers') {
            $permission = 'editUsers';
        }

        if ($task == 'editRoles') {
            $permission = 'editCategories:' . LantraHelper::groupUid('roles');
        }

        return $permission ? $user->can($permission) : false;
    }

    /**
     * @param null $userId
     * @return bool
     */
    public function isLantraAdmin($userId = null)
    {
        if (false == $user = $this->getUser($userId)) {
            return false;
        }
        return Lantra::$app->users->isLantraAdmin($user);
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
     * Check whether this user can submit external reviews
     *
     * @param null $subordinateId
     * @param bool $managerId
     * @return bool
     */
    public function canExternal($subordinateId = null, $managerId = null)
    {
        $manager = (is_null($managerId)) ? null : $this->getUser($managerId);
        return Lantra::$app->packages->canExternal($subordinateId, $manager);
    }

    /**
     * @param null $userId
     * @return bool
     */
    public function hasDashboard($userId = null)
    {
        if (false == $user = $this->getUser($userId)) {
            return false;
        }
        return Lantra::$app->users->hasDashboard($user);
    }

    /**
     * @param null $subordinateId
     * @param null $managerId
     * @param bool $includeHierarchy
     * @return bool
     * @throws \Exception
     */
    public function isManager($subordinateId = null, $managerId = null, $includeHierarchy = true)
    {
        $manager = (is_null($managerId)) ? null : $this->getUser($managerId);
        return Lantra::$app->users->isManager($subordinateId, $manager, $includeHierarchy);
    }

    /**
     * @param null $userId
     * @return bool
     */
    public function isExternal($userId = null)
    {
        $user = (is_null($userId)) ? null : $this->getUser($userId);
        return Lantra::$app->users->isExternal($user);
    }

    /**
     * @param null $userId
     * @return array
     */
    public function packageTypes($userId = null)
    {
        if (false == $user = $this->getUser($userId)) {
            return [];
        }
        return Lantra::$app->packages->packageTypes($user);
    }

    /**
     * @param null $subordinateId
     * @param null $managerId
     * @return bool
     */
    public function isPackageManager($subordinateId = null, $managerId = null)
    {
        $manager = (is_null($managerId)) ? null : $this->getUser($managerId);
        return Lantra::$app->packages->isPackageManager($subordinateId, $manager);
    }

    /**
     * Check whether this user can assess the subordinate
     *
     * @param $package
     * @param bool $assessorId
     * @param bool $includeAdmin
     * @return bool
     */
    public function isAssessor($package = null, $assessorId = null, $includeAdmin = true)
    {
        $assessor = (is_null($assessorId)) ? null : $this->getUser($assessorId);
        return Lantra::$app->packages->isAssessor($package, $assessor, $includeAdmin);
    }

    /**
     * Check whether this user can review the subordinate
     *
     * @param $package
     * @param int $reviewerId
     * @param bool $includeAdmin
     * @return bool
     */
    public function isReviewer($package, $reviewerId = null, $includeAdmin = true)
    {
        $reviewer = (is_null($reviewerId)) ? null : $this->getUser($reviewerId);
        return Lantra::$app->packages->isReviewer($package, $reviewer, $includeAdmin);
    }

    /**
     * Check whether this user can complete the subordinate
     *
     * @param $package
     * @param int $reviewerId
     * @param bool $includeAdmin
     * @return bool
     */
    public function isCompleter($package, $reviewerId = null, $includeAdmin = true)
    {
        $reviewer = (is_null($reviewerId)) ? null : $this->getUser($reviewerId);
        return Lantra::$app->packages->isCompleter($package, $reviewer, $includeAdmin);
    }

    /**
     * @param null $subordinateId
     * @param null $managerId
     * @return bool
     */
    public function isExternalReviewer($subordinateId = null, $managerId = null)
    {
        $manager = (is_null($managerId)) ? null : $this->getUser($managerId);
        return Lantra::$app->packages->isExternalReviewer($subordinateId, $manager);
    }

    /**
     * @param $package
     * @param null $assessorId
     * @return bool
     * @throws \Exception
     */
    public function canAssess($package, $assessorId = null)
    {
        if (false == $assessor = $this->getUser($assessorId)) {
                return false;
        }
        ## you can't mark your own homework...!
        if (!$assessor->admin && $assessor->id == $package->author->id) {
            return false;
        }
        return $this->isAssessor($package, $assessor);
    }

    /**
     * @param $resultEntry
     * @param null $managerId
     * @return bool
     * @throws \Exception
     */
    public function canEndorse($resultEntry, $managerId = null) {
        if (false == $manager = $this->getUser($managerId)) {
            return false;
        }
        ## you can't mark your own homework...!
        if ($manager->id == $resultEntry->author->id) {
            return false;
        }
        return $this->isManager($resultEntry->author->id, $manager);
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
        /*
        if (is_null($resultEntry)) {
            $resultEntry = Lantra::$app->results->getUnitResult($user->id, $unitEntry->id);
        }
        */
        return Lantra::$app->attempts->remainingAttempts($unitEntry, $resultEntry, $user->id);
    }

    /**
     * Display remaining attempts
     *
     * @param Entry $unitEntry
     * @param Entry $resultEntry
     * @param null $userId
     * @return int|string
     */
    public function assessmentScore($unitEntry, $resultEntry = null, $userId = null)
    {
        if (false == $user = $this->getUser($userId)) {
            return 0;
        }
        /*
        if (is_null($resultEntry)) {
            $resultEntry = Lantra::$app->results->getUnitResult($user->id, $unitEntry->id);
        }
        */
        if (is_null($resultEntry)) {
            return '~';
        }
        return $resultEntry->resultScore .'% (' . ($resultEntry->resultScore >= $unitEntry->testPassPercent ? 'Pass' : 'Fail') . ')';
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
     * Get manager reports
     *
     * @param null $userId
     * @return BaseElementModel|null
     * @throws Exception
     */
    public function managerReports($userId = null)
    {
        if (false == $user = $this->getUser($userId)) {
            return null;
        }
        return Lantra::$app->reports->getManagerReports($user);
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
    public function companyModuleResult($moduleId, $userId = null)
    {
        if (false == $user = $this->getUser($userId)) {
            return null;
        }
        return Lantra::$app->results->getUserCompanyModuleResult($user, $moduleId, true);
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
        return CycleHelper::getResultCycle($resultEntry, true);
    }

    /**
     * @param $resultEntry
     * @return \lantra\sp\models\CyclePeriod
     */
    public function resultCycle($resultEntry)
    {
        return CycleHelper::getResultCycle($resultEntry);
    }

    /**
     * @param $moduleEntry
     * @return array
     */
    public function allCycles($moduleEntry)
    {
        return CycleHelper::getModuleCycles($moduleEntry);
    }

    /**
     * @param $userId
     * @param $unitId
     * @param $cycle
     * @param $moduleResultId
     * @return \craft\elements\db\ElementQueryInterface|\craft\elements\db\EntryQuery|null
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     */
    public function recurringResultsQuery($userId, $unitId, $cycle, $moduleResultId, $companyId = null)
    {
        if (!$cycle) {
            return null;
        }
        return Lantra::$app->results->getRecurringResultsQuery($userId, $unitId, $cycle, $moduleResultId, $companyId);
    }

    /**
     * @param $userId
     * @param $unitId
     * @param null $companyId
     * @param null $packageId
     * @param null $moduleGroupId
     */
    public function getUnitResult($userId, $unitId, $cycleCode = null, $moduleResultId = null, $companyId = null, $packageId = null, $moduleGroupId = null, $create = false)
    {
        ## get or create unit result
        $unitResult = Lantra::$app->results->getUnitResult($userId, $unitId, $cycleCode, $moduleResultId, $companyId, $packageId, $moduleGroupId);
        if (!$unitResult && $create) {
            $unitResult = Lantra::$app->results->createUnitResult($userId, $unitId, $moduleResultId, null, null, $companyId, $packageId, $moduleGroupId);
        }
        return $unitResult;
    }

    /**
     * @param $userId
     * @param $unitId
     * @param null $moduleResultId
     * @param $companyId
     * @param int $limit
     * @return mixed
     */
    public function unitResultsQuery($userId, $unitId, $limit = 1, $moduleResultId = null, $companyId = null, $packageId = null, $moduleGroupId = null)
    {
        return Lantra::$app->results->getUnitResultsQuery($userId, $unitId, $limit, $moduleResultId, $companyId, $packageId, $moduleGroupId);
    }

    /**
     * @param null $moduleResultId
     * @param $status
     * @param $count
     * @return \craft\elements\db\ElementQueryInterface|\craft\elements\db\EntryQuery
     */
    public function getModuleResultUnitResults($moduleResultId = null, $status = null, $count = false)
    {
        return Lantra::$app->results->getModuleResultResults($moduleResultId, $status, $count, 'unitResult');
    }

    /**
     * @param null $moduleResultId
     * @param $status
     * @param $count
     * @return \craft\elements\db\ElementQueryInterface|\craft\elements\db\EntryQuery
     */
    public function getModuleResultUserResults($moduleResultId = null, $status = null, $count = false)
    {
        return Lantra::$app->results->getModuleResultResults($moduleResultId, $status, $count, 'userResult');
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
        return Lantra::$app->cycles->getCycleResult($user->id, $moduleId, $cycle);
    }

    /**
     * @param $moduleResult
     * @param $code
     */
    public function unitResultRecurringCycle($moduleResult, $code, $type)
    {
        $cyclePeriod = CycleHelper::getUnitResultRecurringCycle($moduleResult, $code, $type);
        return $cyclePeriod;
    }

    /**
     * @param $moduleResult
     * @return array
     */
    public function remaining($moduleResult)
    {
        return Lantra::$app->results->remaining($moduleResult);
    }

    /**
     * @param $moduleResult
     * @param $userId
     * @return int
     */
    public function pending($moduleResult, $userId = null)
    {
        if (false == $user = $this->getUser($userId)) {
            return null;
        }
        return Lantra::$app->results->pending($moduleResult, $user->id);
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
     * @param null $userId
     * @return User|null
     */
    public function activeUser($userId = null)
    {
        if (false == $user = $this->getUser($userId)) {
            return null;
        }
        return Lantra::$app->users->getActiveUser($user);
    }

    /**
     * Get the user
     *
     * @param null $userId
     * @return User
     */
    private function getUser($userId = null)
    {
        return LantraHelper::getUser($userId);
    }
}
