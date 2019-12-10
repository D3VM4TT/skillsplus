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

class Result extends Component
{

    /**
     * @param Entry $entry
     */
    public function onBeforeSaveResult(Entry $entry) {
        // set comment
        $comment = craft()->request->getPost('comment');
        if ($comment) {
            unset($_POST['comment']);
            $resultComments = Lantra::$app->result->addComment($entry, $comment);
            $event->params['entry']->setContentFromPost(array('resultComments' => $resultComments));
        }
        // set custom author
        $authorId = craft()->request->getPost('authorId');
        if ($entry->type == 'userResult' && $authorId) {
            $entry->authorId = $authorId;
        }
        $fields = craft()->request->getPost('fields');
        $resultUnitId = isset($fields['resultUnit']) && $fields['resultUnit'] ? $fields['resultUnit'] : null;
        // set result title
        if ($entry->type == 'userResult' && $resultUnitId) {
            $unitEntry = craft()->entries->getEntryById($resultUnitId);
            if ($unitEntry) {
                $entry->getContent()->title = $unitEntry->title;
            }
        }
        // check endorsed change
        $oldEntry = craft()->entries->getEntryById($entry->id);
        $currentUser = craft()->userSession->getUser();
        // Auto endorse
        if (! craft()->request->isCpRequest() && $entry->resultStatus != 'draft' && $entry->authorId != $currentUser->id && Lantra::$app->user->isManager($entry->authorId)) {
            $entry->setContentFromPost(['resultStatus' => 'endorsed']);
            if (!$oldEntry) {
                $entry->setContentFromPost(['resultEndorsedDate' => DateTimeHelper::currentTimeForDb()]);
                $entry->setContentFromPost(['resultEndorsedUser' => [$currentUser->id]]);
            }
        }
        // force clear endorsed date if pending
        if ($entry->resultStatus == 'pending') {
            $entry->setContentFromPost(['resultEndorsedDate' => null]);
        } elseif ($oldEntry && $oldEntry->resultStatus == 'pending' && $entry->resultStatus == 'endorsed') {
            $entry->setContentFromPost(['resultEndorsedDate' => DateTimeHelper::currentTimeForDb()]);
            $entry->setContentFromPost(['resultEndorsedUser' => [$currentUser->id]]);
        }
        // check change from draft to pending
        if ($oldEntry && $oldEntry->resultStatus == 'draft' && $entry->resultStatus == 'pending') {
            // send notification
            if (Lantra::$app->result->notifyManagerEndorsementResult($entry)) {
                craft()->lantra_notify->sendManagerEndorsementResult($entry);
            }
        }

        $dateFormat = 'Y-m-d H:i:s';
        // set a user start date
        $userStartDate = craft()->request->getPost('userStartDate');
        if ($userStartDate && false != $date = DateTime::createFromFormat($dateFormat, $userStartDate)) {
            $userStartDate = $date->getTimestamp();
        }
        $entry->setContentFromPost(['resultStartDate' => $userStartDate]);
        // set a user finish date
        $userFinishDate = craft()->request->getPost('userFinishDate');
        if ($userFinishDate && false != $date = DateTime::createFromFormat($dateFormat, $userFinishDate)) {
            $userFinishDate = $date->getTimestamp();
        }
        $entry->setContentFromPost(['resultFinishDate' => $userFinishDate]);
        // validate dates
        $userExpiryDate = craft()->request->getPost('userExpiryDate');
        if ($userExpiryDate && false != $date = DateTime::createFromFormat($dateFormat, $userExpiryDate)) {
            $userExpiryDate = $date->getTimestamp();
            $entry->expiryDate = $date->getTimestamp();
        }
        if ($userStartDate && $userFinishDate && $userStartDate > $userFinishDate) {
            $entry->addError('resultStartDate', 'Start date cannot be later than finish date.');
            $event->performAction = false;
        }
        if ($userStartDate && $userExpiryDate && $userStartDate > $userExpiryDate) {
            $entry->addError('resultStartDate', 'Start date cannot be later than expiry date.');
            $event->performAction = false;
        }
        if ($userFinishDate && $userExpiryDate && $userFinishDate > $userExpiryDate) {
            $entry->addError('resultFinishDate', 'Finish date cannot be later than expiry date.');
            $event->performAction = false;
        }
        if ($event->performAction == false) {
            craft()->urlManager->setRouteVariables(array(
                'resultEntry'    => $entry
            ));
        }

    }

    // @todo move ids to config?
    private $sectionIdResults = 10;
    private $typeIdUnitResult = 10;
    private $typeIdModuleResult = 14;

    /**
     * @param $entry
     * @param $comment
     * @param null $userId
     * @return array
     */
    function addComment($entry, $comment, $userId = null) {

        if (is_null($userId)) {
            $userId = craft()->userSession->getUser()->id;
        }
        $field = craft()->fields->getFieldByHandle('resultComments');
        $blockTypes = craft()->superTable->getBlockTypesByFieldId($field->id);
        $blockType = $blockTypes[0];
        // not sure why we have to run this loop...
        $tableData = [];
        foreach($entry->resultComments as $key => $row) {
            $tableData[$key] =  [
                'type' => $blockType->id,
                'enabled' => true,
                'fields' => [
                    'user' => [$row->user->first()->id],
                    'date' => $row->date->getTimestamp(),
                    'comment' => $row->comment,
                    'read' => $row->read
                ]
            ];
        }
        $tableData['new1'] = [
            'type' => $blockType->id,
            'enabled' => true,
            'fields' => [
                'user' => [$userId],
                'date' => time(),
                'comment' => $comment,
                'read' => false
            ]
        ];
        craft()->lantra_notify->sendCommentUpdate($entry, $comment, $userId);
        return $tableData;
    }

    /**
     * @param $comment
     * @param $userId
     * @throws \Exception
     */
    function readComment($comment, $userId) {
        // userId of result
        $resultAuthorId = $comment->getOwner()->author->id;
        $commentAuthorId = $comment->user->first()->id;
        if (($resultAuthorId == $userId && $commentAuthorId != $userId) || ($resultAuthorId != $userId && $commentAuthorId == $resultAuthorId)) {
            $comment->setContent(['read' => true]);
            craft()->content->saveContent($comment, false);
        }
    }

    /**
     * @param $result
     * @return int
     */
    function unreadComments($result, $userId) {
        $unread = 0;
        foreach($result->resultComments as $comment) {
            $commentAuthorId = $comment->user->first()->id;
            if ($commentAuthorId != $userId && ! $comment->read) {
                $unread++;
            }
        }
        return $unread;
    }

    /**
     * Get a unit result entry
     *
     * @param $userId
     * @param $unitId
     * @return null
     * @throws Mixed
     */
    function getUnitResult($userId, $unitId) {
        $criteria = craft()->elements->getCriteria(ElementType::Entry);
        $criteria->section = 'results';
        $criteria->type = 'unitResult';
        $criteria->limit = 1;
        $criteria->authorId = $userId;
        $criteria->relatedTo = ['targetElement' => $unitId, 'field' => 'resultUnit'];
        return $criteria->first();
    }

