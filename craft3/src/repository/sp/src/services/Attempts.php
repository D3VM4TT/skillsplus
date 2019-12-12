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

class Attempts extends Component
{
    /**
     * @param Entry $entry
     * @param Event $event
     */
    public function onBeforeSaveAttempt(Event $event, Entry $entry) {
        if ($entry->isNew) {
            $unitEntry = $entry->attemptUnit->one();
            if (!is_object($unitEntry) || !Lantra::$app->attempts->canAttempt($entry->authorId, $unitEntry)) {
                $event->performAction = false;
                Craft::$app->request->redirect('/unit/' . $unitEntry->id);
            }
        }
    }

    /**
     * Save a test attempt
     *
     * @param $attemptEntry
     * @return null
     * @throws Mixed
     */
    function onSaveAttempt(Event $event, Entry $entry) {
        $attemptEntry = Craft::$app->entries->getEntryById($entry->id);
        $unitEntry = $attemptEntry->attemptUnit->one();
        ## author sent from form
        $authorId = Craft::$app->request->getParam('authorId');
        if ($authorId && false != $user = Craft::$app->users->getUserById($authorId)) {
            $attemptEntry->authorId = $user->id;
            $attemptEntry->getContent()->title = '[unit ' . $unitEntry->id . '] ' . $user->getFullName();
            Craft::$app->elements->saveElement($attemptEntry, false);
        }
        $total = count($attemptEntry->attemptAnswers);
        $correct = 0;
        ## loop through answers and count correct
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
        $resultEntry->setAttributes([
            'resultUnit' => array($unitEntry->id),
            'resultStatus' => $resultStatus,
            'resultAttempts' => $resultAttempts,
            'resultScore' => $resultScore
        ]);
        if ($passed) {
            $resultEntry->setAttributes([
                'resultEndorsedDate' => time()
            ]);
        }
        // @todo error reporting?
        if ( ! Craft::$app->elements->saveElement($resultEntry)) {
            return;
        }
        return;
    }

    /**
     * Mark an attempt entry
     *
     * @param $attemptEntry
     * @throws mixed
     */
    public function markAttempt($attemptEntry) {
        foreach ($attemptEntry->attemptAnswers as $answerBlock) {
            $questionBlock = craft()->matrix->getBlockById($answerBlock->questionId);
            $correct = $this->markQuestion($questionBlock, $answerBlock->answer);
            $answerBlock->setAttributes(array(
                'question' => $questionBlock->question,
                'correct' => $correct
            ));
            craft()->matrix->saveBlock($answerBlock);
        }
    }

    /**
     * Check a user can attempt
     *
     * @param $userId
     * @param $unitId
     * @return bool
     */
    public function canAttempt($userId, $unitEntry) {
        // result does not exist for user
        if (! is_object($unitEntry) || false == $resultEntry = Lantra::$app->results->getUnitResult($userId, $unitEntry->id)) {
            return true;
        }
        return (bool) $this->remainingAttempts($unitEntry, $resultEntry);
    }

    /**
     * Return remaining number of attempts for a user on a unit
     *
     * @param EntryModel $unitEntry
     * @param EntryModel $resultEntry
     * @return mixed
     */
    public function remainingAttempts($unitEntry, $resultEntry) {
        if ( ! $unitEntry->testMaxAttempts) {
            return 'unlimited';
        }
        if ($resultEntry) {
            return max((int)($unitEntry->testMaxAttempts - $resultEntry->resultAttempts->total()), 0);
        }
        return (int)$unitEntry->testMaxAttempts;
    }

    /**
     * Mark a question
     *
     * @param $questionBlock
     * @param $answer
     * @return bool
     */
    private function markQuestion($questionBlock, $answer) {
        if ($questionBlock->type == 'trueFalse') {
            return ($questionBlock->answer == 0 && $answer == 'false') || ($questionBlock->answer == 1 && $answer == 'true');
        }
        elseif ($questionBlock->type == 'choices') {
            foreach($questionBlock->answers as $row) {
                if ($row['answer'] == $answer) {
                    return $row['correct'] == 1;
                }
            }
        }
        else {
            return ! empty(trim($answer));
        }
    }
}
