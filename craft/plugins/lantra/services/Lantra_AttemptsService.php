<?php
namespace Craft;

class Lantra_AttemptsService extends BaseApplicationComponent
{
    private $sectionIdAttempts = 12;

    /**
     * @param $attemptEntry
     */
    function markAttempt($attemptEntry) {
        /* @var $answerBlock MatrixBlockModel */
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
     * @param $questionBlock
     * @param $answer
     * @return bool
     */
    function markQuestion($questionBlock, $answer) {
        if ($questionBlock->type == 'trueFalse') {
            return $questionBlock->answer && ($answer == 'true');
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
