<?php

namespace Craft;

class LantraPlugin extends BasePlugin
{
    public $sectionIdResults = 10;
    public $sectionIdAttempts = 12;

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

        // Mark attempt and create result
        craft()->on('entries.onSaveEntry', function(Event $event) {
            $entry = $event->params['entry'];
            if ($event->params['isNewEntry'] && $entry->sectionId == $this->sectionIdAttempts && ! craft()->request->isCpRequest()){
                $this->markAttempt($entry);
                $this->saveResult($entry);
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

    function saveResult($entry) {

        $attemptEntry = craft()->entries->getEntryById($entry->id);
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
}
