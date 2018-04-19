<?php

namespace Craft;

class LantraPlugin extends BasePlugin
{
    public $sectionIdResults = 10;
    public $sectionIdAttempts = 12;
    public $sectionIdQualifications = 13;

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
            // Mark attempt and create result
            if ($event->params['isNewEntry'] && $entry->sectionId == $this->sectionIdAttempts && ! craft()->request->isCpRequest()){
                $this->markAttempt($entry);
                $this->saveResult($entry);
            }
            // Check result for new qualifications
            if ($entry->sectionId == $this->sectionIdResults) {
                $this->checkResult($entry);
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

    function saveResult($attemptEntry) {

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
        $resultEntry->enabled = true;
        $resultEntry->authorId = $user->id;
        $resultEntry->setContentFromPost([
            'resultEndorsedDate' => time(),
            'resultStatus' => 'endorsed',
            'resultUnit' => array($unitEntry->id),
            'resultAttempt' => array($attemptEntry->id),
            'resultScore' => $score,
            'resultPassed' => $passed
        ]);

        if ( ! craft()->entries->saveEntry($resultEntry)) {

        }
    }

    function checkResult($resultEntry) {
        // the related unit id
        $unitId = $resultEntry->resultUnit->first()->id;
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
            if (in_array($unitId, $unitIds)) {
                $this->checkModuleQualification($moduleEntry);
            }
        }
    }

    /**
     * Check whether to award a qualification
     *
     * @param $moduleEntry
     */
    function checkModuleQualification($moduleEntry) {

        $resultEntries = $this->getModuleUserResults($moduleEntry);
        $points = 0;

        foreach ($resultEntries as $resultEntry) {
            $unitEntry = $resultEntry->resultUnit->first();
            if ($unitEntry->unitType == 'evidence' && $resultEntry->resultStatus == 'endorsed' || $unitEntry->unitType == 'elearning' && $resultEntry->resultPassed) {
                $points += $unitEntry->unitValue;
            }
        }

        if ($points >= $moduleEntry->moduleCompletedValue) {
            $this->saveQualification($moduleEntry);
        }
    }

    function saveQualification($moduleEntry) {
        $user = craft()->userSession->getUser();

        $criteria = craft()->elements->getCriteria(ElementType::Entry);
        $criteria->section = 'qualifications';
        $criteria->limit = 1;
        $criteria->authorId = $user->id;
        $criteria->relatedTo = ['targetElement' => $moduleEntry];

        if ($criteria->count()) {
            return;
        }

        $qualificationEntry = new EntryModel();

        $qualificationEntry->sectionId = $this->sectionIdQualifications;
        $qualificationEntry->enabled = true;
        $qualificationEntry->authorId = $user->id;
        $qualificationEntry->setContentFromPost([
            'qualificationModule' => array($moduleEntry->id),
        ]);

        // set a qualification expiry
        if ($moduleEntry->moduleExpiryDays) {
            $qualificationEntry->expiryDate = (time() + ($moduleEntry->moduleExpiryDays * 86400));
        }

        if ( ! craft()->entries->saveEntry($qualificationEntry)) {

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
    function getModuleUserResults($moduleEntry) {
        $unitIds = $this->getModuleUnitIds($moduleEntry);

        $criteria = craft()->elements->getCriteria(ElementType::Entry);
        $criteria->section = 'results';
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