    /**
     * Get a module result entry
     *
     * @param $userId
     * @param $moduleId
     * @return null
     * @throws Mixed
     */
    function getModuleResult($userId, $moduleId) {
        $criteria = craft()->elements->getCriteria(ElementType::Entry);
        $criteria->section = 'results';
        $criteria->type = 'moduleResult';
        $criteria->limit = 1;
        $criteria->authorId = $userId;
        $criteria->relatedTo = ['targetElement' => $moduleId, 'field' => 'resultModule'];
        return $criteria->first();
    }

    /**
     * Save a test attempt
     *
     * @param $attemptEntry
     * @return null
     * @throws Mixed
     */
    function saveAttemptResult($attemptEntry) {
        $attemptEntry = craft()->entries->getEntryById($attemptEntry->id);
        $unitEntry = $attemptEntry->attemptUnit->first();
        // author sent from form
        $authorId = craft()->request->getPost('authorId');
        if ($authorId && false != $user = craft()->users->getUserById($authorId)) {
            $attemptEntry->authorId = $user->id;
            $attemptEntry->getContent()->title = '[unit ' . $unitEntry->id . '] ' . $user->getFullName();
            craft()->content->saveContent($attemptEntry, false);
        }
        $total = count($attemptEntry->attemptAnswers);
        $correct = 0;
        // loop through answers and count correct
        foreach ($attemptEntry->attemptAnswers as $answerBlock) {
            if ($answerBlock->correct) {
                $correct++;
            }
        }
        // calculate percentage
        $score = round($correct / $total * 100);
        // passed if greater than unit setting
        $passed = $score >= $unitEntry->getContent()->testPassPercent;
        $resultScore = $score;
        // does a result exist?
        if (false == $resultEntry = $this->getUnitResult($attemptEntry->authorId, $unitEntry->id)) {
            $resultEntry = new EntryModel();
            $resultEntry->sectionId = $this->sectionIdResults;
            $resultEntry->typeId = $this->typeIdUnitResult;
            $resultEntry->enabled = true;
            $resultEntry->authorId = $attemptEntry->authorId;
            $resultAttempts = array($attemptEntry->id);
            $resultStatus = $passed ? 'endorsed' : 'active';
        }
        else {
            // append new result attempt
            $resultAttempts = array_merge($resultEntry->resultAttempts->ids(), array($attemptEntry->id));
            // only change if better than previous
            $resultStatus = $resultEntry->resultStatus;
            $resultScore = $resultEntry->resultScore;
            if ($score > $resultEntry->resultScore) {
                $resultStatus = $passed ? 'endorsed' : 'active';
                $resultScore = $score;
            }
        }
        $resultEntry->setContentFromPost([
            'resultUnit' => array($unitEntry->id),
            'resultStatus' => $resultStatus,
            'resultAttempts' => $resultAttempts,
            'resultScore' => $resultScore
        ]);
        if ($passed) {
            $resultEntry->setContentFromPost([
                'resultEndorsedDate' => time()
            ]);
        }
        // @todo error reporting?
        if ( ! craft()->entries->saveEntry($resultEntry)) {
            return;
        }
        return;
    }

    /**
     * Handle new unit and user results
     *
     * @param $resultEntry
     * @return null
     * @throws null
     */
    function saveNewResult($resultEntry) {
        $saveContent = false;
        $unitEntry = $resultEntry->resultUnit->first();
        $userId = craft()->userSession->getId();
        if ($resultEntry->type == 'unitResult' || $resultEntry->type == 'userResult') {
            if ( ! $resultEntry->resultOwner) {
                $resultEntry->setContentFromPost(['resultOwner' => [$userId]]);
                $saveContent = true;
            }
            // copy manager endorsement level from unit for submitted evidence
            if ($resultEntry->resultEvidence && $resultEntry->type == 'unitResult') {
                $resultEntry->setContentFromPost(['unitEndorsementManagerLevel' => $unitEntry->unitEndorsementManagerLevel]);
                $saveContent = true;
            }
            // set author
            $author = null;
            $authorId = craft()->request->getPost('author');
            if (is_array($authorId)) {
                $author = craft()->users->getUserById($authorId[0]);
            }
            //  (manager submitting on behalf of user)
            if ($author && $author->id != $userId) {
                // auto endorse
                if ($resultEntry->resultStatus == 'endorsed' && (
                    $resultEntry->type == 'userResult' || ($resultEntry->resultEvidence && $resultEntry->type == 'unitResult'))) {
                    $resultEntry->setContentFromPost(['resultEndorsedDate' => DateTimeHelper::currentTimeForDb()]);
                    $resultEntry->setContentFromPost(['resultEndorsedUser' => [$userId]]);
                    $saveContent = true;
                }
            }
            // make sure title is correct
            if ($author && $resultEntry->type == 'unitResult' && $resultEntry->resultEvidence)
            {
                $resultEntry->getContent()->title = '[unit ' . $unitEntry->id . '] ' . $author->firstName . ' ' . $author->lastName;
                $saveContent = true;
            }
            // send notification
            if ($this->notifyManagerEndorsementResult($resultEntry)){
                craft()->lantra_notify->sendManagerEndorsementResult($resultEntry);
            }
        }
        if ($saveContent) {
            craft()->entries->saveEntry($resultEntry, false);
        }
    }

    /**
     * Check whether to send the endorsement notification (can be disabled using lantra settings)
     *
     * @param $resultEntry
     * @return bool
     */
    function notifyManagerEndorsementResult($resultEntry) {
        if ($resultEntry->resultStatus != 'pending') {
            return false;
        }
        if ($resultEntry->type == 'userResult') {
            return true;
        }
        if ($resultEntry->resultEvidence && $resultEntry->type == 'unitResult'){
            return true;
        }
        return false;
    }

    /**
     * Check whether a user has any remaining attempts
     *
     * @param $resultEntry
     * @return null
     * @throws null
     */
    function checkRemainingAttempts($resultEntry) {
        $resultUnitEntry = $resultEntry->resultUnit->first();
        $totalAttempts = $resultEntry->resultAttempts->total();
        if ($resultUnitEntry->resultStatus != 'endorsed' && $resultUnitEntry->testMaxAttempts && ($totalAttempts >= $resultUnitEntry->testMaxAttempts)) {
            $this->blockResult($resultEntry);
        }
    }

    /**
     * Set a unitResult resultStatus
     *
     * @param $resultEntry
     * @param $resultStatus
     * @return null
     * @throws null
     */
    function setResultStatus($resultEntry, $resultStatus) {
        // only continue if status has changed
        if ($resultEntry->resultStatus == $resultStatus) {
            return;
        }
        $resultEntry->setContentFromPost(['resultStatus' => $resultStatus]);
        // bypass save entry to stop callback loop
        if ( ! craft()->content->saveContent($resultEntry, false)) {
            return;
        }
        return;
    }

    /**
     * Set a unitResult resultStatus to blocked
     *
     * @param $resultEntry
     * @return null
     * @throws null
     */
    function blockResult($resultEntry) {
        $this->setResultStatus($resultEntry,'blocked');
        craft()->lantra_notify->sendManagerBlockedResult($resultEntry);
    }

    /**
     * Unlink unitResult attempts and set resultStatus to active
     *
     * @param $resultEntry
     * @return null
     * @throws null
     */
    function unblockResult($resultEntry) {
        $resultEntry->setContentFromPost([
            'resultAttempts' => [],
            'resultScore' => 0
        ]);
        $this->setResultStatus($resultEntry,'active');
        craft()->entries->saveEntry($resultEntry);
    }

