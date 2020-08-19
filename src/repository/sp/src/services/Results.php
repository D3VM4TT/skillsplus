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
use craft\db\Query;
use craft\elements\Category;
use craft\elements\Entry;
use craft\elements\User;
use craft\helpers\DateTimeHelper;
use craft\events\ModelEvent;
use DateTime;
use yii\base\Event;

use lantra\sp\Plugin as Lantra;
use lantra\sp\helpers\LantraHelper;
use lantra\sp\helpers\CycleHelper;
use lantra\sp\models\Cycle;
use lantra\sp\models\CyclePeriod;

use verbb\supertable\SuperTable;
use verbb\supertable\elements\SuperTableBlockElement;

class Results extends Component
{
    private $sectionIdResults = 10;
    private $typeIdUnitResult = 10;
    private $typeIdModuleResult = 14;
    private $dateFormat = 'd/m/y';

    /**
     * @param $event
     * @param Entry $entry
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\SyntaxError
     * @throws \yii\base\InvalidConfigException
     */
    public function onBeforeSaveResult(ModelEvent $event, Entry $entry)
    {
        $userId = Craft::$app->getUser()->id;
        $unitEntry = $entry->resultUnit ? $entry->resultUnit->one() : null;
        if ($entry->type == 'unitResult' && !$unitEntry) {
            $event->isValid = false;
            $entry->addError('resultUnit', 'You must select a Result Unit for Unit Results');
        }
        if ($entry->type == 'moduleResult' && $entry->resultModule) {
            $moduleEntry = $entry->resultModule->one();
            if ($moduleEntry->type == 'cpd') {
                if (!$entry->cycleName) {
                    $cycle = CycleHelper::getResultCycle($entry);
                    $entry->setFieldValue('cycleName', $cycle->name);
                    $entry->setFieldValue('cycleStartDate', $cycle->startDate);
                    $entry->setFieldValue('cycleFinishDate', $cycle->finishDate);
                }
            }
        }
        if ($entry->type == 'unitResult' || $entry->type == 'userResult') {
            ## set result owner as user
            if (!$entry->resultOwner) {
                $entry->setFieldValue('resultOwner', [$userId]);
            }
            ## copy manager endorsement level from unit for submitted evidence
            if ($entry->resultEvidence && $entry->type == 'unitResult' && $unitEntry) {
                $entry->setFieldValue('unitEndorsementManagerLevel', $unitEntry->unitEndorsementManagerLevel);
            }
            ## set author
            $author = null;
            $authorId = Craft::$app->request->getParam('author');
            if (is_array($authorId)) {
                $author = Craft::$app->users->getUserById($authorId[0]);
            }
            ## make sure title is correct
            if ($author && $entry->type == 'unitResult' && $entry->resultEvidence && $unitEntry) {
                $entry->title = '[unit ' . $unitEntry->id . '] ' . $author->firstName . ' ' . $author->lastName;
            }
            $dateTime = new \DateTime();
            ## set comment
            $comment = Craft::$app->request->getParam('comment');
            if ($comment) {
                $this->addComment($entry, $comment);
            }
            $fields = Craft::$app->request->getParam('fields');
            $resultUnitId = isset($fields['resultUnit']) && is_array($fields['resultUnit']) && count($fields['resultUnit']) ? $fields['resultUnit'][0] : null;
            ## set result title
            if ($entry->type == 'userResult' && $resultUnitId) {
                $unitEntry = Craft::$app->entries->getEntryById($resultUnitId);
                if ($unitEntry) {
                    $entry->title = $unitEntry->title;
                }
            }
            ## check endorsed change
            $oldEntry = $entry->id ? Craft::$app->entries->getEntryById($entry->id) : null;
            $currentUser = Craft::$app->getUser();
            if (!Craft::$app->request->isCpRequest && $entry->resultStatus == 'endorsed') {
                if ($entry->authorId != $currentUser->id && Lantra::$app->users->isManager($entry->authorId)) {
                    $entry->setFieldValue('resultStatus', 'endorsed');
                    $newEndorse = Craft::$app->request->getParam('newEndorse');
                    if (!$oldEntry || !$oldEntry->resultEndorsedDate || $newEndorse) {
                        $entry->setFieldValue('resultEndorsedDate', $dateTime);
                        $entry->setFieldValue('resultEndorsedUser', [$currentUser->id]);
                    }
                }
                else {
                    $entry->setFieldValue('resultStatus',$oldEntry ? $oldEntry->resultStatus : 'pending');
                }
            }
            ## force clear endorsed date if pending
            if ($entry->resultStatus != 'endorsed') {
                $entry->setFieldValue('resultEndorsedDate', null);
                $entry->setFieldValue('resultEndorsedUser', []);
            }
            ## check change from draft to pending
            if ($oldEntry && $oldEntry->resultStatus == 'draft' && $entry->resultStatus == 'pending') {
                ## send notification
                if (Lantra::$app->results->notifyManagerEndorsementResult($entry)) {
                    Lantra::$app->notify->sendManagerEndorsementResult($entry);
                }
            }

            $request = Craft::$app->getRequest();
            if (!$request->isCpRequest) {
                $userStartDate = Craft::$app->request->getParam('userStartDate');
                $userFinishDate = Craft::$app->request->getParam('userFinishDate');
                $userExpiryDate = Craft::$app->request->getParam('userExpiryDate');
                $dateFormat = 'Y-m-d H:i:s';
                if ($userStartDate && false != $date = $dateTime->createFromFormat($dateFormat, $userStartDate)) {
                    $userStartDate = $date->format(DATE_ATOM);
                    $entry->setFieldValue('resultStartDate', $userStartDate);
                }
                if ($userFinishDate && false != $date = $dateTime->createFromFormat($dateFormat, $userFinishDate)) {
                    $userFinishDate = $date->format(DATE_ATOM);
                    $entry->setFieldValue('resultFinishDate', $userFinishDate);
                }
                if ($userExpiryDate && false != $date = $dateTime->createFromFormat($dateFormat, $userExpiryDate)) {
                    $userExpiryDate = $date->getTimestamp();
                    $entry->expiryDate = $date->format(DATE_ATOM);
                }
                ## validate dates
                if ($userStartDate && $userFinishDate && $userStartDate > $userFinishDate) {
                    $entry->addError('resultStartDate', 'Start date cannot be later than finish date.');
                    $event->isValid = false;
                }
                if ($userStartDate && $userExpiryDate && $userStartDate > $userExpiryDate) {
                    $entry->addError('resultStartDate', 'Start date cannot be later than expiry date.');
                    $event->isValid = false;
                }
                if ($userFinishDate && $userExpiryDate && $userFinishDate > $userExpiryDate) {
                    $entry->addError('resultFinishDate', 'Finish date cannot be later than expiry date.');
                    $event->isValid = false;
                }
            }
        }
        if (!$event->isValid) {
            Craft::$app->urlManager->setRouteParams(['resultEntry' => $entry]);
        }
    }

    /**
     * @param ModelEvent $event
     * @param Entry $entry
     * @throws \Throwable
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\SyntaxError
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\base\NotSupportedException
     * @throws \yii\db\Exception
     */
    function onSaveResult(ModelEvent $event, Entry $entry)
    {
        $userId = Craft::$app->getUser()->id;
        if ($entry->type == 'unitResult' || $entry->type == 'userResult') {
            ## send notification
            if ($this->notifyManagerEndorsementResult($entry)){
                Lantra::$app->notify->sendManagerEndorsementResult($entry);
            }
            if ($entry->type == 'userResult') {
                $this->checkUserResult($entry);
            }
            if ($entry->type == 'unitResult') {
                $this->checkUnitResult($entry);
            }
        }
        ## save unit result in user result cache (if enabled)
        if ($entry->enabled && $entry->type == 'unitResult') {
            $this->saveUserResultCache($entry->authorId, $entry);
        }
        if ($entry->type == 'moduleResult') {
            if (null !== $moduleEntry = $entry->resultModule->one()) {
                ## save new module result
                if ($event->isNew) {
                    if ($moduleEntry->type == 'cpd' && $moduleEntry->cycleNotifyStart) {
                        Lantra::$app->notify->sendCycleStart($entry);
                    }
                }
                if ($moduleEntry->cycleNotifyComplete && $entry->resultStatus == 'complete') {
                    Lantra::$app->notify->sendCycleComplete($entry);
                }
            }
        }
    }

