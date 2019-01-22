<?php
namespace Craft;

class Lantra_ResultsService extends BaseApplicationComponent
{
    // @todo move ids to config?
    private $sectionIdResults = 10;
    private $typeIdUnitResult = 10;
    private $typeIdModuleResult = 14;

    /**
     * @param $entry
     * @param $comment
     * @param $userId
     * @throws \Exception
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
                    'comment' => $row->comment
                ]
            ];
        }
        $tableData['new1'] = [
            'type' => $blockType->id,
            'enabled' => true,
            'fields' => [
                'user' => [$userId],
                'date' => time(),
                'comment' => $comment
            ]
        ];
        craft()->lantra_notify->sendCommentUpdate($entry, $comment, $userId);
        return $tableData;
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
        $resultStatus = $passed ? 'endorsed' : 'failed';
        $resultScore = $score;
        // does a result exist?
        if (false == $resultEntry = $this->getUnitResult($attemptEntry->authorId, $unitEntry->id)) {
            $resultEntry = new EntryModel();
            $resultEntry->sectionId = $this->sectionIdResults;
            $resultEntry->typeId = $this->typeIdUnitResult;
            $resultEntry->enabled = true;
            $resultEntry->authorId = $attemptEntry->authorId;
            $resultAttempts = array($attemptEntry->id);
        }
        else {
            // append new result attempt
            $resultAttempts = array_merge($resultEntry->resultAttempts->ids(), array($attemptEntry->id));
            // only change if better than previous
            if ($resultEntry->resultStatus == 'failed' && $passed) {
                $resultStatus = $passed ? 'endorsed' : 'failed';
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
     * Check date in yyyy-mm-dd
     *
     * @param string
     * @return bool
     * @throws null
     */
    private function checkDate($date) {
        $parts = explode('-', $date);
        return checkdate($parts[1], $parts[2], $parts[0]);
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
        if ($resultEntry->type == 'unitResult' || $resultEntry->type == 'userResult') {
            // set a user expiry date
            $userExpiryDate = craft()->request->getPost('userExpiryDate');
            if ($userExpiryDate && $this->checkDate($userExpiryDate)) {
                $resultEntry->expiryDate = new \DateTime($userExpiryDate . ' 12:00:00');
                $saveContent = true;
            }
            // set a user start date
            $userStartDate = craft()->request->getPost('userStartDate');
            if ($userStartDate && $this->checkDate($userStartDate)) {
                $resultEntry->setContentFromPost(['resultStartDate' => new \DateTime($userStartDate . ' 12:00:00')]);
                $saveContent = true;
            }
            // set a user finish date
            $userFinishDate = craft()->request->getPost('userFinishDate');
            if ($userFinishDate && $this->checkDate($userFinishDate)) {
                $resultEntry->setContentFromPost(['resultFinishDate' => new \DateTime($userFinishDate . ' 12:00:00')]);
                $saveContent = true;
            }
            // copy manager endorsement level from unit for submitted evidence
            if ($resultEntry->resultEvidence && $resultEntry->type == 'unitResult') {
                $resultEntry->setContentFromPost(['unitEndorsementManagerLevel' => $unitEntry->unitEndorsementManagerLevel]);
                $saveContent = true;
                craft()->lantra_notify->sendManagerEndorsementResult($resultEntry, $unitEntry->unitEndorsementManagerLevel);
            }
            // set author (manager submitting on behalf of user)
            $authorId = craft()->request->getPost('author');
            if (count($authorId)) {
                $author = craft()->users->getUserById($authorId[0]);
                // auto endorse
                if ($resultEntry->type == 'userResult' || ($resultEntry->resultEvidence && $resultEntry->type == 'unitResult')) {
                    $resultEntry->setContentFromPost(['resultEndorsedDate' => time(), 'resultStatus' => 'endorsed']);
                }
                // make sure title is correct
                if ($author && $resultEntry->type == 'unitResult' && $resultEntry->resultEvidence)
                {
                    $resultEntry->getContent()->title = '[unit ' . $unitEntry->id . '] ' . $author->firstName . ' ' . $author->lastName;
                }
                $saveContent = true;
            }
            elseif ($resultEntry->type == 'userResult') {
                craft()->lantra_notify->sendManagerEndorsementResult($resultEntry);
            }
        }
        if ($saveContent) {
            craft()->entries->saveEntry($resultEntry, false);
        }
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
        if ($resultUnitEntry->testMaxAttempts && ($totalAttempts >= $resultUnitEntry->testMaxAttempts)) {
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
        $resultEntry->setContentFromPost(['resultAttempts' => []]);
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
     * @return array
     * @throws Exception
     */
    function getModuleUserResults($moduleEntry, $userId) {
        $criteria = craft()->elements->getCriteria(ElementType::Entry);
        $criteria->section = 'results';
        $criteria->type = 'userResult';
        $criteria->authorId = $userId;
        $criteria->limit = null;
        $criteria->relatedTo = ['targetElement' => $moduleEntry->id, 'field' => 'resultModule'];
        $criteria->resultValue = '> 0';
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
    public function getManagerEndorsementResults(UserModel $manager, $limit = null,  $count = false) {
        $criteria = craft()->elements->getCriteria(ElementType::Entry);
        $criteria->section = 'results';
        $criteria->type = ['userResult', 'unitResult'];
        $criteria->resultStatus = 'pending';
        $criteria->order = 'postDate desc';
        $criteria->limit = $limit;
        // limit by subordinates and check unit level if team or company manager
        if ( ! $manager->isInGroup('schemeManager') && ! $manager->admin) {
            $subordinateIds = craft()->lantra_users->getManagerSubordinateIds($manager, true);
            if ( ! count($subordinateIds)) {
                return null;
            }
            $level = $manager->managerLevel->value ? (int) $manager->managerLevel->value : 1;
            $criteria->unitEndorsementManagerLevel = '<=' . $level;
        }
        return ($count) ? $criteria->count() : $criteria;
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
            $subordinateIds = craft()->lantra_users->getManagerSubordinateIds($manager, true);
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
            $subordinateIds = craft()->lantra_users->getManagerSubordinateIds($manager, true);
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
}