    /**
     * Check whether a user result has completed a module
     *
     * @param $resultEntry
     * @return null
     * @throws null
     */
    function checkUserResult($resultEntry) {
        // the related module id
        $resultModuleEntry = $resultEntry->resultModule->first();
        if ( ! $resultModuleEntry || ! $resultEntry->resultValue) {
            return;
        }
        // check the moduleResult
        $user = $resultEntry->author;
        $this->checkModuleResult($resultModuleEntry, $user->id);
    }

    /**
     * Check whether a unit result has completed a module
     *
     * @param $resultEntry
     * @return null
     * @throws null
     */
    function checkUnitResult($resultEntry) {
        // the related unit id
        $resultUnitEntry = $resultEntry->resultUnit->first();
        if ( ! $resultUnitEntry) {
            return;
        };
        // get the user job roles
        $user = $resultEntry->author;
        $jobRoles = $user->userRole;
        if ( ! $jobRoles->total()) {
            return;
        }
        // get all modules related to their job roles
        $criteria = craft()->elements->getCriteria(ElementType::Entry);
        $criteria->section = 'modules';
        $criteria->limit = null;
        $criteria->relatedTo = ['targetElement' => $jobRoles, 'field' => 'moduleRoles'];
        $moduleEntries = $criteria->find();
        // search for the relevant module (this unit may be part of multiple modules)
        foreach ($moduleEntries as $moduleEntry) {
            $unitIds = $this->getModuleUnitIds($moduleEntry);
            if (in_array($resultUnitEntry->id, $unitIds)) {
                $this->checkModuleResult($moduleEntry, $user->id);
            }
        }
        return;
    }

    /**
     * Check whether to award the module result
     *
     * @param $moduleEntry
     * @param $userId
     * @return null
     * @throws Exception
     */
    function checkModuleResult($moduleEntry, $userId) {
        $unitResultEntries = $this->getModuleUnitResults($moduleEntry, $userId);
        $userResultEntries = $this->getModuleUserResults($moduleEntry, $userId);
        $resultEntries = array_merge($unitResultEntries, $userResultEntries);
        if ( ! count($resultEntries)) {
            return;
        }
        // create module result
        if ( ! $this->getModuleResult($userId, $moduleEntry->id)) {
            $this->createModuleResult($userId, $moduleEntry->id);
        }
        $moduleResultExpiryTime = null;
        // set default module result expiry
        if ($moduleEntry->moduleExpiryDays) {
            $moduleResultExpiryTime = (time() + ($moduleEntry->moduleExpiryDays * 86400));
        }
        $points = 0;
        foreach ($resultEntries as $resultEntry) {
            if ($resultEntry->resultStatus == 'endorsed') {
                // unit results value is unit value
                if ($resultEntry->type == 'unitResult') {
                    $unitEntry = $resultEntry->resultUnit->first();
                    $points += $unitEntry->unitValue;
                }
                // user result value is custom
                elseif ($resultEntry->type == 'userResult') {
                    $points += $resultEntry->resultValue;
                }
                // check if result expiry is before default module expiry)
                if ($resultEntry->expiryDate && (is_null($moduleResultExpiryTime) || $resultEntry->expiryDate->getTimestamp() < $moduleResultExpiryTime)) {
                    $moduleResultExpiryTime = $resultEntry->expiryDate->getTimestamp();
                }
            }
        }
        // @todo error reporting?
        if ($points >= $moduleEntry->moduleCompletedValue) {
            $this->completeModuleResult($moduleEntry, $userId, $moduleResultExpiryTime);
        }
        return;
    }

    /**
     * Create a module result
     *
     * @param $moduleEntryId
     * @param $userId
     * @return null
     * @throws \Exception
     */
    function createModuleResult($userId, $moduleEntryId) {
        $resultEntry = new EntryModel();
        $resultEntry->sectionId = $this->sectionIdResults;
        $resultEntry->typeId = $this->typeIdModuleResult;
        $resultEntry->enabled = true;
        $resultEntry->authorId = $userId;
        $resultEntry->setContentFromPost(['resultModule' => array($moduleEntryId), 'resultStatus' => 'active']);
        // @todo error reporting?
        if ( ! craft()->entries->saveEntry($resultEntry)) {
            return;
        }
        return;
    }

    /**
     * Complete a module result
     *
     * @param $moduleEntry
     * @param $userId
     * @param $expiryDate
     * @return null
     * @throws /Exception
     */
    function completeModuleResult($moduleEntry, $userId, $expiryDate = null) {
        $resultEntry = $this->getModuleResult($userId, $moduleEntry->id);
        // @todo error reporting
        if ( ! $resultEntry) {
            return;
        }
        // either no expiry, default module expiry or set by result
        $resultEntry->expiryDate = $expiryDate;
        $resultEntry->setContentFromPost(['resultStatus' => 'complete']);
        // @todo error reporting?
        if ( ! craft()->entries->saveEntry($resultEntry)) {
            return;
        }
        return;
    }

    /**
     * @param array $jobRoleIds
     * @throws Exception
     * @return mixed
     */
    public function jobRoleModules($jobRoleIds = []) {
        $criteria = craft()->elements->getCriteria(ElementType::Entry);
        $criteria->section = 'modules';
        $criteria->limit = null;
        $criteria->relatedTo = ['targetElement' => $jobRoleIds, 'field' => 'moduleRoles'];
        return $criteria->find();
    }

    /**
     * @param $jobRoleId
     * @param $userId
     * @return array
     * @throws Exception
     */
    public function getJobRoleUserResults($jobRoleId, $userId = null) {
        if (! $userId) {
            return [];
        }
        // get all modules for job role
        $modules = $this->jobRoleModules([$jobRoleId]);
        $results = [];
        foreach ($modules as $moduleEntry) {
            // get unit results relating to module
            $unitResults = $this->getModuleUnitResults($moduleEntry, $userId);
            // get user results relating to module
            $userResults = $this->getModuleUserResults($moduleEntry, $userId, false);
            $results = array_merge($results, $unitResults, $userResults);
        }
        return $results;
    }

    /**
     * Get all the module unit IDs
     *
     * @param $moduleEntry
     * @return array
     */
    function getModuleUnitIds($moduleEntry) {
        $unitIds = [];
        foreach ($moduleEntry->moduleUnitGroups as $unitGroup) {
            foreach ($unitGroup->unitEntries as $unitEntry) {
                $unitIds[] = $unitEntry->id;
            }
        }
        return $unitIds;
    }

    /**
     *  Get module user results with positive result value
     *
     * @param $moduleEntry
     * @param $userId
     * @param $resultValue
     * @return array
     * @throws Exception
     */
    function getModuleUserResults($moduleEntry, $userId, $resultValue = true) {
        $criteria = craft()->elements->getCriteria(ElementType::Entry);
        $criteria->section = 'results';
        $criteria->type = 'userResult';
        $criteria->authorId = $userId;
        $criteria->limit = null;
        $criteria->relatedTo = ['targetElement' => $moduleEntry->id, 'field' => 'resultModule'];
        if ($resultValue) {
            $criteria->resultValue = '> 0';
        }
        return $criteria->find();
    }

