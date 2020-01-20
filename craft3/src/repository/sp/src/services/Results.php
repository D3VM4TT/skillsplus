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

use lantra\sp\Plugin as Lantra;
use lantra\sp\helpers\LantraHelper;

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
        $unitEntry = $entry->resultUnit->one();
        if ($entry->type == 'unitResult' && !$unitEntry) {
            $event->isValid = false;
            $entry->addError('resultUnit', 'You must select a Result Unit for Unit Results');
        }
        if ($entry->type == 'moduleResult') {

        }
        if ($entry->type == 'userResult') {

        }
        if ($entry->type == 'unitResult' || $entry->type == 'userResult') {
            ## set result owner as user
            if (!$entry->resultOwner) {
                $entry->resultOwner = [$userId];
            }
            ## copy manager endorsement level from unit for submitted evidence
            if ($entry->resultEvidence && $entry->type == 'unitResult' && $unitEntry) {
                $entry->unitEndorsementManagerLevel = $unitEntry->unitEndorsementManagerLevel;
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
                    if (!$oldEntry || !$oldEntry->resultEndorsedDate) {
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
                // send notification
                if (Lantra::$app->results->notifyManagerEndorsementResult($entry)) {
                    Lantra::$app->notify->sendManagerEndorsementResult($entry);
                }
            }
            $dateFormat = 'Y-m-d H:i:s';

            ## set a result start date
            $userStartDate = Craft::$app->request->getParam('userStartDate');
            if ($userStartDate && false != $date = $dateTime->createFromFormat($dateFormat, $userStartDate)) {
                $userStartDate = $date->format(DATE_ATOM);
            }
            $entry->resultStartDate = $userStartDate;
            ## set a result finish date
            $userFinishDate = Craft::$app->request->getParam('userFinishDate');
            if ($userFinishDate && false != $date = $dateTime->createFromFormat($dateFormat, $userFinishDate)) {
                $userFinishDate = $date->format(DATE_ATOM);
            }
            $entry->resultFinishDate = $userFinishDate;
            $userExpiryDate = Craft::$app->request->getParam('userExpiryDate');
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
        if (!$event->isValid) {
            Craft::$app->urlManager->setRouteParams(['resultEntry' => $entry]);
        }
    }

    /**
     * @param ModelEvent $event
     * @param Entry $entry
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\SyntaxError
     * @throws \yii\base\InvalidConfigException
     */
    function onSaveResult(ModelEvent $event, Entry $entry)
    {
        $userId = Craft::$app->getUser()->id;;
        if ($entry->type == 'unitResult' || $entry->type == 'userResult') {
            $unitEntry = $entry->resultUnit->one();
            ## send notification
            if ($this->notifyManagerEndorsementResult($entry)){
                Lantra::$app->notify->sendManagerEndorsementResult($entry);
            }
        }
        ## save unit result in user result cache (if enabled)
        if ($entry->enabled && $entry->type == 'unitResult') {
            $this->saveUserResultCache($entry->authorId, $entry);
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
    function readComment(SuperTableBlockElement $comment, $userId) {
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
    function unreadComments($result, $userId) {
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
    function getUnitResult($userId, $unitId) {
        $criteria = Entry::find();
        $criteria->section = 'results';
        $criteria->type = 'unitResult';
        $criteria->limit = 1;
        $criteria->authorId = $userId;
        $criteria->relatedTo = ['targetElement' => $unitId, 'field' => 'resultUnit'];
        return $criteria->one();
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
        $criteria = Entry::find();
        $criteria->section = 'results';
        $criteria->type = 'moduleResult';
        $criteria->limit = 1;
        $criteria->authorId = $userId;
        $criteria->relatedTo = ['targetElement' => $moduleId, 'field' => 'resultModule'];
        return $criteria->one();
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
        // the related module id
        $resultModuleEntry = $resultEntry->resultModule->one();
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
        $resultUnitEntry = $resultEntry->resultUnit->one();
        if ( ! $resultUnitEntry) {
            return;
        };
        // get the user job roles
        $user = $resultEntry->author;
        $jobRoles = $user->userRole;
        if ( ! $jobRoles->count()) {
            return;
        }
        // get all modules related to their job roles
        $criteria = Entry::find();
        $criteria->section = 'modules';
        $criteria->limit = null;
        $criteria->relatedTo = ['targetElement' => $jobRoles, 'field' => 'moduleRoles'];
        $moduleEntries = $criteria->all();
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
     * @param $moduleEntry
     * @param $userId
     * @throws \Exception
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
                    $unitEntry = $resultEntry->resultUnit->one();
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
     * @param $userId
     * @param $moduleEntryId
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     */
    function createModuleResult($userId, $moduleEntryId) {
        $resultEntry = new EntryModel();
        $resultEntry->sectionId = $this->sectionIdResults;
        $resultEntry->typeId = $this->typeIdModuleResult;
        $resultEntry->enabled = true;
        $resultEntry->authorId = $userId;
        $resultEntry->setFieldValue('resultModule', [$moduleEntryId]);
        $resultEntry->setFieldValue('resultStatus',  'active');
        // @todo error reporting?
        if ( ! Craft::$app->elements->saveElement($resultEntry)) {
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
        ## @todo error reporting
        if ( ! $resultEntry) {
            return;
        }
        ## either no expiry, default module expiry or set by result
        $resultEntry->expiryDate = $expiryDate;
        $resultEntry->resultStatus = complete;
        ## @todo error reporting?
        Craft::$app->elements->saveElement($resultEntry);
    }

    /**
     * @param array $jobRoleIds
     * @throws Exception
     * @return mixed
     */
    public function jobRoleModules($jobRoleIds = []) {
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
    public function getJobRoleUserResults($jobRoleId, $userId = null) {
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
     * @param $resultValue
     * @return array
     * @throws Exception
     */
    function getModuleUserResults($moduleEntry, $userId, $resultValue = true) {
        $criteria = Entry::find();
        $criteria->section = 'results';
        $criteria->type = 'userResult';
        $criteria->authorId = $userId;
        $criteria->limit = null;
        $criteria->relatedTo = ['targetElement' => $moduleEntry->id, 'field' => 'resultModule'];
        if ($resultValue) {
            $criteria->resultValue = '> 0';
        }
        return $criteria->all();
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
        $criteria = Entry::find();
        $criteria->section = 'results';
        $criteria->type = 'unitResult';
        $criteria->authorId = $userId;
        $criteria->limit = null;
        $criteria->relatedTo = ['targetElement' => $unitIds, 'field' => 'resultUnit'];
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
            LEFT JOIN {{%elements}}el ON el.id = e.id
            WHERE e.sectionId = 10 
            AND c.field_resultStatus = 'pending'
            AND el.enabled = 1
            AND el.revisionId IS NULL
            AND el.draftId IS NULL
            AND e.typeId = 10
            AND e.typeId = 10";

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
                if ($response['success']) {
                    $assetIds[] = $response['asset']->id;
                    ## leave archive file in place, just in case.
                    ## unlink($localPath);
                    unset($updatedLegacyResultFiles[$key]);
                    $unchanged = false;
                }
            }
            else {
                Craft::error("Legacy files not found: [". $localPath . "] ", __METHOD__);
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
                    $value = $fieldValue ? $fieldValue: '-';
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
        foreach($user->userRole as $role) {
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
        if ( ! $manager->isInGroup('schemeManager') && ! $manager->admin()) {
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
            Craft::$app->db->createCommand()->addColumn('lantra_result_cache', 'unit' . $id, 'text');
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
            Craft::$app->db->createCommand()->dropColumn('lantra_result_cache', 'unit' . $id);
        }
    }

    /**
     * save user result
     *
     * @param $userId
     * @param $resultEntries
     *
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
                    $updateColumns['unit' . $unit->id] = $this->setResultValue($resultEntry);
                }
            }
        }
        Craft::$app->db->createCommand()->upsert('{{%lantra_result_cache}}', $keyColumns, $updateColumns);
    }

    /**
     * @param $resultEntry
     */
    public function deleteUserResultCache($resultEntry) {
        if (Lantra::$app->settings->getSetting('disableResultCache')) {
            return;
        }
        $userId = $resultEntry->getAuthor()->id;
        $unitId = $resultEntry->resultUnit->one()->id;
        if ($userId && $unitId) {
            Craft::$app->db->createCommand()->update('{{%lantra_result_cache}}', ['unit' . $unitId => ""], ['userId' => $userId]);
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
        return json_encode([
            'expiryDate' => $resultEntry->expiryDate ? $resultEntry->expiryDate->getTimestamp() : null,
            'startDate' => $resultEntry->resultStartDate ? DateTime::createFromFormat(DATE_ATOM, $resultEntry->resultStartDate)->getTimestamp() : null,
            'finishDate' => $resultEntry->resultFinishDate ? DateTime::createFromFormat(DATE_ATOM, $resultEntry->resultFinishDate)->getTimestamp() : null
        ]);
    }
}
