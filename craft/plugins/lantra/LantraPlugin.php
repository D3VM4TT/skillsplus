<?php

namespace Craft;

class LantraPlugin extends BasePlugin
{
    public $sectionIdResults = 10;
    public $sectionIdAttempts = 12;
    public $typeIdUnitResult = 10;
    public $typeIdModuleResult = 14;

    function getName()
    {
        return Craft::t('Lantra');
    }

    function getVersion()
    {
        return '0.0.1';
    }

    function getDeveloper()
    {
        return 'Traffic Marketing';
    }

    function getDeveloperUrl()
    {
        return 'http://thisistraffic.co';
    }

    public function init()
    {
        parent::init();

        // Stop deletes
        craft()->on('elements.onBeforePerformAction', function(Event $event) {
            $action = $event->params['action']->classHandle;
            if ($action == 'Delete' && ! craft()->request->isCpRequest()){
                $event->performAction = false;
            }
        });

        // Stop deletes
        craft()->on('users.onBeforeDeleteUser', function(Event $event) {
            if ( ! craft()->request->isCpRequest()){
                $event->performAction = false;
            }
        });

        craft()->on('entries.onSaveEntry', function(Event $event) {
            $entry = $event->params['entry'];
            // Mark unit attempt and create result entry
            if ($event->params['isNewEntry'] && $entry->sectionId == $this->sectionIdAttempts && ! craft()->request->isCpRequest()){
                $this->markAttempt($entry);
                $this->saveUnitResult($entry);
            }
            // Check unit result for new module result
            if ($entry->sectionId == $this->sectionIdResults && $entry->type == 'unitResult') {
                $this->checkUnitResult($entry);
            }
        });
    }

    function markAttempt($attemptEntry) {
        foreach ($attemptEntry->attemptAnswers as $answerBlock) {
           $questionBlock = craft()->matrix->getBlockById($answerBlock->questionId);
           $correct = $this->markQuestion($questionBlock, $answerBlock->answer);
           $answerBlock->setContentFromPost(array(
               'question' => $questionBlock->question,
               'correct' => $correct
           ));
           craft()->matrix->saveBlock($answerBlock);
        }
    }

    function markQuestion($questionBlock, $answer) {
        // True/False
        if ($questionBlock->type == 'trueFalse') {
            return $questionBlock->answer && ($answer == 'true');
         }
        // Choices
        elseif ($questionBlock->type == 'choices') {
            foreach($questionBlock->answers as $row) {
                if ($row['answer'] == $answer) {
                    return $row['correct'] == 1;
                }
            }
        }
        // text type just needs some text
        else {
            return ! empty(trim($answer));
        }
    }

    function saveUnitResult($attemptEntry) {

        $attemptEntry = craft()->entries->getEntryById($attemptEntry->id);
        $unitEntry = $attemptEntry->attemptUnit->first();
        $user = craft()->userSession->getUser();

        $total = count($attemptEntry->attemptAnswers);
        $correct = 0;

        foreach ($attemptEntry->attemptAnswers as $answerBlock) {
            if ($answerBlock->correct) {
                $correct++;
            }
        }

        $score = round($correct / $total * 100);
        $passed = $score >= $unitEntry->getContent()->testPassPercent;

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

        if ( ! craft()->entries->saveEntry($resultEntry)) {

        }
    }

    function checkUnitResult($resultEntry) {
        // the related unit id
        $resultUnitEntry = $resultEntry->resultUnit->first();
        if ( ! $resultUnitEntry) {
            return;
        };
        // get the user job roles
        $user = craft()->userSession->getUser();
        $jobRoles = $user->userRole;
        if ( ! count($jobRoles)) {
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
    }

    /**
     * Check whether to award the module result
     *
     * @param $moduleEntry
     * @param $userId
     */
    function checkModuleResult($moduleEntry, $userId) {
        $resultEntries = $this->getModuleUnitResults($moduleEntry, $userId);
        if ( ! count($resultEntries)) {
            return null;
        }
        $points = 0;
        foreach ($resultEntries as $resultEntry) {
            $unitEntry = $resultEntry->resultUnit->first();
            if ($resultEntry->resultStatus == 'endorsed') {
                $points += $unitEntry->unitValue;
            }
        }
        if ($points >= $moduleEntry->moduleCompletedValue) {
            $this->saveModuleResult($moduleEntry);
        }
    }

    /**
     * Save a module result
     *
     * @param $moduleEntry
     * @param $userId
     */
    function saveModuleResult($moduleEntry, $userId) {
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

        // set a qualification expiry
        if ($moduleEntry->moduleExpiryDays) {
            $resultEntry->expiryDate = (time() + ($moduleEntry->moduleExpiryDays * 86400));
        }

        if ( ! craft()->entries->saveEntry($resultEntry)) {

        }
    }

    /**
     * Get all the module unit IDs
     *
     * @param $moduleEntry
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
     * Get module user results grouped by unit ID
     *
     * @param $moduleEntry
     * @return array
     */
    function getModuleUnitResults($moduleEntry, $userId) {
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
            $return[$resultEntry->resultUnit->first()->id] = $resultEntry;
        }
        return $return;
    }
}