    /**
     * Get module unit results grouped by unit ID
     *
     * @param $moduleEntry
     * @param $userId
     * @return array
     * @throws Exception
     */
    function getModuleUnitResults($moduleEntry, $userId) {
        $unitIds = $this->getModuleUnitIds($moduleEntry);
        $criteria = craft()->elements->getCriteria(ElementType::Entry);
        $criteria->section = 'results';
        $criteria->type = 'unitResult';
        $criteria->authorId = $userId;
        $criteria->limit = null;
        $criteria->relatedTo = ['targetElement' => $unitIds, 'field' => 'resultUnit'];
        $resultEntries = $criteria->find();
        $return = [];
        foreach ($resultEntries as $resultEntry) {
            $unitId = $resultEntry->resultUnit->first()->id;
            $return[$unitId] = $resultEntry;
        }
        return $return;
    }

    /**
     * Return all result entries requiring endorsement for a manager
     *
     * @param UserModel $manager
     * @param int|null $limit
     * @param bool $count
     * @return mixed
     * @throws mixed
     */
    public function getManagerEndorsementResults($manager, $limit = null,  $count = false) {
        $criteria = craft()->elements->getCriteria(ElementType::Entry);
        $criteria->section = 'results';
        $criteria->type = ['userResult', 'unitResult'];
        $criteria->resultStatus = 'pending';
        $criteria->order = 'postDate desc';
        $criteria->limit = $limit;
        // limit by subordinates and check unit level if team or company manager
        if (!$manager->isInGroup('schemeManagers') && !$manager->admin) {
            $subordinateIds = Lantra::$app->user->getManagerSubordinateIds($manager, true);
            if (!count($subordinateIds)) {
                return null;
            }
            $criteria->authorId = $subordinateIds;
            $level = $manager->managerLevel->value ? (int) $manager->managerLevel->value : 1;
            $criteria->unitEndorsementManagerLevel = '<=' . $level;
        }
        return ($count) ? $criteria->count() : $criteria;
    }

    /**
     * Return all users requiring endorsement for a manager
     *
     * @param UserModel $manager
     * @param int|null $limit
     * @param bool $count
     * @param bool $directSubordinates
     * @return mixed
     * @throws mixed
     */
    public function getManagerEndorsementUsers($manager, $limit = null, $count = false, $directSubordinates = false) {
        $criteria = craft()->elements->getCriteria(ElementType::User);
        $criteria->id =  $this->getManagerEndorsementUserIds($manager, $directSubordinates);
        $criteria->order = 'lastName desc';
        $criteria->limit = $limit;
        return ($count) ? $criteria->count() : $criteria;
    }

    /**
     * @param UserModel $manager
     * @param bool $directSubordinates
     * @return int
     */
    public function countManagerEndorsementUsers($manager, $directSubordinates = false) {
        return $this->getManagerEndorsementUserIds($manager, $directSubordinates, true);
    }

    /**
     * @param UserModel $manager
     * @param bool $directSubordinates
     * @param bool $count
     * @return array|int
     */
    public function getManagerEndorsementUserIds($manager, $directSubordinates = false, $count = false) {
        $onlySubordinates = false;
        # check for manager subordinates (SM and admin show all)
        if (!$manager->isInGroup('schemeManagers') && !$manager->admin) {
            $subordinateIds = Lantra::$app->user->getManagerSubordinateIds($manager, $directSubordinates == false);
            # make sure there are any subordinates
            if (!count($subordinateIds)) {
                return $count ? 0 : [];
            }
            $onlySubordinates = true;
            $level = $manager->managerLevel->value ? (int) $manager->managerLevel->value : 1;
        }

        if ($count) {
            $mysql = "SELECT COUNT(DISTINCT authorId) AS total";
        }
        else {
            $mysql = "SELECT DISTINCT authorId";
        }

        $mysql .= "
            FROM {{entries}} e
            JOIN {{content}} c ON c.elementId = e.id
            JOIN {{elements}} el ON el.id = e.id
            WHERE e.sectionId = 10 
            AND c.field_resultStatus = 'pending'
            AND e.typeId = 10
            AND e.typeId = 10";

        # add subordinates and level to query
        if ($onlySubordinates) {
            $mysql .= " 
            AND c.field_unitEndorsementManagerLevel <= " . $level . "
            AND authorId IN(" . implode(',', $subordinateIds) . ")";
        }

        if ($count) {
            return Craft::$app->db->createCommand($mysql)->queryRow()['total'];
        }

        # return array or authorIds of users that have pending results
        $result = Craft::$app->db->createCommand($mysql)->queryAll();
        $return = [];
        foreach ($result as $row) {
            $return[] = $row['authorId'];
        }
        return $return;
    }

    /**
     * Return all expiring module result entries
     *
     * @param null $userId
     * @param string $days
     * @param int $limit
     * @param string $search
     * @return ElementCriteriaModel
     * @throws Exception
     */
    public function getManagerModuleExpiringResults($userId = null, $days = 'all', $limit = 10, $search = '') {
        return $this->getManagerModuleResults($userId, $days, $limit, true,'complete', $search);
    }

    /**
     * Return all completed module result entries
     *
     * @param null $userId
     * @param string $days
     * @param int $limit
     * @param string $search
     * @return ElementCriteriaModel
     * @throws Exception
     */
    public function getManagerModuleCompletedResults($userId = null, $days = 'all', $limit = 10, $search = '') {
        return $this->getManagerModuleResults($userId, $days, $limit,false,'complete', $search);
    }

    /**
     * Return all active module result entries
     *
     * @param null $userId
     * @param string $days
     * @param int $limit
     * @param string $search
     * @return mixed
     * @throws mixed
     */
    public function getManagerModuleActiveResults($userId = null, $days = 'all', $limit = 10, $search = '') {
        return $this->getManagerModuleResults($userId, $days, $limit, false,'active', $search);
    }

    /**
     * Return user module results for a manager
     *
     * @param null $userId
     * @param string $days
     * @param int $limit
     * @param bool $expiring
     * @param string $status
     * @param string $search
     * @param array $authorId
     * @return ElementCriteriaModel|null
     * @throws mixed
     */
    private function getManagerModuleResults($userId = null, $days = 'all', $limit = 10, $expiring = true, $status = 'active', $search = '', $authorId = null) {
        if ( ! is_null($userId)) {
            $manager = craft()->users->getUserById($userId);
        }
        else {
            $manager = craft()->userSession->getUser();
        }
        if ( ! $manager) {
            return null;
        }
        // limit by subordinates if team or company manager
        if ( ! $manager->isInGroup('schemeManager') && ! $manager->admin()) {
            $subordinateIds = Lantra::$app->user->getManagerSubordinateIds($manager, true);
            if ( ! count($subordinateIds)) {
                return null;
            }
            $authorId = $subordinateIds;
        }
        return $this->getModuleResults($days, $limit, $expiring, $status, $search, $authorId);
    }

    /**
     * @param string $days
     * @param int $limit
     * @param bool $expiring
     * @param string $status
     * @param string $search
     * @param array $authorId
     * @return object
     * @throws mixed
     */
    public function getModuleResults($days = 'all', $limit = 10, $expiring = true, $status = 'active', $search = '', $authorId = null) {
        $criteria = craft()->elements->getCriteria(ElementType::Entry);
        $criteria->section = 'results';
        $criteria->type = 'moduleResult';
        $criteria->resultStatus = $status;
        if ($expiring == 'expired') {
            $criteria->expiryDate = '<'. time();
            $criteria->order = 'expiryDate asc';
        }
        elseif ($expiring == true) {
            $criteria->expiryDate = $days != 'all' ? '<'. (time() + ($days*86400)) : ':notempty:';
            $criteria->order = 'expiryDate asc';
        }
        elseif ($days != 'all') {
            $criteria->postDate = '>' . (time() - ($days*86400));
        }
        if ($search) {
            $criteria->search = $search;
        }
        if ($authorId) {
        $criteria->authorId = $authorId;
        }
        $criteria->limit = $limit;
        return $criteria;
    }

