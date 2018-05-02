<?php
namespace Craft;

class Lantra_ResultsService extends BaseApplicationComponent
{
    // @todo move ids to config?
    private $sectionIdResults = 10;
    private $typeIdUnitResult = 10;
    private $typeIdModuleResult = 14;

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
     * Save a test attempt
     *
     * @param $attemptEntry
     * @return null
     * @throws Mixed
     */
    function saveAttemptResult($attemptEntry) {
        $attemptEntry = craft()->entries->getEntryById($attemptEntry->id);
        $unitEntry = $attemptEntry->attemptUnit->first();
        $user = craft()->userSession->getUser();
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
        if (false == $resultEntry = $this->getUnitResult($user->id, $unitEntry->id)) {
            $resultEntry = new EntryModel();
            $resultEntry->sectionId = $this->sectionIdResults;
            $resultEntry->typeId = $this->typeIdUnitResult;
            $resultEntry->enabled = true;
            $resultEntry->authorId = $user->id;
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
            craft()->lantra_notify->sendNoAttemptsRemaining($resultEntry);
        }
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
        $resultEntries = $this->getModuleUnitResults($moduleEntry, $userId);
        if ( ! count($resultEntries)) {
            return;
        }
        $points = 0;
        foreach ($resultEntries as $resultEntry) {
            $unitEntry = $resultEntry->resultUnit->first();
            if ($resultEntry->resultStatus == 'endorsed') {
                $points += $unitEntry->unitValue;
            }
        }
        // @todo error reporting?
        if ($points >= $moduleEntry->moduleCompletedValue) {
            $this->saveModuleResult($moduleEntry, $userId);
        }
        return;
    }

    /**
     * Save a module result
     *
     * @param $moduleEntry
     * @param $userId
     * @return null
     * @throws Exception
     */
    function saveModuleResult($moduleEntry, $userId) {
        // check a module result doesn't already exist
        $criteria = craft()->elements->getCriteria(ElementType::Entry);
        $criteria->section = 'results';
        $criteria->type = 'moduleResult';
        $criteria->limit = 1;
        $criteria->authorId = $userId;
        $criteria->relatedTo = ['targetElement' => $moduleEntry , 'field' => 'resultModule'];
        // @todo error reporting?
        if ($criteria->count()) {
            return;
        }
        $resultEntry = new EntryModel();
        $resultEntry->sectionId = $this->sectionIdResults;
        $resultEntry->typeId = $this->typeIdModuleResult;
        $resultEntry->enabled = true;
        $resultEntry->authorId = $userId;
        $resultEntry->setContentFromPost([
            'resultModule' => array($moduleEntry->id),
        ]);
        // add expiry date based on module setting
        if ($moduleEntry->moduleExpiryDays) {
            $resultEntry->expiryDate = (time() + ($moduleEntry->moduleExpiryDays * 86400));
        }
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
        $criteria->type = 'unitResult';
        $criteria->resultEvidence = ':notempty:';
        $criteria->limit = $limit;
        $criteria->resultStatus = 'pending';
        $criteria->order = 'postDate desc';
        // limit by subordinates if team or company manager
        if ( ! $manager->isInGroup('schemeManager') && ! $manager->admin()) {
            $subordinateIds = craft()->lantra_users->getManagerSubordinateIds($manager, true);
            if ( ! count($subordinateIds)) {
                return null;
            }
            $criteria->authorId = $subordinateIds;
        }
        return ($count) ? $criteria->count() : $criteria;
    }

    /**
     * Return all expiring module result entries
     *
     * @param null $userId
     * @param int $days
     * @param int $limit
     * @return ElementCriteriaModel
     * @throws Exception
     */
    public function getManagerExpiringResults($userId = null, $days = 'all', $limit = 10) {
        return $this->getManagerResults($userId, true, $days, $limit);
    }

    /**
     * Return all recent module result entries
     *
     * @param null $userId
     * @param int $days
     * @param int $limit
     * @return ElementCriteriaModel
     * @throws Exception
     */
    public function getManagerRecentResults($userId = null, $days = 'all', $limit = 10) {
        return $this->getManagerResults($userId, false, $days, $limit);
    }

    /**
     * Return user module results for a manager
     *
     * @param null $userId
     * @param bool $expiring
     * @param int $days
     * @return ElementCriteriaModel|null
     * @throws mixed
     */
    private function getManagerResults($userId = null, $expiring = true, $days = 'all', $limit = 10) {
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
        $criteria->type = 'moduleResult';
        if ($expiring) {
            $criteria->expiryDate = $days != 'all' ? '<'. (time() + ($days*86400)) : ':notempty:';
            $criteria->order = 'expiryDate asc';
        }
        elseif ($days != 'all') {
            $criteria->postDate = '>' . (time() - ($days*86400));
        }
        $criteria->limit = $limit;
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
     * Return all unit result entries
     *
     * @param null $userId
     * @param int $limit
     * @return mixed
     * @throws mixed
     */
    public function getManagerUnitResults($userId = null, $limit = 10) {
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
     * Return all blocked unit result entries
     *
     * @param null $userId
     * @param int $limit
     * @return mixed
     * @throws mixed
     */
    public function getManagerBlockedUnitResults($userId = null, $limit = 10) {
        if ( ! is_null($userId)) {
            $manager = craft()->users->getUserById($userId);
        }
        else {
            $manager = craft()->userSession->getUser();
        }
        if ( ! $manager) {
            return null;
        }
        // This is a fairly complex query which may cause performance issues when we have lots of results to query.
        // It looks for unitResult type results and counts the existing attempts and returns the ids where that
        // total is equal or greater than testMaxAttempts value for the unit.
        $query = craft()->db->createCommand();
        $subAttempts = '(SELECT COUNT(*) FROM {{relations}} AS r WHERE r.fieldId = 58 AND r.sourceId = el.id)';
        $subUnitId = '(SELECT targetId FROM {{relations}} AS r WHERE r.fieldId = 26 AND r.sourceId = el.id)';
        $query->select([
            'el.id',
            $subAttempts . ' as resultAttempts',
            'unitEntry.field_testMaxAttempts as maxAttempts',
            'unitRelation.targetId as unitEntryId'
            ])
            ->from('{{elements}} as el')
            ->join('{{entries}} AS e', 'e.id = el.id')
            ->join('{{content}} AS c', 'c.elementId = el.id')
            ->join('{{relations}} AS unitRelation', 'unitRelation.sourceId = el.id')
            ->join('{{content}} AS unitEntry', 'unitEntry.elementId = ' . $subUnitId)
            ->where('e.sectionId = 10 AND e.typeId = 10 AND unitRelation.fieldId = 26 AND unitRelation.sourceId = el.id')
            ->having('maxAttempts > 0 AND resultAttempts >= maxAttempts');
        // get all blocked results
        $results = $query->queryAll();
        $blockedResultsIds = [];
        foreach($results as $row) {
            $blockedResultsIds[] = $row['id'];
        }
        // no blocked results exist
        if (! count($blockedResultsIds)) {
            return;
        }
        // now get this managers blocked results
        $criteria = craft()->elements->getCriteria(ElementType::Entry);
        $criteria->section = 'results';
        $criteria->type = 'unitResult';
        $criteria->limit = $limit;
        $criteria->id = $blockedResultsIds;
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
}