    /**
     * @param Event $event
     * @param Entry $entry
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     */
    public function onDeleteResult(Event $event, Entry $entry)
    {
        ## check the cpd module result
        if (null != $moduleResultEntry = $entry->resultModuleResult->one()) {
            $moduleEntry = $moduleResultEntry->resultModule->one();
            if ($moduleEntry && $moduleEntry->type == 'cpd') {
                $this->checkModuleResult($moduleEntry, $entry->authorId, $moduleResultEntry);
            }
        }
    }

    /**
     * @param ModelEvent $event
     * @param Entry $entry
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     */
    public function onBeforeSaveAttempt(ModelEvent $event, Entry $entry)
    {
        if ($event->isNew) {
            $unitEntry = $entry->attemptUnit->one();
            if (!$unitEntry) {
                $event->isValid = false;
                $entry->addError('attemptUnit', 'You must select an attempt unit for attempt results.');
            }
            if (!is_object($unitEntry) || !$unitEntry || !Lantra::$app->attempts->canAttempt($entry->authorId, $unitEntry)) {
                $event->isValid = false;
                $entry->addError('resultUnit', 'You have no remaining attempts.');
            }
            ## create result entry
            if (false == $resultEntry = $this->getUnitResult($entry->authorId, $unitEntry->id)) {
                $resultEntry = new Entry();
                $resultEntry->sectionId = $this->sectionIdResults;
                $resultEntry->typeId = $this->typeIdUnitResult;
                $resultEntry->enabled = true;
                $resultEntry->authorId = $entry->authorId;
                $resultEntry->resultUnit = [$unitEntry->id];
                $resultEntry->resultStatus = 'active';
                if (!Craft::$app->elements->saveElement($resultEntry)) {
                    $event->isValid = false;
                    $entry->addError('attemptUnit', 'Could not save result entry.');
                }
            }
        }
    }

    /**
     * @param ModelEvent $event
     * @param Entry $entry
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     */
    function onSaveAttempt(ModelEvent $event, Entry $entry)
    {
        $unitEntry = $entry->attemptUnit->one();
        ## author sent from form
        $authorId = Craft::$app->request->getParam('authorId');
        if ($authorId && false != $user = Craft::$app->users->getUserById($authorId)) {
            $entry->authorId = $user->id;
            $entry->title = '[unit ' . $unitEntry->id . '] ' . $user->getFullName();
        }
        ## mark attempt
        Lantra::$app->attempts->markAttempt($entry);
        $total = count($entry->attemptAnswers);
        $correct = 0;
        ## loop through answers and count correct
        foreach ($entry->attemptAnswers->all() as $answerBlock) {
            if ($answerBlock->correct) {
                $correct++;
            }
        }
        ## calculate percentage
        $score = round($correct / $total * 100);
        ## passed if greater than unit setting
        $passed = $score >= $unitEntry->testPassPercent;
        ## update result
        if (false != $resultEntry = $this->getUnitResult($entry->authorId, $unitEntry->id)) {
            $resultAttempts = $resultEntry->resultAttempts ? array_merge($resultEntry->resultAttempts->ids(), [$entry->id]) : [$entry->id];
            $resultEntry->setFieldValue('resultAttempts', $resultAttempts);
            if ($score > $resultEntry->resultScore) {
                $resultEntry->resultStatus = $passed ? 'endorsed' : 'active';
                $resultEntry->resultScore = $score;
            }
            if ($passed) {
                $resultEntry->resultEndorsedDate = time();
            }
            Craft::$app->elements->saveElement($resultEntry, false);
        }
    }