    /**
     * Return all blocked unit result entries
     *
     * @param null $userId
     * @param string $days
     * @param int $limit
     * @param string $search
     * @return mixed
     * @throws mixed
     */
    public function getManagerUnitBlockedResults($userId = null, $days = 'all', $limit = 10, $search = '') {
        return $this->getManagerUnitResults($userId, $days, $limit, false, 'blocked', false, $search);
    }

    /**
     * Return all expiring unit result entries
     *
     * @param null $userId
     * @param string $days
     * @param int $limit
     * @param string $search
     * @return ElementCriteriaModel
     * @throws Exception
     */
    public function getManagerUnitExpiringResults($userId = null, $days = 'all', $limit = 10, $search = '') {
        return $this->getManagerUnitResults($userId, $days, $limit,true, false, false, $search);
    }

    /**
     * Return all endorsed unit result entries
     *
     * @param null $userId
     * @param string $days
     * @param int $limit
     * @param string $search
     * @return ElementCriteriaModel
     * @throws Exception
     */
    public function getManagerUnitEndorsedResults($userId = null, $days = 'all', $limit = 10, $search = '') {
        return $this->getManagerUnitResults($userId, $days, $limit,false, 'endorsed', false, $search);
    }

    /**
     * @param $filter
     * @return array
     */
    private function formatUserFilter($filter) {
        $defaults = [
            'limit'      => null,
            'search'     => '',
            'relatedTo'  => []
        ];
        return array_merge($defaults, $filter);
    }

    /**
     * @param $filter
     * @return array
     */
    private function formatResultsFilter($filter) {
        $defaults = [
            'limit'         => null,
            'search'        => '',
            'relatedTo'     => [],
            'order'         => 'authorId',
            'status'        => ['live', 'expired'],
            'resultType'    => null,
            'expiryDate'    => null,
            'startDate'     => null,
            'resultStatus'  => null,
            ## used for filtering required units
            'unitIds'       => [],
        ];
        return array_merge($defaults, $filter);
    }

    /**
     * @param null $userId
     * @param array $userFilter
     * @param array $resultFilter
     * @return array
     * @throws Exception
     */
    public function getManagerUserSummary($userId = null, $userFilter = [], $resultFilter = []) {
        $userFilter = $this->formatUserFilter($userFilter);
        $subordinates = Lantra::$app->user->getManagerUsers($userId, $userFilter['limit'], $userFilter['search'], $userFilter['relatedTo']);
        if (! $subordinates) {
            return [];
        }
        $subordinateIds = $subordinates->ids();
        // streamlined sql to get users
        $users = Lantra::$app->user->getReportUsers($subordinateIds);

        $header = [
            'Company ID',
            'Company Label',
            'User ID',
            'User Type',
            'User Name',
            'User Email',
            'User Job Title',
            'User Birthday',
            'User Start Date',
            'User Address'
        ];

        // only filter units if less than 10 users
        if (count($subordinateIds) < 10) {
            $allUnits = $this->managerUnits($subordinates);
        }
        else {
            $allUnits = $this->allUnits();
        }

        $data = $this->getUserResultCache($subordinateIds);

        foreach ($allUnits as $unit) {
            $header[] = 'Qual Title';
            $header[] = 'Date Started';
            $header[] = 'Date Finished';
            $header[] = 'Date Expired';
        }

        $format = 'd-m-Y';

        $rows = [$header];
        foreach($users as $user) {

            $role = $this->getRole($user->roleId);

            $row = [
                $user->companyId,
                $user->companyLabel,
                $user->id,
                $user->userType,
                $user->fullName,
                $user->email,
                $role ? $role->title : 'unknown',
                $user->userDateOfBirth,
                $user->userStartDate,
                $user->userAddress
            ];
            $userUnits = $this->roleUnits($user->roleId);
            foreach ($allUnits as $unit) {
                // set defaults
                $title = '';
                $startDate = false;
                $finishDate = false;
                $expiryDate = false;
                // this unit is required for this user
                if (isset($userUnits[$unit->id])) {
                    $title = $unit->title;
                    // does a result exist?
                    if (isset($data[$user->id]) && isset($data[$user->id][$unit->id])) {
                        $startDate = $data[$user->id][$unit->id]['startDate'];
                        $finishDate = $data[$user->id][$unit->id]['finishDate'];
                        $expiryDate = $data[$user->id][$unit->id]['expiryDate'];
                    }
                }
                // output the data
                $row[] = $title;
                $row[] = $startDate ? $startDate->format($format) : '';
                $row[] = $finishDate ? $finishDate->format($format) : '';
                $row[] = $expiryDate ? $expiryDate->format($format) : '';
            }
            $rows[] = $row;
        }
        return $rows;
    }


    /**
     * @param EntryModel $resultEntry
     * @return EntryModel
     * @throws \Exception
     */
    public function legacyResultFiles(EntryModel $resultEntry) {
        if (! $resultEntry->legacyResultFiles) {
            return $resultEntry;
        }
        $folderName = $resultEntry->authorId;
        $parentFolder = craft()->assets->getFolderById(1);
        $folder = craft()->assets->findFolder(['parent' => $parentFolder, 'name' => $folderName]);
        if (! $folder) {
            $folder = craft()->assets->createFolder($parentFolder->id, $folderName);
            $response = $folder->getResponseData();
            $folderId = isset($response['folderId']) ? $response['folderId'] : null;
        } else {
            $folderId = $folder->id;
        }
        $legacyFiles = explode(',', $resultEntry->legacyResultFiles);
        $assetIds = [];
        $updatedLegacyResultFiles = $legacyFiles;

        $unchanged = true;

        foreach($legacyFiles as $key => $filePath){
            $env = craft()->config->get('environmentVariables');
            $legacyPath = $env['assetsPath'] . '/archive' . trim($filePath);
            $parts = explode('/', $legacyPath);
            $filename = end($parts);

            ## look for path with spaces
            $legacyPathSpaces = str_replace('%20', ' ', $legacyPath);
            $localPath = false;
            if (is_file($legacyPathSpaces)) {
                $localPath = $legacyPathSpaces;
            } elseif (is_file($legacyPath)) {
                $localPath = $legacyPath;
            }

            if ($localPath && $folderId) {
                $response = craft()->assets->insertFileByLocalPath(
                    $localPath,
                    $filename,
                    $folderId,
                    AssetConflictResolution::Replace
                );

                $fileId = $response->getDataItem('fileId');

                if ($response->isError()) {

                }

                if ($response->isSuccess() && $fileId) {
                    $assetIds[] = $fileId;
                    ## leave archive file in place, just in case.
                    ## unlink($localPath);
                    unset($updatedLegacyResultFiles[$key]);
                    $unchanged = false;
                }
            }
            else {
                Craft::log("Legacy files not found: [". $localPath . "] ",LogLevel::Error, true, 'results', 'lantra');
            }
        }

        if ($unchanged || ! count($assetIds)) {
            return $resultEntry;
        }

        $resultEntry->getContent()->title = $resultEntry->title;
        $resultEntry->setContentFromPost([
            'resultEvidence' => $assetIds,
            'legacyResultFiles' => implode(',', $updatedLegacyResultFiles)
        ]);
        craft()->entries->saveEntry($resultEntry);
        ## get entry again to force refresh on data
        $resultEntry = craft()->entries->getEntryById($resultEntry->id);
        return $resultEntry;
    }

