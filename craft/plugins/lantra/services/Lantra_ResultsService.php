<?php
namespace Craft;

class Lantra_ResultsService extends BaseApplicationComponent
{
    // @todo move ids to config?
    private $sectionIdResults = 10;
    private $typeIdUnitResult = 10;
    private $typeIdModuleResult = 14;

    /**
     * Save a test attempt
     *
     * @param $attemptEntry
     * @return null
     * @throws Exception
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
        // create result entry
        $resultEntry = new EntryModel();
        $resultEntry->sectionId = $this->sectionIdResults;
        $resultEntry->typeId = $this->typeIdUnitResult;
        $resultEntry->enabled = true;
        $resultEntry->authorId = $user->id;
        $resultEntry->setContentFromPost([
            'resultStatus' => $passed ? 'endorsed' : 'failed',
            'resultUnit' => array($unitEntry->id),
            'resultAttempt' => array($attemptEntry->id),
            'resultScore' => $score
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
        $user = craft()->userSession->getUser();
        $jobRoles = $user->userRole;
        if ( ! $jobRoles->total()) {
            return;
        }
        // get all modules related to their job roles
        $criteria = craft()->elements->getCriteria(ElementType::Entry);
        $criteria->section = 'modules';
        $criteria->limit = null;
        $criteria->relatedTo = ['targetElement' => $jobRoles];
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
        $resultEntries = $this->getModuleBestResults($moduleEntry, $userId);
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
        $criteria->relatedTo = ['targetElement' => $moduleEntry];
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
     * Get module best user results grouped by unit ID
     *
     * @param $moduleEntry
     * @param $userId
     * @return array
     * @throws Exception
     */
    function getModuleBestResults($moduleEntry, $userId) {
        $unitIds = $this->getModuleUnitIds($moduleEntry);
        $criteria = craft()->elements->getCriteria(ElementType::Entry);
        $criteria->section = 'results';
        $criteria->type = 'unitResult';
        $criteria->authorId = $userId;
        $criteria->limit = null;
        $criteria->relatedTo = ['targetElement' => $unitIds];
        $resultEntries = $criteria->find();
        $return = [];
        foreach ($resultEntries as $resultEntry) {
            $unitId = $resultEntry->resultUnit->first()->id;
            // only add result if new or better score
            if ( ! isset($return[$unitId]) || ($resultEntry->resultScore > $return[$unitId]->resultScore)) {
                $return[$unitId] = $resultEntry;
            }
        }
        return $return;
    }

    /**
     * Return all expiring module result entries
     *
     * @param null $userId
     * @param null $days
     * @return mixed
     * @throws Exception
     */
    public function getManagerExpiringResults($userId = null, $days = null) {
        return $this->getManagerResults($userId, true, $days);
    }

    /**
     * Return all recent module result entries
     *
     * @param null $userId
     * @param null $days
     * @return mixed
     * @throws Exception
     */
    public function getManagerRecentResults($userId = null, $days = null) {
        return $this->getManagerResults($userId, false, $days);
    }

    /**
     * Return user module results for a manager
     *
     * @param null $userId
     * @param bool $expiring
     * @param int $days
     * @return mixed
     * @throws Exception
     */
    private function getManagerResults($userId = null, $expiring = true, $days = 7) {
        if ( ! is_null($userId)) {
            $manager = craft()->users->getUserById($userId);
        }
        else {
            $manager = craft()->userSession->getUser();
        }
        if ( ! $manager) {
            return null;
        }
        $subordinateIds = craft()->lantra_users->getManagerSubordinateIds($manager, true);
        $criteria = craft()->elements->getCriteria(ElementType::Entry);
        $criteria->section = 'results';
        $criteria->type = 'moduleResult';
        if ($expiring) {
            $criteria->expiryDate = $days ? '<'. (time() + ($days*86400)) : ':notempty:';
            $criteria->order = 'expiryDate asc';
        }
        else {
            $criteria->postDate = '>' . time() - ($days*86400);
        }
        $criteria->limit = null;
        $criteria->authorId = $subordinateIds;
        return $criteria;
    }
}