    /**
     * @param Entry $entry
     * @param null $userId
     * @return bool
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     */
    function endorseResult(Entry $entry, $userId = null)
    {
        if (is_null($userId)) {
            $userId = Craft::$app->getUser()->id;
        }
        $entry->setFieldValues([
            'resultStatus' => 'endorsed',
            'resultEndorsedDate' => DateTimeHelper::currentUTCDateTime(),
            'resultEndorsedUser' => [$userId]
        ]);
        return Craft::$app->elements->saveElement($entry, false);
    }
    /**
     * @param $entry
     * @param $comment
     * @param null $userId
     * @return mixed
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\SyntaxError
     * @throws \yii\base\InvalidConfigException
     */
    function addComment($entry, $comment, $userId = null)
    {
        if (is_null($userId)) {
            $userId = Craft::$app->getUser()->id;
        }
        $field = Craft::$app->getFields()->getFieldByHandle('resultComments');
        $blockTypes = SuperTable::$plugin->getService()->getBlockTypesByFieldId($field->id);
        $blockType = $blockTypes[0];
        ## not sure why we have to run this loop and resave the previous data
        $tableData = [];
        foreach($entry->resultComments->all() as $key => $row) {
            $tableData[$key] =  [
                'type' => $blockType->id,
                'enabled' => true,
                'fields' => [
                    'user' => [$row->user->one()->id],
                    'date' => DateTimeHelper::currentUTCDateTime(),
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
                'date' => DateTimeHelper::currentUTCDateTime(),
                'comment' => $comment,
                'read' => false
            ]
        ];
        $entry->setFieldValues(['resultComments' => $tableData]);
        Lantra::$app->notify->sendCommentUpdate($entry, $comment, $userId);
    }

    /**
     * @param SuperTableBlockElement $comment
     * @param $userId
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     */
    function readComment(SuperTableBlockElement $comment, $userId)
    {
        ## userId of result
        $resultAuthorId = $comment->getOwner()->author->id;
        $commentAuthorId = $comment->user->one()->id;
        if (($resultAuthorId == $userId && $commentAuthorId != $userId) || ($resultAuthorId != $userId && $commentAuthorId == $resultAuthorId)) {
            $comment->read = true;
            Craft::$app->elements->saveElement($comment);
        }
    }

    /**
     * @param $result
     * @return int
     */
    function unreadComments($result, $userId)
    {
        $unread = 0;
        $comments = $result->resultComments->all();
        foreach($comments as $comment) {
            $commentAuthorId = $comment->user->one()->id;
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
    function unitResultExists($userId, $unitId)
    {
        $criteria = Entry::find();
        $criteria->section = 'results';
        $criteria->type = 'unitResult';
        $criteria->authorId = $userId;
        $criteria->status = null;
        $criteria->relatedTo = ['targetElement' => $unitId, 'field' => 'resultUnit'];
        return $criteria->count() ? true : false;
    }

    /**
     * Get a unit result entry
     *
     * @param $userId
     * @param $unitIds
     * @return null
     * @throws Mixed
     */
    function countUnitResults($userId, $unitIds, $resultStatus = null)
    {
        $criteria = Entry::find();
        $criteria->section = 'results';
        $criteria->type = 'unitResult';
        $criteria->authorId = $userId;
        $criteria->status = ['live', 'expired'];
        if ($resultStatus) {
            $criteria->resultStatus = $resultStatus;
        }
        $criteria->relatedTo = ['targetElement' => $unitIds, 'field' => 'resultUnit'];
        return $criteria->count();
    }

    /**
     * Get a unit result entry
     *
     * @param $userId
     * @param $unitId
     * @return null
     * @throws Mixed
     */
    function getUnitResult($userId, $unitId, $cycleCode = null)
    {
        $criteria = Entry::find();
        $criteria->section = 'results';
        $criteria->type = 'unitResult';
        $criteria->limit = 1;
        $criteria->authorId = $userId;
        $criteria->relatedTo = ['targetElement' => $unitId, 'field' => 'resultUnit'];
        if ($cycleCode) {
            $criteria->resultRecurringCycleCode = $cycleCode;
        }
        return $criteria->one();
    }

    /**
     * @param $userId
     * @param $unitId
     * @param CyclePeriod $cycle
     * @param $moduleResultId
     * @return \craft\elements\db\ElementQueryInterface|\craft\elements\db\EntryQuery|null
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     */
    public function getRecurringResultsQuery($userId, $unitId, CyclePeriod $cycle, $moduleResultId)
    {
        if (false == $unitEntry = Craft::$app->entries->getEntryById($unitId)) {
            return null;
        }
        $recurringCycles = $cycle->getRecurring($unitEntry->unitRecurringPeriod);

        $validCodes = [];
        foreach($recurringCycles as $recurringCycle) {
            $validCodes[] = $recurringCycle->code;
        }

        if (count($validCodes)) {
            ## delete incomplete results that are no longer needed for this cycle (if cycle changed)
            $allResults = $this->getUnitResultsQuery($userId, $unitId, null, $moduleResultId)->all();
            foreach ($allResults as $result) {
                if (!in_array($result->resultRecurringCycleCode, $validCodes) && $result->resultStatus == 'incomplete') {
                    Craft::$app->elements->deleteElementById($result->id);
                }
            }
        }

        $startDate = $cycle->startDate;
        foreach($recurringCycles as $recurringCycle) {
            ## make sure the recurring results exist
            if (null == $resultEntry = $this->getUnitResult($userId, $unitId, $recurringCycle->code)) {
                $resultEntry = $this->createUnitResult($userId, $unitId, $moduleResultId, $recurringCycle, $startDate);
            }
            ## fix to update resultModuleResult if cycle has changed
            $this->setResultModuleResult($resultEntry, $moduleResultId);
        }

        return $this->getUnitResultsQuery($userId, $unitId, null, $moduleResultId);
    }

    /**
     * @param $resultEntry
     * @param $moduleResultId
     * @return bool
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     */
    function setResultModuleResult($resultEntry, $moduleResultId)
    {
        $resultEntry->setFieldValue('resultModuleResult', [$moduleResultId]);
        return Craft::$app->elements->saveElement($resultEntry);
    }

    /**
     * Gets all the result for a unit - must be linked to a module result entry of cpd
     *
     * @param $userId
     * @param $unitId
     * @param int $limit
     * @param null $moduleResultId
     * @return \craft\elements\db\ElementQueryInterface|\craft\elements\db\EntryQuery
     */
    function getUnitResultsQuery($userId, $unitId, $limit = 1, $moduleResultId = null)
    {
        $criteria = Entry::find();
        $criteria->section = 'results';
        $criteria->type = 'unitResult';
        $criteria->limit = $limit;
        $criteria->authorId = $userId;
        $criteria->orderBy = 'resultRecurringCycleCode';
        if ($moduleResultId) {
            $criteria->relatedTo = [
                'and',
                ['targetElement' => $unitId, 'field' => 'resultUnit'],
                ['targetElement' => $moduleResultId, 'field' => 'resultModuleResult']
            ];
        }
        else {
            $criteria->relatedTo = ['targetElement' => $unitId, 'field' => 'resultUnit'];
        }
        $criteria->status = ['live', 'expired'];
        return $criteria;
    }

    /**
     * @param $moduleResultId
     * @param null $status
     * @param bool $count
     * @param string $type
     * @param null $resultStatus
     * @return array|\craft\base\ElementInterface[]|Entry[]|int|string
     */
    function getModuleResultResults($moduleResultId, $status = null, $count = false, $type = 'unitResult', $resultStatus = null)
    {
        $criteria = Entry::find();
        $criteria->section = 'results';
        if ($type == 'both') {
            $type = ['userResult', 'unitResult'];
        }
        $criteria->type = $type;
        if ($status) {
            $criteria->status = [$status];
        } else {
            $criteria->status = ['live', 'expired'];
        }
        $criteria->relatedTo = ['targetElement' => $moduleResultId, 'field' => 'resultModuleResult'];
        if ($resultStatus) {
            $criteria->resultStatus($resultStatus);
        }
        return $count ? $criteria->count() : $criteria->all();
    }

    /**
     * Get a module result entry
     *
     * @param $userId
     * @param $moduleId
     * @param $create
     * @param $postDate
     * @return null
     * @throws Mixed
     */
    function getModuleResult($userId, $moduleId, $create = false, $postDate = null) {
        $criteria = Entry::find();
        $criteria->section = 'results';
        $criteria->type = 'moduleResult';
        $criteria->limit = 1;
        $criteria->authorId = $userId;
        $criteria->relatedTo = ['targetElement' => $moduleId, 'field' => 'resultModule'];
        ## postDate might be sent from Cycles
        if ($postDate) {
            $criteria->postDate = $postDate;
        }
        $existing = $criteria->one();
        if (!$existing && $create) {
            return $this->createModuleResult($userId, $moduleId);
        }
        return $existing;
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
        $resultUnitEntry = $resultEntry->resultUnit->one();
        $totalAttempts = $resultEntry->resultAttempts->count();
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
        ## only continue if status has changed
        if ($resultEntry->resultStatus == $resultStatus) {
            return;
        }
        $resultEntry->resultStatus = $resultStatus;
        ## bypass save entry to stop callback loop
        if (!Craft::$app->elements->saveElement($resultEntry, false)) {
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
        Lantra::$app->notify->sendManagerBlockedResult($resultEntry);
    }

    /**
     * Unlink unitResult attempts and set resultStatus to active
     *
     * @param $resultEntry
     * @return null
     * @throws null
     */
    function unblockResult($resultEntry) {
        $resultEntry->setFieldValue('resultAttempts', []);
        $resultEntry->setFieldValue('resultScore', 0);
        $this->setResultStatus($resultEntry,'active');
        Craft::$app->elements->saveElement($resultEntry);
    }

    /**
     * Check whether a user result has completed a module
     *
     * @param $resultEntry
     * @return null
     * @throws null
     */
    function checkUserResult($resultEntry) {
        ## the related module id
        $resultModuleEntry = $resultEntry->resultModule ? $resultEntry->resultModule->one() : null;
        if (!$resultModuleEntry) {
            return;
        }
        ## check the moduleResult
        $user = $resultEntry->author;
        $moduleResultEntry = $this->getModuleResult($user->id, $resultModuleEntry->id, true);
        $this->checkModuleResult($resultModuleEntry, $user->id, $moduleResultEntry);
    }

    /**
     * Check whether a unit result has completed a module
     *
     * @param $resultEntry
     * @return null
     * @throws null
     */
    function checkUnitResult($resultEntry) {
        ## the related unit id
        $resultUnitEntry = $resultEntry->resultUnit->one();
        if (!$resultUnitEntry) {
            return;
        };
        ## get the user job roles
        $user = $resultEntry->author;
        $jobRoles = $user->userRole;
        if (!$jobRoles->count()) {
            return;
        }
        ## if cpd (specific module result entry) check the specific module result
        $moduleResultEntry = null;
        if ($resultEntry->resultModuleResult) {
            $moduleResultEntry = $resultEntry->resultModuleResult->one();
            $moduleEntry = $this->getModuleResultModule($moduleResultEntry);
            if($moduleEntry && $moduleEntry->type == 'cpd') {
                return $this->checkModuleResult($moduleEntry, $user->id, $moduleResultEntry);
            }
        }
        ## get all modules related to their job roles
        $criteria = Entry::find();
        $criteria->section = 'modules';
        $criteria->limit = null;
        $criteria->relatedTo = ['targetElement' => $jobRoles, 'field' => 'moduleRoles'];
        $moduleEntries = $criteria->all();
        ## search for the relevant module (this unit may be part of multiple modules)
        foreach ($moduleEntries as $moduleEntry) {
            $unitIds = $this->getModuleUnitIds($moduleEntry);
            if (in_array($resultUnitEntry->id, $unitIds)) {
                $this->checkModuleResult($moduleEntry, $user->id, $moduleResultEntry);
            }
        }
        return;
    }

    /**
     * @param $moduleResultEntry
     * @return null
     */
    public function getModuleResultModule($moduleResultEntry)
    {
        if (!$moduleResultEntry || null == $moduleEntry = $moduleResultEntry->resultModule->one()) {
            return null;
        }
        return $moduleEntry;
    }

    /**
     * @param $moduleEntry
     * @param $userId
     * @param $moduleResultEntry
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     */
    public function checkModuleResult($moduleEntry, $userId, $moduleResultEntry = null)
    {
        ## linked module results
        if ($moduleResultEntry) {
            $unitResultEntries = $this->getModuleResultResults($moduleResultEntry->id, null, false, 'unitResult');
            $userResultEntries = $this->getModuleResultResults($moduleResultEntry->id, null, false, 'userResult');
        } else {
            $moduleResultEntry = $this->getModuleResult($userId, $moduleEntry->id, true);
            $unitResultEntries = $this->getModuleUnitResults($moduleEntry, $userId);
            $userResultEntries = $this->getModuleUserResults($moduleEntry, $userId);
        }

        $resultEntries = array_merge($unitResultEntries, $userResultEntries);

        if (!count($resultEntries)) {
            return;
        }
        $moduleResultExpiryTime = null;
        if ($moduleEntry->type == 'qualification') {
            ## set default module result expiry
            if ($moduleEntry->moduleExpiryDays) {
                $moduleResultExpiryTime = (time() + ($moduleEntry->moduleExpiryDays * 86400));
            }
        }
        $points = 0;
        $hours = 0;
        foreach ($resultEntries as $resultEntry) {
            if ($resultEntry->resultStatus == 'endorsed') {
                if ((int) $resultEntry->resultHours) {
                    $hours += (int) $resultEntry->resultHours;
                }
                ## unit results value is unit value
                if ($resultEntry->type == 'unitResult') {
                    $unitEntry = $resultEntry->resultUnit->one();
                    ## point overridden by unit group
                    $points += $this->getUnitPoints($unitEntry, $moduleEntry);
                }
                ## user result value is custom
                elseif ($resultEntry->type == 'userResult') {
                    if ((int) $resultEntry->resultPoints) {
                        $points += (int) $resultEntry->resultPoints;
                    }
                }
                ## check if result expiry is before default module expiry)
                if ($resultEntry->expiryDate && (is_null($moduleResultExpiryTime) || $resultEntry->expiryDate->getTimestamp() < $moduleResultExpiryTime)) {
                    $moduleResultExpiryTime = $resultEntry->expiryDate->getTimestamp();
                }
            }
        }
        $componentResults = $this->getComponentResults($moduleEntry, $unitResultEntries, $userResultEntries);
        $moduleResultEntry->setFieldValue('resultHours', $hours);
        $moduleResultEntry->setFieldValue('resultPoints', $points);
        $moduleResultEntry->setFieldValue('resultComponentResults', $componentResults);
        Craft::$app->getElements()->saveElement($moduleResultEntry);
        ## update result status to complete or revert to active (if unit result was deleted)
        if ($this->isCompleteModuleResult($moduleResultEntry)) {
            $this->completeModuleResult($moduleResultEntry, $userId, $moduleResultExpiryTime);
        }
        else {
            $this->activateModuleResult($moduleResultEntry, $userId);
        }
        return;
    }

    /**
     * @param Entry $unitEntry
     * @param Entry|null $moduleEntry
     * @return float|int|null
     */
    public function getUnitPoints(Entry $unitEntry, Entry $moduleEntry = null)
    {
        if ($moduleEntry) {
            foreach ($moduleEntry->moduleUnitGroups as $unitGroup) {
                $unitIds = [];
                foreach($unitGroup->unitEntries as $unitEntry) {
                    $unitIds[] = $unitEntry->id;
                }
                if ($unitGroup->unitPointsOverride && in_array($unitEntry->id, $unitIds)) {
                   return $unitGroup->unitPointsOverride;
                }
            }
        }
        return $unitEntry->unitPoints;
    }

    /**
     * @param Entry $moduleEntry
     * @param $unitResultEntries
     * @param array $userResultEntries
     * @return array
     */
    public function getComponentResults(Entry $moduleEntry, $unitResultEntries, $userResultEntries = [])
    {
        $rows = [];
        // add unit group targets
        foreach($moduleEntry->moduleUnitGroups->all() as $unitGroup) {
            $targetHours = (int) $unitGroup->cpdTargetHours;
            $targetPoints = (int) $unitGroup->cpdTargetPoints;
            $endorsedHours = $this->getUnitGroupEndorsed($moduleEntry, $unitResultEntries, $unitGroup, 'hours');
            $endorsedPoints = $this->getUnitGroupEndorsed($moduleEntry, $unitResultEntries, $unitGroup, 'points');
            $complete = ($endorsedHours >= $targetHours && $endorsedPoints >= $targetPoints) ? 1 : 0;

            $row = [
                'col1' => $unitGroup->id,
                'col2' => $unitGroup->groupName,
                'col3' => $targetHours,
                'col4' => $targetPoints,
                'col5' => $endorsedHours,
                'col6' => $endorsedPoints,
                'col7' => $complete,
            ];
            $rows [] = $row;
        }
        // add the achievements targets
        if ($moduleEntry->achievementTargetHours) {
            $endorsedHours = $this->getUserEndorsed($userResultEntries);
            $rows [] = [
                'col1' => '',
                'col2' => $moduleEntry->achievementLabel ? $moduleEntry->achievementLabel : 'Achievements',
                'col3' => $moduleEntry->achievementTargetHours,
                'col4' => 0,
                'col5' => $endorsedHours,
                'col6' => 0,
                'col7' => $endorsedHours >= $moduleEntry->achievementTargetHours ? 1 : 0,
            ];
        }
        return $rows;
    }

    /**
     * @param $unitResultEntries
     * @return int
     */
    private function getUserEndorsed($unitResultEntries)
    {
        $return = 0;
        foreach ($unitResultEntries as $userResultEntry) {
            if ($userResultEntry->resultStatus != 'endorsed') {
                continue;
            }
            $return += (int) $userResultEntry->resultHours;
        }
        return $return;
    }

    /**
     * @param $moduleEntry
     * @param $unitResultEntries
     * @param $unitGroup
     * @param $type
     * @return int
     */
    private function getUnitGroupEndorsed($moduleEntry, $unitResultEntries, $unitGroup, $type)
    {
        $unitIds = $unitGroup->unitEntries->ids();
        $return = 0;
        foreach ($unitIds as $id) {
            if (isset($unitResultEntries[$id])) {
                $unitResultEntry = $unitResultEntries[$id];
                if ($unitResultEntry->resultStatus != 'endorsed') {
                    continue;
                }
                if ($type == 'hours') {
                    $return += (int) $unitResultEntry->resultHours;
                }
                else {
                    $unitEntry = $unitResultEntry->resultUnit->one();
                    $return += $this->getUnitPoints($unitEntry, $moduleEntry);
                }
            }
        }
        return $return;
    }

    /**
     * @param $moduleResult
     * @return bool
     */
    function isCompleteComponentResults($moduleResult)
    {
        foreach($moduleResult->resultComponentResults as $componentResult) {
            if (!isset($componentResult['complete']) || $componentResult['complete']) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param $moduleResult
     * @return bool
     */
    function isCompleteModuleResult($moduleResult)
    {
        if (!$moduleResult->resultModule) {
            return false;
        }
        $moduleEntry = $moduleResult->resultModule->one();
        if ($moduleEntry->type == 'cpd') {
            ## check component results
            if (!$this->isCompleteComponentResults($moduleResult)) {
                return false;
            }
            $targetType = (string) $moduleEntry->targetType->value;
            if ($targetType == 'hours') {
                 return $moduleResult->resultHours >= $moduleEntry->targetHours;
            }
            elseif ($targetType == 'points') {
                return $moduleResult->resultPoints >= $moduleEntry->targetPoints;
            }
            else {
                $remainingPoints = max($moduleEntry->targetHours - $moduleResult->resultHours, 0);
                $remainingHours = max($moduleEntry->targetPoints - $moduleResult->resultPoints, 0);
                if ($targetType == 'pointsAndHours') {
                    return !$remainingPoints && !$remainingHours;
                } elseif ($targetType == 'pointsOrHours') {
                    return !$remainingPoints || !$remainingHours;
                }
            }
        }
        elseif ($moduleEntry->type == 'qualification') {
            $totalUnits = count($this->getModuleUnitIds($moduleEntry));
            $totalResults = $this->getModuleUnitResults($moduleEntry, $moduleResult->getAuthor()->id, true);
            return $totalResults >= $totalUnits;
        }
        return false;
    }

    /**
     * @param $moduleResult
     * @return int
     */
    public function pending($moduleResult, $userId)
    {
        $return = [
            'text'    => '',
            'total'   => 0
        ];
        $pendingResults = $this->getModuleResultResults($moduleResult->id, null, false, 'both', 'pending');

        $pendingHours = 0;
        $pendingPoints = 0;
        foreach($pendingResults as $result) {
            $pendingHours = $pendingHours + (int) $result->resultHours;
            if (null != $resultUnit = $result->resultUnit->one()) {
                $pendingPoints = $pendingPoints + (int)$result->resultUnit->one()->unitPoints;
            }
            $return['total']++;
        }
        if ($pendingHours) {
            $return['text'] = $pendingHours . ' hour' . ($pendingHours > 1 ? 's' : '');
        }
        if ($pendingHours && $pendingPoints) {
            $return['text'] .= ' and ';
        }
        if ($pendingPoints) {
            $return['text'] = $pendingPoints . ' point' . ($pendingPoints > 1 ? 's' : '');
        }
        return $return;
    }

    /**
     * @param $moduleResult
     * @return array
     */
    public function remaining($moduleResult)
    {
        $return = [
            'text'          => '',
            'pointsScore'   => '',
            'hoursScore'     => ''
        ];
        if (!$moduleResult || !$moduleResult->resultModule) {
            return $return;
        }
        $moduleEntry = $moduleResult->resultModule->one();
        if (!$moduleEntry) {
            return $return;
        }
        $targetPoints = (int) $moduleEntry->targetPoints;
        $targetHours = (int) $moduleEntry->targetHours;

        $resultPoints = (int) $moduleResult->resultPoints;
        $resultHours = (int) $moduleResult->resultHours;

        $remainingPoints = $targetPoints - $resultPoints;
        $remainingHours = $targetHours - $resultHours;
        $remainingPointsText = $remainingPoints . ' point' . ($remainingPoints > 1 ? 's' : '');
        $remainingHoursText = $remainingHours . ' hour' . ($remainingHours > 1 ? 's' : '');

        if ($moduleEntry->targetType == 'hours') {
            $return['hoursScore'] = $resultHours . '/' . $targetHours;
            $return['text'] = $remainingHoursText;
        } elseif ($moduleEntry->targetType == 'points') {
            $return['pointsScore'] = $resultPoints . '/' . $targetPoints;
            $return['text'] = $remainingPointsText;
        } else {
            $return['hoursScore'] = $resultHours . '/' . $targetHours;
            $return['pointsScore'] = $resultPoints . '/' . $targetPoints;
            if ($moduleEntry->targetType == 'pointsAndHours') {
                if ($remainingPoints && $remainingHours) {
                    $return['text'] =  $remainingPointsText . ' and ' . $remainingHoursText;
                }
                else {
                    $return['text'] = $remainingPoints ? $remainingPointsText : $remainingHoursText;
                }
            } elseif ($moduleEntry->targetType == 'pointsOrHours') {
                $return['text'] = $remainingPointsText . ' or ' . $remainingHoursText;
            }
        }
        return $return;
    }

    /**
     * @param $userId
     * @param $unitId
     * @param $resultModuleResult
     * @param $cycle
     * @param $postDate
     * @return EntryModel|void
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     */
    function createUnitResult($userId, $unitId, $resultModuleResult = null, $cycle = null, $postDate = null) {
        $resultEntry = new Entry();
        $resultEntry->sectionId = $this->sectionIdResults;
        $resultEntry->typeId = $this->typeIdUnitResult;
        $resultEntry->enabled = true;
        $resultEntry->authorId = $userId;
        if ($cycle) {
            $resultEntry->setFieldValue('resultRecurringCycleCode', $cycle->code);
        }
        if ($postDate) {
            $resultEntry->postDate = $postDate;
        }
        if ($resultModuleResult) {
            $resultEntry->setFieldValue('resultModuleResult', [$resultModuleResult]);
        }
        $resultEntry->setFieldValue('resultUnit', [$unitId]);
        $resultEntry->setFieldValue('resultStatus',  'incomplete');
        if (!Craft::$app->elements->saveElement($resultEntry)) {
            return;
        }
        return $resultEntry;
    }

    /**
     * @param $userId
     * @param $moduleEntryId
     * @return EntryModel|void
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     */
    function createModuleResult($userId, $moduleEntryId, $postDate = null) {
        $resultEntry = new Entry();
        $resultEntry->sectionId = $this->sectionIdResults;
        $resultEntry->typeId = $this->typeIdModuleResult;
        $resultEntry->enabled = true;
        $resultEntry->authorId = $userId;
        if ($postDate) {
            $resultEntry->postDate = $postDate;
        }
        $resultEntry->setFieldValue('resultModule', [$moduleEntryId]);
        $resultEntry->setFieldValue('resultStatus',  'active');
        if (!Craft::$app->elements->saveElement($resultEntry)) {
            return;
        }
        return $resultEntry;
    }

    /**
     * Complete a module result
     *
     * @param $moduleResultEntry
     * @param $userId
     * @param $moduleResultExpiryTime
     * @return null
     * @throws /Exception
     */
    function completeModuleResult($moduleResultEntry, $userId, $moduleResultExpiryTime = null)
    {
        ## either no expiry, default module expiry or set by result
        if ($moduleResultExpiryTime) {
            $moduleResultEntry->expiryDate = DateTime::createFromFormat('U', $moduleResultExpiryTime);
        }
        $moduleResultEntry->setFieldValue('resultStatus', 'complete');
        Craft::$app->elements->saveElement($moduleResultEntry);
    }

    /**
     * Activate a module result
     *
     * @param $moduleResultEntry
     * @return null
     * @throws /Exception
     */
    function activateModuleResult($moduleResultEntry)
    {
        $moduleResultEntry->setFieldValue('resultStatus', 'active');
        Craft::$app->elements->saveElement($moduleResultEntry);
    }

    /**
     * @param array $jobRoleIds
     * @throws Exception
     * @return mixed
     */
    public function jobRoleModules($jobRoleIds = [])
    {
        $criteria = Entry::find();
        $criteria->section = 'modules';
        $criteria->limit = null;
        $criteria->relatedTo = ['targetElement' => $jobRoleIds, 'field' => 'moduleRoles'];
        return $criteria->all();
    }

    /**
     * @param $jobRoleId
     * @param $userId
     * @return array
     * @throws Exception
     */
    public function getJobRoleUserResults($jobRoleId, $userId = null)
    {
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
     * @param null $userId
     * @return \craft\elements\db\ElementQueryInterface|\craft\elements\db\EntryQuery
     */
    public function getAllResults($userId = null)
    {
        $criteria = Entry::find();
        $criteria->section = 'results';
        $criteria->authorId = $userId;
        $criteria->status = null;
        $criteria->limit = null;
        return $criteria;
    }

    /**
     * @param null $userId
     * @param array $unitIds
     * @return \craft\elements\db\ElementQueryInterface|\craft\elements\db\EntryQuery
     */
    public function getAllUnitResults($userId = null, $unitIds = [])
    {
        $criteria = Entry::find();
        $criteria->section = 'results';
        $criteria->type = 'unitResult';
        $criteria->authorId = $userId;
        $criteria->relatedTo = ['targetElement' => $unitIds, 'field' => 'resultUnit'];
        $criteria->status = null;
        $criteria->limit = null;
        return $criteria;
    }

    /**
     * @param $packageId
     * @param null $userId
     * @param string $results
     * @return array|string
     */
    public function getPackageUserResults($packageId, $userId = null, $type = 'both')
    {
        if (null == $user = LantraHelper::getUser($userId)) {
            return [];
        }
        $results = [];
        // get all modules for job role
        $package = Lantra::$app->packages->getUserPackage($packageId, $user);
        if ($package) {
            $modules = Lantra::$app->packages->getPackageModuleEntries($package);
            foreach ($modules as $moduleEntry) {
                if ($type == 'unit' || $type == 'both') {
                    // get unit results relating to module
                    $unitResults = $this->getModuleUnitResults($moduleEntry, $user->id);
                    $results = array_merge($results, $unitResults);
                }
                if ($type == 'user' || $type == 'both') {
                    // get user results relating to module
                    $userResults = $this->getModuleUserResults($moduleEntry, $user->id, false);
                    $results = array_merge($results, $userResults);
                }
            }
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
        if ($moduleEntry->moduleUnitGroups) {
            foreach ($moduleEntry->moduleUnitGroups->all() as $unitGroup) {
                foreach ($unitGroup->unitEntries->all() as $unitEntry) {
                    $unitIds[] = $unitEntry->id;
                }
            }
        }
        return $unitIds;
    }

    /**
     *  Get module user results with positive result value
     *
     * @param $moduleEntry
     * @param $userId
     * @param $resultPoints
     * @return array
     * @throws Exception
     */
    function getModuleUserResults($moduleEntry, $userId, $resultPoints = true) {
        $criteria = Entry::find();
        $criteria->section = 'results';
        $criteria->type = 'userResult';
        $criteria->authorId = $userId;
        $criteria->status = null;
        $criteria->limit = null;
        $criteria->relatedTo = ['targetElement' => $moduleEntry->id, 'field' => 'resultModule'];
        if ($resultPoints) {
            $criteria->resultPoints = '> 0';
        }
        return $criteria->all();
    }

    /**
     * @param $moduleEntry
     * @param $userId
     * @param bool $count
     * @param null $moduleResultId
     * @return array|int|string
     */
    function getModuleUnitResults($moduleEntry, $userId, $count = false, $moduleResultId = null, $status = null) {
        $unitIds = $this->getModuleUnitIds($moduleEntry);
        $criteria = Entry::find();
        $criteria->section = 'results';
        $criteria->type = 'unitResult';
        $criteria->authorId = $userId;
        $criteria->status = null;
        $criteria->limit = null;
        if ($status) {
            $criteria->resultStatus = $status;
        }
        if ($moduleResultId) {
            $criteria->relatedTo = [
                'and',
                ['targetElement' => $unitIds, 'field' => 'resultUnit'],
                ['targetElement' => $moduleResultId, 'field' => 'resultModuleResult']
            ];
        }
        else {
            $criteria->relatedTo = ['targetElement' => $unitIds, 'field' => 'resultUnit'];
        }
        if ($count) {
            return $criteria->count();
        }
        $resultEntries = $criteria->all();
        $return = [];
        foreach ($resultEntries as $resultEntry) {
            $unitId = $resultEntry->resultUnit->one()->id;
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
        $criteria = Entry::find();
        $criteria->section = 'results';
        $criteria->type = ['userResult', 'unitResult'];
        $criteria->resultStatus = 'pending';
        $criteria->order = 'postDate desc';
        $criteria->limit = $limit;
        // limit by subordinates and check unit level if team or company manager
        if (!$manager->isInGroup('schemeManagers') && !$manager->admin) {
            $subordinateIds = Lantra::$app->users->getManagerSubordinateIds($manager, true);
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
        $criteria = User::find();
        $criteria->id =  $this->getManagerEndorsementUserIds($manager, $directSubordinates);
        $criteria->order = 'lastName desc';
        $criteria->limit = $limit;
        return ($count) ? $criteria->count() : $criteria;
    }

    /**
     * @param $manager
     * @param bool $directSubordinates
     * @return array|int
     * @throws \yii\db\Exception
     */
    public function countManagerEndorsementUsers(User $manager, $directSubordinates = false) {
        return $this->getManagerEndorsementUserIds($manager, $directSubordinates, true);
    }

    /**
     * @param $manager
     * @param bool $directSubordinates
     * @param bool $count
     * @return array|int
     * @throws \yii\db\Exception
     */
    public function getManagerEndorsementUserIds(User $manager, $directSubordinates = false, $count = false) {
        $onlySubordinates = false;
        # check for manager subordinates (SM and admin show all)
        if (!$manager->isInGroup('schemeManagers') && !$manager->admin) {
            $subordinateIds = Lantra::$app->users->getManagerSubordinateIds($manager, $directSubordinates == false);
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
            FROM {{%entries}} e
            LEFT JOIN {{%content}} c ON c.elementId = e.id
            LEFT JOIN {{%elements}} el ON el.id = e.id
            LEFT JOIN {{%users}} u ON u.id = authorId
            WHERE e.sectionId = 10
            AND u.suspended = 0
            AND u.pending = 0 
            AND c.field_resultStatus = 'pending'
            AND el.enabled = 1
            AND el.revisionId IS NULL
            AND el.draftId IS NULL
            AND el.dateDeleted IS NULL
            AND (e.typeId = 10 OR e.typeId = 17)
            AND authorId != " . $manager->id;

        # add subordinates and level to query
        if ($onlySubordinates) {
            $mysql .= " 
            AND c.field_unitEndorsementManagerLevel <= " . $level . "
            AND authorId IN(" . implode(',', $subordinateIds) . ")";
        }

        if ($count) {
            return Craft::$app->db->createCommand($mysql)->queryScalar();
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
            $manager = Craft::$app->users->getUserById($userId);
        }
        else {
            $manager = Craft::$app->getUser();
        }
        if ( ! $manager) {
            return null;
        }
        // limit by subordinates if team or company manager
        if ( ! $manager->isInGroup('schemeManager') && ! $manager->admin()) {
            $subordinateIds = Lantra::$app->users->getManagerSubordinateIds($manager, true);
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
        $criteria = Entry::find();
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
     * @param $moduleId
     * @param null $authorId
     * @return array|\craft\base\ElementInterface[]|Entry[]
     */
    public function getUserModuleResults($moduleId, $authorId = null)
    {
        $criteria = Entry::find();
        $criteria->section = 'results';
        $criteria->type = 'moduleResult';
        $criteria->relatedTo = ['targetElement' => $moduleId, 'field' => 'resultModule'];
        if ($authorId) {
            $criteria->authorId = $authorId;
        }
        $criteria->limit = null;
        return $criteria->all();
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
     * @throws \yii\db\Exception
     */
    public function getManagerUserSummary($userId = null, $userFilter = [], $resultFilter = []) {
        $userFilter = $this->formatUserFilter($userFilter);
        $subordinates = Lantra::$app->users->getManagerUsers($userId, $userFilter['limit'], $userFilter['search'], $userFilter['relatedTo']);
        if (! $subordinates) {
            return [];
        }
        $subordinateIds = $subordinates->ids();
        // streamlined sql to get users
        $users = Lantra::$app->users->getReportUsers($subordinateIds);

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
     * @param Entry $resultEntry
     * @return Entry|null
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     */
    public function legacyResultFiles(Entry $resultEntry)
    {
        if (!$resultEntry->legacyResultFiles) {
            return $resultEntry;
        }

        $folder = LantraHelper::userEvidenceFolder($resultEntry->author);

        $legacyFiles = explode(',', $resultEntry->legacyResultFiles);
        $assetIds = [];
        $updatedLegacyResultFiles = $legacyFiles;

        $unchanged = true;

        foreach($legacyFiles as $key => $filePath){
            $legacyPath = Craft::getAlias('@assetsPath') . '/archive' . trim($filePath);
            $parts = explode('/', $legacyPath);
            $filename = end($parts);

            ## check existing asset?
            if (false != $existingAsset = LantraHelper::findAsset('evidence', $folder->id, $filename)) {
                $assetIds[] = $existingAsset->id;
                unset($updatedLegacyResultFiles[$key]);
                $unchanged = false;
                Craft::info("Legacy file is existing asset: [". $filename . "] ", __METHOD__);
                continue;
            }

            ## look for path with spaces
            $legacyPathSpaces = str_replace('%20', ' ', $legacyPath);
            $localPath = false;
            if (is_file($legacyPathSpaces)) {
                $localPath = $legacyPathSpaces;
            } elseif (is_file($legacyPath)) {
                $localPath = $legacyPath;
            }

            if ($localPath && $folder) {
                $response = LantraHelper::addAsset($localPath, $filename, 'evidence', $folder->name);
                if ($response['asset']) {
                    $assetIds[] = $response['asset']->id;
                    ## leave archive file in place, just in case.
                    ## unlink($localPath);
                    unset($updatedLegacyResultFiles[$key]);
                    $unchanged = false;
                }
            }
            else {
                Craft::warning("Legacy files not found: [". $localPath . "] ", __METHOD__);
            }
        }

        if ($unchanged || ! count($assetIds)) {
            return $resultEntry;
        }

        $resultEntry->setFieldValue('resultEvidence', $assetIds);
        $resultEntry->setFieldValue('legacyResultFiles', implode(',', $updatedLegacyResultFiles));
        Craft::$app->elements->saveElement($resultEntry);

        ## get entry again to force refresh on data
        $resultEntry = Craft::$app->entries->getEntryById($resultEntry->id);
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
        $subordinates = Lantra::$app->users->getManagerUsers($userId, $userFilter['limit'], $userFilter['search'], $userFilter['relatedTo']);
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
                        $title = $result->resultUnit->one()->title;
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
            $company = Lantra::$app->users->userCompany($user);
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
                    if ($this->isDateField($displayField)) {
                        $value = $fieldValue ? $fieldValue->format($this->dateFormat) : '-';
                    }
                    else {
                        $value = $fieldValue ? $fieldValue : '-';
                    }
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
     * @param $displayField
     * @return bool
     */
    private function isDateField($displayField)
    {
        return in_array($displayField, ['expiryDate', 'resultStartDate', 'resultFinishDate']);
    }

    /**
     * @param $subordinateIds
     * @param array $resultFilter
     * @return array
     * @throws Exception
     */
    private function getSubordinateResults($subordinateIds, $resultFilter = []) {
        $criteria = Entry::find();
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
        $results = $criteria->all();

        // arrange as useful array [userId][id] = [result]
        $data = [];
        foreach ($results as $result) {
            if ( ! isset ($data[$result->authorId])){
                $data[$result->authorId] = [];
            }
            $id = $result->type == 'unitResult' && $result->resultUnit->count() ? $result->resultUnit->one()->id : $result->id;
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
        $subordinates = Lantra::$app->users->getManagerUsers($userId, $userFilter['limit'], $userFilter['search'], $userFilter['relatedTo']);
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
                $company = Lantra::$app->users->userCompany($user);
                $role = $user->userRole->one();
                $userUnits = $this->roleUnits($role->id);
                $title = $result->title;
                if ($result->type == 'unitResult') {
                    $resultUnit = $result->resultUnit->one();
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
                    $result->expiryDate->format($this->dateFormat),
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
     * Return manager CPD results
     *
     * @param null $userId
     * @param array $userFilter
     * @param array $resultFilter
     * @return array
     * @throws Exception
     */
    public function getManagerCpdResults($userId = null, $userFilter = [], $resultFilter) {
        $userFilter = $this->formatUserFilter($userFilter);
        $subordinates = Lantra::$app->users->getManagerUsers($userId, $userFilter['limit'], $userFilter['search'], $userFilter['relatedTo']);
        $subordinateIds = $subordinates->ids();

        $header = [
            'User ID',
            'User Name',
            'Company ID',
            'Company Label',
            'Unit',
            'Start Date',
            'Finish Date',
            'Hours',
            'Points',
            'Evidence',
            'Comments'
        ];

        $resultFilter['resultType'] = 'unitResult';
        $resultFilter = $this->formatResultsFilter($resultFilter);
        $allResults = $this->getSubordinateResults($subordinateIds, $resultFilter);

        $rows = [$header];
        foreach($subordinates as $user) {
            if (isset($allResults[$user->id]) && count($allResults[$user->id])) {
                $company = Lantra::$app->users->userCompany($user);
                foreach($allResults[$user->id] as $result) {
                    $unit = $result->resultUnit->one();
                    $files = [];
                    $comments = [];
                    foreach ($result->resultEvidence as $file) {
                        $files[] = $file->filename;
                    }
                    foreach ($result->resultComments as $comment) {
                        $comments[] = $comment->comment . ' (' . ($comment->user ? $comment->user->one()->fullName : 'unknown') . ')';
                    }
                    $row = [
                        $user->id,
                        $user->fullName,
                        $company ? $company->id : '~',
                        $company ? $company->companyLabel : 'unknown',
                        $unit->title,
                        $result->resultStartDate ? $result->resultStartDate->format($this->dateFormat) : '~',
                        $result->resultFinishDate ? $result->resultFinishDate->format($this->dateFormat) : '~',
                        $result->resultHours,
                        $result->resultValue,
                        implode(', ', $files),
                        implode(', ', $comments)
                    ];
                    $rows[] = $row;
                }
            }
        }
        return $rows;
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
        $subordinates = Lantra::$app->users->getManagerUsers($userId, $userFilter['limit'], $userFilter['search'], $userFilter['relatedTo']);

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
        $criteria = Entry::find();
        $criteria->type = 'userResult';
        $criteria->section = 'results';
        $criteria->status = 'expired';
        $criteria->authorId = $user->id;
        $results = $criteria->all();
        foreach ($results as $result) {
            $company = Lantra::$app->users->userCompany($user);
            $role = $user->userRole->one();
            $row = [
                $user->id,
                $user->fullName,
                $company ? $company->id : '~',
                $company ? $company->companyLabel : 'unknown',
                $role ? $role->title : 'unknown',
                $result->title,
                $result->expiryDate->format($this->dateFormat),
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
                $company = Lantra::$app->users->userCompany($user);
                $role = $user->userRole->one();
                $row = [
                    $user->id,
                    $user->fullName,
                    $company ? $company->id : '~',
                    $company ? $company->companyLabel : 'unknown',
                    $role ? $role->title : 'unknown',
                    $unit->title,
                    $result ? $result->expiryDate->format($this->dateFormat) : null,
                    $result ? 'expired' : 'required'
                ];
                $rows[] = $row;
            }
        }
        return $rows;
    }

    ## cache of roles
    private $roles;

    private function getRole($roleId = null) {
        if (is_null($this->roles)) {
            $criteria = Category::find();
            $criteria->group = 'roles';
            $criteria->limit = null;
            foreach($criteria->all() as $role) {
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
            $criteria = Category::find();
            $criteria->group = 'roles';
            $criteria->limit = null;
            foreach ($criteria->all() as $role) {
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
        foreach($user->userRole->all() as $role) {
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
        $criteria = Entry::find();
        $criteria->relatedTo = ['targetElement' => $unitId, 'field' => 'resultUnit'];
        $criteria->status = ['live', 'expired'];
        $criteria->authorId = $user->id;
        return $criteria->one();
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
            $criteria = Entry::find();
            $criteria->relatedTo = ['targetElement' => $role->id, 'field' => 'moduleRoles'];
            $criteria->limit = null;
            $this->roleModules[$role->id] = $criteria;
        }
        return $criteria->all();
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
        $criteria = Entry::find();
        $criteria->section = 'units';
        $criteria->limit = null;
        $units = [];
        foreach($criteria->all() as $unit) {
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
            $manager = Craft::$app->users->getUserById($userId);
        }
        else {
            $manager = Craft::$app->getUser();
        }
        if ( ! $manager) {
            return null;
        }
        $criteria = Entry::find();
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
        if ( ! $manager->isInGroup('schemeManager') && ! $manager->admin) {
            $subordinateIds = Lantra::$app->users->getManagerSubordinateIds($manager, true);
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
        $criteria = Entry::find();
        $criteria->section = 'modules';
        $criteria->limit = null;
        $criteria->relatedTo = ['targetElement' => $roleId, 'field' => 'moduleRoles'];
        return $criteria->count() ? $criteria->all() : [];
    }

    /**
     * @param $id
     * @throws \yii\base\NotSupportedException
     */
    public function addUnitColumn($id) {
        if (LantraHelper::setting('disableResultCache')) {
            return;
        }
        if (!Craft::$app->db->columnExists('{{%lantra_result_cache}}', 'unit' . $id)) {
            Craft::$app->db->createCommand()->addColumn('{{%lantra_result_cache}}', 'unit' . $id, 'text FIRST')->execute();
        }
    }

    /**
     * @param $id
     * @throws \yii\base\NotSupportedException
     */
    public function removeUnitColumn($id) {
        if (LantraHelper::setting('disableResultCache')) {
            return;
        }
        if (Craft::$app->db->columnExists('{{%lantra_result_cache}}', 'unit' . $id)) {
            Craft::$app->db->createCommand()->dropColumn('{{%lantra_result_cache}}', 'unit' . $id)->execute();
        }
    }

    /**
     * @param array $users
     * @throws \yii\db\Exception
     */
    public function refreshResultCache($users = [])
    {
        foreach ($users as $user) {
            $results = $this->getUserUnitResults($user->id);
            if ($results->count()) {
                $this->saveUserResultCache($user->id, $results->all());
            }
        }
    }

    /**
     * @param $userId
     * @return \craft\elements\db\ElementQueryInterface|\craft\elements\db\EntryQuery
     */
    public function getUserUnitResults($userId) {
        $criteria = Entry::find();
        $criteria->section = 'results';
        $criteria->type = 'unitResult';
        $criteria->authorId = $userId;
        return $criteria;
    }

    /**
     * @param $userId
     * @param null $resultEntries
     * @throws \yii\base\NotSupportedException
     * @throws \yii\db\Exception
     */
    public function saveUserResultCache($userId, $resultEntries = null) {
        if (LantraHelper::setting('disableResultCache')) {
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
                $unit = $resultEntry->resultUnit->one();
                if ($unit) {
                    ## make sure unit column exists
                    $this->addUnitColumn($unit->id);
                    $updateColumns['unit' . $unit->id] = $this->setResultValue($resultEntry);
                }
            }
        }
        Craft::$app->db->createCommand()->upsert('{{%lantra_result_cache}}', $keyColumns, $updateColumns)->execute();
    }

    /**
     *
     */
    public function syncUserResultCache()
    {
        $dateUpdated = $this->getResultCacheDateUpdated();
        $users = $this->getResultUsersSince($dateUpdated);
        if (!count($users)) {
            return 0;
        }
        $this->refreshResultCache($users);
        return count($users);
    }

    /**
     * Gets all users with results later than a date
     *
     * @param $dateUpdated
     * @return array
     */
    public function getResultUsersSince($dateUpdated)
    {
        $criteria = Entry::find();
        $criteria->section = 'results';
        if ($dateUpdated) {
            $criteria->dateUpdated('> ' . $dateUpdated);
        }
        $results = $criteria->all();
        $users = [];
        foreach($results as $result){
            if (! isset($users[$result->authorId])){
                $users[] = $result->author;
            }
        }
        return $users;
    }

    /**
     * @return DateTime|null
     */
    public function getResultLastDate()
    {
        $criteria = Entry::find();
        $criteria->section = 'results';
        $criteria->orderBy('dateUpdated desc');
        $criteria->limit(1);
        $result = $criteria->one();
        return $result ? $result->dateUpdated : null;
    }

    /**
     * @return bool|false|null|string
     */
    public function getResultCacheDateUpdated()
    {
        $dateUpdated = (new Query())
            ->select('dateUpdated')
            ->from('{{%lantra_result_cache}}')
            ->orderBy('dateUpdated desc')
            ->limit(1)
            ->scalar();

        return $dateUpdated;
    }

    /**
     * @param $resultEntry
     * @throws \yii\db\Exception
     */
    public function deleteUserResultCache($resultEntry) {
        if (Lantra::$app->settings->getSetting('disableResultCache')) {
            return;
        }
        $userId = $resultEntry->getAuthor()->id;
        $unitId = $resultEntry->resultUnit->one()->id;
        if ($userId && $unitId) {
            Craft::$app->db->createCommand()->update('{{%lantra_result_cache}}', ['unit' . $unitId => ""], ['userId' => $userId])->execute();
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
        $single = !is_array($userIds);
        if ($single) {
            $where = ['userId' => $userIds];
        }
        else {
            $where = ['IN', 'userId', $userIds];
        }
        $result = (new Query())
            ->from('{{%lantra_result_cache}}')
            ->where($where)
            ->all();

        if (!$result) {
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

        if (is_object($resultEntry->resultStartDate)) {
            $startDate = $resultEntry->resultStartDate->getTimestamp();
        }
        else {
            $startDate = $resultEntry->resultStartDate ? DateTime::createFromFormat(DATE_ATOM, $resultEntry->resultStartDate)->getTimestamp() : null;
        }
        if (is_object($resultEntry->resultFinishDate)) {
            $finishDate = $resultEntry->resultFinishDate->getTimestamp();
        }
        else {
            $finishDate = $resultEntry->resultFinishDate ? DateTime::createFromFormat(DATE_ATOM, $resultEntry->resultFinishDate)->getTimestamp() : null;
        }
        return json_encode([
            'expiryDate' => $resultEntry->expiryDate ? $resultEntry->expiryDate->getTimestamp() : null,
            'startDate' => $startDate,
            'finishDate' => $finishDate
        ]);
    }
}