    /**
     * @param $subordinates
     * @return array
     * @throws Exception
     */
    private function mandatoryUnitTitles($subordinates) {
        $return = [];
        foreach($subordinates as $user) {
            $mandatoryUnits = $this->userUnits($user);
            foreach($mandatoryUnits as $unit) {
                if (! isset($return[$unit->id])){
                    $return[$unit->id] = $unit->title;
                }
            }
        }
        return $return;
    }

    /**
     * @param $userResults
     * @param $userId
     * @param $unitId
     * @param string $title
     * @return null
     */
    private function getUserResult($userResults, $userId, $unitId, $title = '') {
        if ( !isset($userResults[$userId])) {
            return null;
        }
        ## look for unitId
        if (isset($userResults[$userId][$unitId])) {
            return $userResults[$userId][$unitId];
        }
        ## search for title
        foreach ($userResults[$userId] as $result) {
            if ($title && $title == $result->title) {
                return $result;
            }
        }
        return null;
    }

    /**
     * Get subordinate's results as matrix (Qual User)
     *
     * @param null $userId
     * @param array $userFilter
     * @param array $resultFilter
     * @param string $displayField
     * @return array
     * @throws Exception
     */
    public function getManagerUserCompletedResults($userId = null, $userFilter = [], $resultFilter = [], $displayField = 'expiryDate') {
        $userFilter = $this->formatUserFilter($userFilter);
        $subordinates = Lantra::$app->user->getManagerUsers($userId, $userFilter['limit'], $userFilter['search'], $userFilter['relatedTo']);
        $subordinateIds = $subordinates->ids();

        $header = [
            'User ID',
            'User Name',
            'Company Label'
        ];

        $resultFilter = $this->formatResultsFilter($resultFilter);
        $allResults = $this->getSubordinateResults($subordinateIds, $resultFilter);

        $reportUnits = count($resultFilter['relatedTo']) ? $resultFilter['relatedTo']['targetElement'] : [];

        $headerUnits = [];

        ## add all the mandatory result headers
        if ($resultFilter['resultType'] != 'userResult') {
            $mandatoryUnits = $this->mandatoryUnitTitles($subordinates);
            foreach ($mandatoryUnits as $id => $title) {
                // skip mandatory units if filter is on
                if (count($reportUnits) && ! in_array($id, $reportUnits)) {
                    continue;
                }
                $headerUnits[$id] = $title;
                $header[] = $title;
            }
        }

        if ($resultFilter['resultType'] != 'unitResult') {
            ## add the title columns (might be unit id or result id)
            foreach ($allResults as $userId => $results) {
                foreach ($results as $id => $result) {
                    if (isset($headerUnits[$id])) {
                        continue;
                    }
                    $title = $result->title;
                    if ($result->type == 'unitResult' && $unitEntry = $result->resultUnit->count()) {
                        $title = $result->resultUnit->first()->title;
                    }
                    ## hack to remove duplicate results with same title
                    if (in_array($title, $headerUnits)) {
                        continue;
                    }
                    $headerUnits[$id] = $title;
                    $header[] = $title;
                }
            }
        }

        $rows = [$header];
        foreach($subordinates as $user) {
            // if user results we don't need mandatory units
            if ($resultFilter['resultType'] != 'userResult') {
                $mandatoryUnits = $this->userUnits($user);
            }
            $company = Lantra::$app->user->userCompany($user);
            $row = [
                $user->id,
                $user->fullName,
                $company ? $company->companyLabel : 'unknown',
            ];
            foreach ($headerUnits as $id => $title) {
                // show result value if exists
                $result = $this->getUserResult($allResults, $user->id, $id, $title);
                if ($result) {
                    $fieldValue = $result->$displayField;
                    $value = $fieldValue ? $fieldValue: '-';
                }
                // if mandatory report
                elseif ($resultFilter['resultType'] == 'unitResult' && !isset($mandatoryUnits[$id])) {
                    $value = 'N/R';
                }
                else {
                    $value = '';
                }
                $row[] = $value;
            }
            $rows[] = $row;
        }
        return $rows;
    }

    /**
     * @param $subordinateIds
     * @param array $resultFilter
     * @return array
     * @throws Exception
     */
    private function getSubordinateResults($subordinateIds, $resultFilter = []) {
        $criteria = craft()->elements->getCriteria(ElementType::Entry);
        $criteria->section = 'results';
        $criteria->limit = null;
        if ($resultFilter['resultType']) {
            $criteria->type = $resultFilter['resultType'];
        }
        else {
            $criteria->type = ['unitResult', 'userResult'];
        }
        if ($resultFilter['startDate']) {
            $criteria->startDate = $resultFilter['startDate'];
        }
        if ($resultFilter['expiryDate']) {
            $criteria->expiryDate = $resultFilter['expiryDate'];
        }
        if ($resultFilter['resultStatus']) {
            $criteria->resultStatus = $resultFilter['resultStatus'];
        }
        if ($resultFilter['order']) {
            $criteria->order = $resultFilter['order'];
        }
        $criteria->authorId = $subordinateIds;
        $criteria->status = $resultFilter['status'];
        if ($resultFilter['relatedTo']) {
            $criteria->relatedTo = $resultFilter['relatedTo'];
        }
        $results = $criteria->find();

        // arrange as useful array [userId][id] = [result]
        $data = [];
        foreach ($results as $result) {
            if ( ! isset ($data[$result->authorId])){
                $data[$result->authorId] = [];
            }
            $id = $result->type == 'unitResult' && $result->resultUnit->count() ? $result->resultUnit->first()->id : $result->id;
            $data[$result->authorId][$id] = $result;
        }
        return $data;
    }

    /**
     * @param array $array
     * @return array
     */
    private function getIds($array = []){
        $ids = [];
        if ((is_array($array) || is_object($array)) && count($array)) {
            foreach ($array as $item) {
                $ids [] = $item->id;
            }
        }
        return $ids;
    }

