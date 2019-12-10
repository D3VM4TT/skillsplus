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
    public function onBeforeSaveAttempt(Entry $entry, Event $event) {
        if ($event->params['isNewEntry']) {
            $unitEntry = $entry->attemptUnit->first();
            if (!is_object($unitEntry) || !Lantra::$app->attempts->canAttempt($entry->authorId, $unitEntry)) {
                $event->performAction = false;
                Craft::$app->request->redirect('/unit/' . $unitEntry->id);
            }
        }
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
            $answerBlock->setContentFromPost(array(
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