    /**
     * @param null $userId
     * @param array $userFilter
     * @param $resultFilter
     * @param $includeRequired
     * @return array
     * @throws Exception
     */
    public function getManagerUnitExpiredResults($userId = null, $userFilter = [], $resultFilter, $includeRequired = false) {
        $userFilter = $this->formatUserFilter($userFilter);
        $subordinates = Lantra::$app->user->getManagerUsers($userId, $userFilter['limit'], $userFilter['search'], $userFilter['relatedTo']);
        $subordinateIds = $subordinates->ids();

        $header = [
            'User ID',
            'User Name',
            'Company ID',
            'Company Label',
            'User Job Title',
            'Unit/Result Title',
            'Expiry Date',
            'Status'
        ];

        $resultFilter = $this->formatResultsFilter($resultFilter);
        $allResults = $this->getSubordinateResults($subordinateIds, $resultFilter);
        $rows = [];
        $resultIds = [];
        foreach($allResults as $userId => $results) {
            foreach ($results as $id => $result) {
                $resultIds[] = $result->id;
                $user = $result->author;
                $company = Lantra::$app->user->userCompany($user);
                $role = $user->userRole->first();
                $userUnits = $this->roleUnits($role->id);
                $title = $result->title;
                if ($result->type == 'unitResult') {
                    $resultUnit = $result->resultUnit->first();
                    // skip non-mandatory unitResults (i.e. from previous job role)
                    if ($resultFilter['resultType'] == 'unitResult' && !isset($userUnits[$resultUnit->id])) {
                       continue;
                    }
                    $title = $resultUnit ? $resultUnit->title : '[unit not found] ' . $title;
                }
                $row = [
                    $userId,
                    $user->fullName,
                    $company ? $company->id : 'unknown',
                    $company ? $company->companyLabel : 'unknown',
                    $role ? $role->title : 'unknown',
                    $title,
                    $result->expiryDate,
                    $result->status == 'expired' ? 'expired' : 'expiring'
                ];
                $rows[] = $row;
            }
        }
        if ($includeRequired) {
            foreach($subordinates as $user) {
                $unitIds = count($resultFilter['unitIds']) ? $resultFilter['unitIds'] : [];
                $rows = array_merge($rows, $this->userRequiredRows($user, $unitIds, false));
            }
            usort($rows, function ($a, $b) {return strcmp($a[0], $b[0]);});
        }
        return array_merge([$header], $rows);
    }

    /**
     * Return all required (including expired)
     *
     * @param null $userId
     * @param array $userFilter
     * @param array $resultFilter
     * @return array
     * @throws Exception
     */
    public function getManagerUnitRequiredResults($userId = null, $userFilter = [], $resultFilter) {
        $userFilter = $this->formatUserFilter($userFilter);
        $subordinates = Lantra::$app->user->getManagerUsers($userId, $userFilter['limit'], $userFilter['search'], $userFilter['relatedTo']);

        $header = [
            'User ID',
            'User Name',
            'Company ID',
            'Company Label',
            'User Job Title',
            'Unit/Result Title',
            'Expiry Date',
            'Status'
        ];

        $resultFilter = $this->formatResultsFilter($resultFilter);
        $rows = [$header];
        foreach($subordinates as $user) {
            if ( ! $resultFilter['resultType'] || $resultFilter['resultType'] == 'unitResult') {
                $unitIds = count($resultFilter['unitIds']) ? $resultFilter['unitIds'] : [];
                $rows = array_merge($rows, $this->userRequiredRows($user, $unitIds));
            }
            if ( ! $resultFilter['resultType'] || $resultFilter['resultType'] == 'userResult') {
                $rows = array_merge($rows, $this->userExpiredRows($user, $resultFilter));
            }
        }
        return $rows;
    }

    /**
     * @param $user
     * @return array
     * @throws Exception
     */
    private function userExpiredRows($user, $resultFilter)  {
        $rows = [];
        $criteria = craft()->elements->getCriteria(ElementType::Entry);
        $criteria->type = 'userResult';
        $criteria->section = 'results';
        $criteria->status = 'expired';
        $criteria->authorId = $user->id;
        $results = $criteria->find();
        foreach ($results as $result) {
            $company = Lantra::$app->user->userCompany($user);
            $role = $user->userRole->first();
            $row = [
                $user->id,
                $user->fullName,
                $company ? $company->id : '~',
                $company ? $company->companyLabel : 'unknown',
                $role ? $role->title : 'unknown',
                $result->title,
                $result->expiryDate,
                'expired'
            ];
            $rows[] = $row;
        }
        return $rows;
    }

    /**
     * @param null $user
     * @param array $unitIds
     * @param bool $includeExpired
     * @return array
     * @throws Exception
     */
    private function userRequiredRows($user, $unitIds = [], $includeExpired = true)  {
        $units = $this->userUnits($user, $unitIds);
        $rows = [];
        foreach ($units as $unit) {
            $result = $this->unitResult($user, $unit->id);
            if ( ! $result || ($includeExpired && $result->status == 'expired')) {
                $company = Lantra::$app->user->userCompany($user);
                $role = $user->userRole->first();
                $row = [
                    $user->id,
                    $user->fullName,
                    $company ? $company->id : '~',
                    $company ? $company->companyLabel : 'unknown',
                    $role ? $role->title : 'unknown',
                    $unit->title,
                    $result ? $result->expiryDate : null,
                    $result ? 'expired' : 'required'
                ];
                $rows[] = $row;
            }
        }
        return $rows;
    }

    // cache of roles
    private $roles;

    private function getRole($roleId = null) {
        if (is_null($this->roles)) {
            $criteria = craft()->elements->getCriteria(ElementType::Category);
            $criteria->group = 'roles';
            $criteria->limit = null;
            foreach($criteria->find() as $role) {
                $this->roles[$role->id] = $role;
            }
        }
        return isset($this->roles[$roleId]) ? $this->roles[$roleId] : null;

    }

    /* cache of role modules */
    private $roleUnits;
    private $roleModules = [];
    private $moduleUnits = [];

    /**
     * Get all the units for all the subordinates of a manager
     *
     * @param $subordinates
     * @return array
     * @throws Exception
     */
    private function managerUnits($subordinates) {
        $units = [];
        foreach($subordinates as $user) {
            foreach($this->userUnits($user) as $unit) {
                if (! isset($units[$unit->id])) {
                    $units[$unit->id] = $unit;
                }
            }
        }
        return $units;
    }

    /**
     * Get all the units for a role
     *
     * @param $roleId
     * @return array|mixed
     * @throws Exception
     */
    public function roleUnits($roleId)  {
        if (is_null($this->roleUnits)) {
            $criteria = craft()->elements->getCriteria(ElementType::Category);
            $criteria->group = 'roles';
            $criteria->limit = null;
            foreach ($criteria->find() as $role) {
                $modules = $this->roleModules($role);
                $units = [];
                foreach ($modules as $module) {
                    $moduleUnits = $this->moduleUnits($module);
                    foreach ($moduleUnits as $unit) {
                        if (!isset($units[$unit->id])) {
                            $units[$unit->id] = $unit;
                        }
                    }
                }
                $this->roleUnits[$role->id] = $units;
            }
        }
        return isset($this->roleUnits[$roleId]) ? $this->roleUnits[$roleId] : [];
    }

    /**
     * Get all the units for a user
     *
     * @param $user
     * @param $unitIds
     * @return array
     * @throws Exception
     */
    public function userUnits($user, $unitIds = [])  {
        $units = [];
        foreach($user->userRole as $role) {
            $modules = $this->roleModules($role);
            foreach ($modules as $module) {
                $moduleUnits = $this->moduleUnits($module);
                foreach ($moduleUnits as $unit) {
                    if (! isset($units[$unit->id]) && (! count($unitIds) || in_array($unit->id, $unitIds))) {
                        $units[$unit->id] = $unit;
                    }
                }
           }
        }
        return $units;
    }

    /**
     * @param $user
     * @param $unitId
     * @return BaseElementModel|null
     * @throws Exception
     */
    private function unitResult($user, $unitId)  {
        $criteria = craft()->elements->getCriteria(ElementType::Entry);
        $criteria->relatedTo = ['targetElement' => $unitId, 'field' => 'resultUnit'];
        $criteria->status = ['live', 'expired'];
        $criteria->authorId = $user->id;
        return $criteria->first();
    }

    /**
     * @param $role
     * @return ElementCriteriaModel|int
     * @throws Exception
     */
    private function roleModules($role)  {
        if (isset($this->roleModules[$role->id])) {
            $criteria = $this->roleModules[$role->id];
        }
        else {
            $criteria = craft()->elements->getCriteria(ElementType::Entry);
            $criteria->relatedTo = ['targetElement' => $role->id, 'field' => 'moduleRoles'];
            $criteria->limit = null;
            $this->roleModules[$role->id] = $criteria;
        }
        return $criteria->find();
    }

    /**
     * @param $module
     * @return ElementCriteriaModel|mixed
     * @throws Exception
     */
    private function moduleUnits($module)  {
        if (isset($this->moduleUnits[$module->id])) {
            return $this->moduleUnits[$module->id];
        }
        $units = [];
        foreach($module->moduleUnitGroups as $group) {
            $units = array_merge($units, $group->unitEntries->find());
        }
        $this->moduleUnits[$module->id] = $units;
        return $units;
    }

    /**
     * @return array
     * @throws Exception
     */
    private function allUnits() {
        $criteria = craft()->elements->getCriteria(ElementType::Entry);
        $criteria->section = 'units';
        $criteria->limit = null;
        $units = [];
        foreach($criteria->find() as $unit) {
            $units[$unit->id] = $unit;
        }
        return $units;
    }

    /**
     * Return all unit result entries
     *
     * @param null $userId
     * @param string $days
     * @param int $limit
     * @param bool $expiring
     * @param bool $status
     * @param bool $id
     * @param string $search
     * @return ElementCriteriaModel|null
     * @throws mixed
     */
    private function getManagerUnitResults($userId = null, $days = 'all', $limit = 10, $expiring = false, $status = false, $id = false, $search = '') {
        if ( ! is_null($userId)) {
            $manager = craft()->users->getUserById($userId);
        }
        else {
            $manager = craft()->userSession->getUser();
        }
        if ( ! $manager) {
            return null;
        }
        $criteria = craft()->elements->getCriteria(ElementType::Entry);
        $criteria->section = 'results';
        $criteria->type = 'unitResult';
        $criteria->limit = $limit;
        if ($expiring) {
            $criteria->expiryDate = $days != 'all' ? '<'. (time() + ($days*86400)) : ':notempty:';
            $criteria->order = 'expiryDate asc';
        }
        elseif ($days != 'all') {
            $criteria->postDate = '>' . (time() - ($days*86400));
        }
        if ($status) {
            $criteria->resultStatus = $status;
        }
        // from specific ids (i.e. blocked results)
        if ($id) {
            $criteria->id = $id;
        }
        if ($search) {
            $criteria->search = $search;
        }
        // limit by subordinates if team or company manager
        if ( ! $manager->isInGroup('schemeManager') && ! $manager->admin()) {
            $subordinateIds = Lantra::$app->user->getManagerSubordinateIds($manager, true);
            if ( ! count($subordinateIds)) {
                return null;
            }
            $criteria->authorId = $subordinateIds;
        }
        return $criteria;
    }

    /**
     * Get all modules for a job role
     *
     * @param $roleId
     * @return array
     * @throws Exception
     */
    private function getRoleModules($roleId) {
        $criteria = craft()->elements->getCriteria(ElementType::Entry);
        $criteria->section = 'modules';
        $criteria->limit = null;
        $criteria->relatedTo = ['targetElement' => $roleId, 'field' => 'moduleRoles'];
        return $criteria->total() ? $criteria->find() : [];
    }

    /**
     * create unit column
     *
     * @param $id
     */
    public function addUnitColumn($id) {
        if ( ! craft()->db->columnExists('lantra_result_cache', 'unit' . $id)) {
            craft()->db->createCommand()->addColumn('lantra_result_cache', 'unit' . $id, 'text');
        }
    }

    /**
     * remove unit column
     *
     * @param $id
     */
    public function removeUnitColumn($id) {
        if (craft()->db->columnExists('lantra_result_cache', 'unit' . $id)) {
            craft()->db->createCommand()->dropColumn('lantra_result_cache', 'unit' . $id);
        }
    }

    /**
     * save user result
     *
     * @param $userId
     * @param $resultEntries
     *
     */
    public function saveUserResultCache($userId, $resultEntries = null) {
        if (Lantra::$app->setting->getSetting('disableResultCache')) {
            return;
        }
        $keyColumns = [
            'userId' => $userId
        ];
        $updateColumns = [];
        if ($resultEntries) {
            if (!is_array($resultEntries)) {
                $resultEntries = [$resultEntries];
            }
            foreach ($resultEntries as $resultEntry) {
                $unit = $resultEntry->resultUnit->first();
                if ($unit) {
                    $updateColumns['unit' . $unit->id] = $this->setResultValue($resultEntry);
                }
            }
        }
        craft()->db->createCommand()->insertOrUpdate('lantra_result_cache', $keyColumns, $updateColumns);
    }

    /**
     * @param $resultEntry
     */
    public function deleteUserResultCache($resultEntry) {
        if (Lantra::$app->setting->getSetting('disableResultCache')) {
            return;
        }
        $userId = $resultEntry->getAuthor()->id;
        $unitId = $resultEntry->resultUnit->first()->id;
        if ($userId && $unitId) {
            craft()->db->createCommand()->update('lantra_result_cache', ['unit' . $unitId => ""], ['userId' => $userId]);
        }
    }

    /**
     * get user result
     *
     * @param $userIds
     * @return array
     *
     */
    public function getUserResultCache($userIds = []) {
        $single = ! is_array($userIds);
        if ($single) {
            $where = ['userId' => $userIds];
        }
        else {
            $where = ['IN', 'userId', $userIds];
        }
        $query = craft()->db->createCommand()->from('lantra_result_cache')->where($where);
        $result = $query->queryAll();

        if ( ! $result) {
            return null;
        }
        $return = [];
        foreach($result as $id => $row) {
            $return[$row['userId']] = [];
            foreach($row as $column => $value) {
                if (substr($column,0 , 4) == 'unit') {
                    $unitId = trim($column, 'unit');
                    $return[$row['userId']][$unitId] = $this->getResultValue($value);
                }
            }
        }
        return $single ? array_pop($return) : $return;
    }

    /**
     * @param $value
     * @return array
     */
    private function getResultValue($value) {
        $value = json_decode($value);
        return [
            'expiryDate' => $value && isset($value->expiryDate) ? DateTime::createFromFormat('U', $value->expiryDate) : null,
            'startDate' => $value && isset($value->startDate) ? DateTime::createFromFormat('U', $value->startDate) : null,
            'finishDate' => $value && isset($value->finishDate) ? DateTime::createFromFormat('U', $value->finishDate) : null,
        ];
    }

    /**
     * @param $resultEntry
     * @return string
     */
    private function setResultValue($resultEntry) {
        return json_encode([
            'expiryDate' => $resultEntry->expiryDate ? $resultEntry->expiryDate->getTimestamp() : null,
            'startDate' => $resultEntry->resultStartDate ? $resultEntry->resultStartDate->getTimestamp() : null,
            'finishDate' => $resultEntry->resultFinishDate ? $resultEntry->resultFinishDate->getTimestamp() : null
        ]);
    }
}
