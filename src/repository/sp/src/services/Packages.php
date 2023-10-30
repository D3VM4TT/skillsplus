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
use craft\events\ModelEvent;
use craft\elements\GlobalSet;
use craft\elements\Entry;
use craft\elements\User;
use craft\elements\Category;
use craft\elements\MatrixBlock;
use craft\helpers\DateTimeHelper;
use \DateTime;
use lantra\sp\helpers\LantraHelper;
use lantra\sp\helpers\RecordHelper;
use lantra\sp\Plugin as Lantra;
use verbb\supertable\elements\SuperTableBlockElement;
use verbb\supertable\services\SuperTableService;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Packages extends Component
{
    /**
     * @param ModelEvent $event
     * @param Entry $entry
     */
    public function onBeforeSavePackageWorkflow(ModelEvent $event, Entry $entry)
    {
        if ($entry->autoComplete) {
            return;
        }

        ## check steps in order assessment -> review -> complete
        $assessment = false;
        $review = false;
        $complete = false;
        $error = false;

        $i = 1;
        foreach ($entry->workflow as $step) {
            if ($i == 1 && $step->stepType->value != 'assessment') {
                $error = 'First step must be an assessment step.';
                $event->isValid = false;
            }
            if ($step->stepType->value == 'assessment') {
                if ($assessment) {
                    $error = 'Workflow can only contain one assessment step.';
                    $event->isValid = false;
                }
                $assessment = true;
            }
            if ($step->stepType->value == 'review') {
                $review = true;
                if ($complete) {
                    $error = 'Review can not follow complete step.';
                    $event->isValid = false;
                }
            }
            if ($step->stepType->value == 'complete') {
                if ($complete) {
                    $error = 'Workflow can only contain one complete step.';
                    $event->isValid = false;
                }
                $complete = true;
            }
            if ($step->stepUserGroup == 'jobRole' && !$step->stepJobRole->count()) {
                $step->addError('stepJobRole', 'Job role(s) required.');
                $event->isValid = false;
            }
            $i++;
        }
        if (!$assessment) {
            $error = 'Workflow must contain an assessment step.';
            $event->isValid = false;
        }
        if ($review && !$complete) {
            $error = 'If you add any review steps you must also add a complete step.';
            $event->isValid = false;
        }
        if ($error) {
            $entry->addError('workflow', $error);
        }
    }

    /**
     * @param ModelEvent $event
     * @param Entry $entry
     */
    public function onBeforeSavePackage(ModelEvent $event, Entry $entry)
    {
        if ($event->isNew) {
            $taskbookLabel = LantraHelper::setting('taskbookLabel');
            $taskbook = $entry->packageTaskbook ? $entry->packageTaskbook->one() : null;
            if (!$taskbook) {
                $entry->addError('packageTaskbook', 'You must select a ' . $taskbookLabel . '.');
                $event->isValid = false;
                return;
            }
            $entry->title = '[' . $taskbook->title . '] ' . $entry->author->fullname;
            if (Craft::$app->request->isSiteRequest) {
                ## loop optional and pull out selected
                $optional = Craft::$app->request->getParam('optional', []);
                $optionalModuleGroups = [];
                foreach ($optional as $id => $item) {
                    if ($item['selected'] == '1') {
                        $optionalModuleGroups[$id] = $item;
                    }
                }
                $totalOptional = count($optionalModuleGroups);
                ## check minimum optional module groups
                if ($taskbook->moduleMinimumOptional && $totalOptional < $taskbook->moduleMinimumOptional) {
                    $entry->addError('packageModules', 'You must select a minimum of ' . $taskbook->moduleMinimumOptional . ' optional modules.');
                    $event->isValid = false;
                }
                if (!$taskbook->taskbookIsFree && !$this->isCompleteCredits($taskbook, $optionalModuleGroups)) {
                    $entry->addError('packageModules', 'You have not met the requirements for this package');
                    $event->isValid = false;
                }
                $cost = $taskbook->moduleMaxCost;
                if ($taskbook->moduleCosts) {
                    foreach ($taskbook->moduleCosts as $row) {
                        if ($totalOptional == $row['optionalModules']) {
                            $cost = (int)$row['cost'];
                        }
                    }
                }
                $entry->setFieldValue('packageCost', $cost);
                ## package is free
                if (!$cost) {
                    $entry->setFieldValue('packagePaid', true);
                }
            }
            ## make sure log is clear
            $entry->setFieldValue('packageLog', []);
        }

        ## set workflow
        if (!$entry->packageWorkflow->count()) {
            $taskbookWorkflow = $taskbook->packageWorkflow->one();
            $defaultWorkflow = LantraHelper::setting('defaultWorkflow');
            if (!$taskbookWorkflow && !$defaultWorkflow) {
                $entry->addError('packageWorkflow', 'You must select a package workflow or set default workflow in settings.');
                $event->isValid = false;
            } elseif ($taskbookWorkflow) {
                $entry->setFieldValue('packageWorkflow', [$taskbookWorkflow->id]);
            }
            else {
                $entry->setFieldValue('packageWorkflow', [$defaultWorkflow->id]);
            }
        }
    }

    /**
     * @param ModelEvent $event
     * @param Entry $entry
     * @return \craft\web\Response|\yii\console\Response
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     */
    public function onSavePackage(ModelEvent $event, Entry $entry)
    {
        if ($event->isNew) {
            $this->applyPackageModuleGroups($entry);
            ## move user to users if package free
            if ($entry->packagePaid) {
                $group = Craft::$app->userGroups->getGroupByHandle('users');
                Craft::$app->users->assignUserToGroups($entry->authorId, [$group->id]);
            }
            ## add custom payment to base
            $payment = Craft::$app->request->getParam('payment');
            if (isset($payment['method']) && $payment['method'] != '') {
                $meta = [
                    'product' => $entry->title,
                    'packageId' => $entry->id
                ];
                Lantra::$app->spbase->addPayment($entry->authorId, $payment['method'], $payment['amount'], $payment['reference'], $meta, true, true);
                $entry->setFieldValue('packageCost', $payment['amount']);
                Craft::$app->elements->saveElement($entry);
            }
        }
        if (!$entry->packageReviews->count()) {
            $this->applyPackageWorkflow($entry);
        }
        if (!$entry->packageAssessment->count()) {
            $this->applyPackageAssessment($entry);
        }
        if (Craft::$app->request->isSiteRequest && $event->isNew && $entry->packageRevision) {
            $this->revisionPackageUnits($entry);
        }
    }

    /**
     * @param ModelEvent $event
     * @param Entry $entry
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     */
    public function onSavePackageWorkflow(ModelEvent $event, Entry $entry)
    {
        $s = 1;
        foreach ($entry->workflow as $step) {
            if (!$step->stepId) {
                $step->stepId = 'step-' . $s;
                $step->setFieldValue('stepId', 'step-' . $s);
                $s++;
                Craft::$app->elements->saveElement($step);
            }
        }
    }

    /**
     * @param Entry $entry
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     */
    public function applyPackageModuleGroups(Entry $entry)
    {
        ## stop if already set
        if (count($entry->packageModuleGroups)) {
            return;
        }
        $taskbook = $entry->packageTaskbook->one();
        $packageLevel = $entry->packageLevel;
        $n = 1;
        $packageModuleGroups = [];
        $sp = new SuperTableService();
        $field = Craft::$app->fields->getFieldByHandle('packageModuleGroups');
        $blockType = $sp->getBlockTypesByFieldId($field->id)[0];
        foreach ($taskbook->moduleGroupCategories('mandatory') as $mandatoryModuleGroupCategory) {
            $level = $taskbook->taskbookFixedLevels ?  $mandatoryModuleGroupCategory->level : $packageLevel;
            $packageModuleGroups['new' . $n] = [
                'type' => $blockType->id,
                'enabled' => true,
                'fields' => [
                    'moduleGroup' => [$mandatoryModuleGroupCategory->id],
                    'moduleGroupLevel' => $level,
                    'moduleGroupMandatory' => 1
                ]
            ];
            $n++;
        }
        $entry->setFieldValues(['packageModuleGroups' => $packageModuleGroups]);
        Craft::$app->elements->saveElement($entry);
        ## get the package (with behaviour)
        if (Craft::$app->request->isSiteRequest) {
            $package = Entry::findOne($entry->id);
            $this->applyOptionalModuleGroups($package);
        }
    }

    /**
     * @param $package
     * @param null $singleType
     * @return bool
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     */
    public function applyOptionalModuleGroups($package, $singleType = null)
    {
        $sp = new SuperTableService();
        $field = Craft::$app->fields->getFieldByHandle('packageModuleGroups');
        $blockType = $sp->getBlockTypesByFieldId($field->id)[0];
        $optional = Craft::$app->request->getParam('optional', []);
        $taskbook = $package->packageTaskbook->one();

        ## calculate cost if new
        $cost = $this->getModuleGroupCost($package->taskbook, $singleType);
        ## append the optional module groups
        foreach ($optional as $categoryId => $row) {
            ## if selected and not already in package
            if (!isset($row['selected']) || $row['selected'] == '0' || $package->hasModuleGroup($categoryId)) {
                continue;
            }
            $taskbookModuleGroupBlock = $taskbook->moduleGroupBlock($categoryId);
            $postedLevel = isset($row['level']) ? $row['level'] : 0;
            ## override level for bics (5)
            if (getenv('SITE') == 'bics' && $package->packageLevel == 5) {
                $postedLevel = 5;
            }
            $level = $taskbook->taskbookFixedLevels ? $taskbookModuleGroupBlock->moduleGroupLevel : $postedLevel;
            $block = new SuperTableBlockElement();
            $block->fieldId = $field->id;
            $block->typeId = $blockType->id;
            $block->ownerId = $package->id;
            $block->setFieldValues([
                'moduleGroup' => [$categoryId],
                'moduleGroupLevel' => $level,
                'moduleGroupMandatory' => 0,
                'moduleGroupPaid' => $cost == 0,
                'moduleGroupCost' => $cost
            ]);
            if (!Craft::$app->elements->saveElement($block)) {
                continue;
            }
        }
        return true;
    }

    /**
     * @param $entry
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     */
    public function applyPackageWorkflow(Entry $entry)
    {
        $packageWorkflow = $entry->packageWorkflow->one();

        ## skip if no workflow or auto complete
        if (!$packageWorkflow || !count($packageWorkflow->workflow) || $packageWorkflow->autoComplete) {
            return;
        }

        $sp = new SuperTableService();
        $field = Craft::$app->fields->getFieldByHandle('packageReviews');
        $stepBlockType = $sp->getBlockTypesByFieldId($field->id)[0];
        $packageReviews = [];
        $n = 1;
        foreach ($packageWorkflow->workflow as $step) {
            $packageReviews['new' . $n] = [
                'type' => $stepBlockType->id,
                'enabled' => true,
                'fields' => [
                    'reviewStepId' => $step->stepId,
                    'reviewStepName' => $step->stepName,
                    'reviewStepType' => $step->stepType
                ]
            ];
            $n++;
        }
        $entry->setFieldValues(['packageReviews' => $packageReviews]);
        Craft::$app->elements->saveElement($entry);
    }

    /**
     * @param $package
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     */
    public function applyPackageAssessment(Entry $entry)
    {
        ## have to reload package behavior
        $package = Craft::$app->entries->getEntryById($entry->id);
        $moduleGroupIds = $package->moduleGroupIds();
        if (!count($moduleGroupIds)) {
            return;
        }
        $sp = new SuperTableService();
        $field = Craft::$app->fields->getFieldByHandle('packageAssessment');
        $assessmentBlockType = $sp->getBlockTypesByFieldId($field->id)[0];
        $n = 1;
        foreach ($moduleGroupIds as $moduleGroupId) {
            $packageAssessment['new' . $n] = [
                'type' => $assessmentBlockType->id,
                'enabled' => true,
                'fields' => [
                    'assessmentDate' => '',
                    'assessmentModuleGroup' => [$moduleGroupId]
                ]
            ];
            $n++;
        }
        $package->setFieldValues(['packageAssessment' => $packageAssessment]);
        Craft::$app->elements->saveElement($package);
    }

    /**
     * @param User $user
     * @return array
     */
    public function getAllModuleGroups(User $user)
    {
        $packages = $this->getUserPackages($user);
        $categories = [];
        foreach ($packages as $package) {
            foreach ($package->moduleGroupCategories() as $category) {
                $categories[$category->id] = $category;
            }
        }

        return $categories;
    }

    /**
     * Get all the optional module groups available to the user
     *
     * @param $user
     * @return array
     */
    public function getOptionalModuleGroups(User $user)
    {
        $packages = $this->getUserPackages($user);
        $available = [];
        $categoryIds = array_keys($this->getAllModuleGroups($user));
        ## get available ids
        foreach ($packages as $package) {
            $packageAvailable = $package->availableModuleGroupCategories();
            foreach ($packageAvailable as $id => $c) {
                if (!in_array($id, $categoryIds)) {
                    $available[$id] = $c;
                }
            }
        }
        return $available;
    }

    /**
     * Get all the resit module groups available to the user
     *
     * @param $user
     * @return array
     */
    public function getResitModuleGroups(User $user)
    {
        $packages = $this->getUserPackages($user);
        $resit = [];
        ## get resit ids
        foreach ($packages as $package) {
            $packageResit = $package->resitModuleGroupCategories();
            foreach ($packageResit as $id => $c) {
                $resit[$id] = $c;
            }
        }
        return $resit;
    }

    /**
     * @param $subordinateId
     * @param User|null $manager
     * @return bool
     */
    public function isPackageManager($subordinateId, User $manager = null)
    {
        if (null == $user = Craft::$app->users->getUserById($subordinateId)) {
            return false;
        }
        $packages = $this->getUserPackages($user, false);
        if (!count($packages)) {
            return false;
        }
        foreach ($packages as $package) {
            if ($package->isManager($manager)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param $subordinateId
     * @param User|null $manager
     * @return bool
     */
    public function isExternalReviewer($subordinateId, User $manager = null)
    {
        if (!Lantra::$app->users->isExternal($manager)) {
            return false;
        }
        $externalUserIds = $this->getExternalUserIds($manager);
        return in_array($subordinateId, $externalUserIds);
    }
    /**
     * @param $packageId
     * @return null
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\SyntaxError
     */
    public function stepRequest($packageId)
    {
        if (null == $package = Entry::findOne($packageId)) {
            return null;
        }
        ## lock the package
        $package->setFieldValue('packageStatus', 'locked');
        $package->save();
        if (null != $nextStep = $package->nextStep) {
            if (null == $manager = $nextStep->reviewUser->one()) {
                Lantra::$app->notify->sendStepUnassigned($nextStep);
            }
            else {
                Lantra::$app->notify->sendStepRequest($nextStep);
            }
            $package->log('Step request [' . $nextStep->stepId . ']');
        }
    }

    /**
     * @param Entry $package
     * @param $data
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     */
    public function assessment(Entry $package, $data)
    {
        $assessment = false;
        foreach ($package->packageAssessment as $a) {
            if ($a->assessmentDate) {
                continue;
            }
            if (isset($data[$a->id])) {
                $data[$a->id]['assessmentDate'] = new DateTime();
                $a->setFieldValues($data[$a->id]);
                if (Craft::$app->elements->saveElement($a)) {
                    $assessment = true;
                }
            }
        }
        if ($assessment) {
            Lantra::$app->notify->sendAssessment($package);
        }
    }

    /**
     * @param SuperTableBlockElement $step
     * @param $userId
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     */
    public function stepAssign(SuperTableBlockElement $step, $userId)
    {
        ## already assigned
        if ($step->reviewUser->count() && $step->reviewUser->one()->id == $userId) {
            return;
        }
        $step->setFieldValue('reviewUser', [$userId]);

        if (Craft::$app->elements->saveElement($step)) {
            Lantra::$app->notify->sendStepAssign($step);
        }
        $package = $step->owner;
        if ($userId) {
            $package->log('Review user assigned to ' . $step->reviewStepType . ' - ' . $step->reviewStepName . ' [' . $userId . ']');
        }
    }

    /**
     * @param SuperTableBlockElement $step
     * @param $sampled
     * @param $passed
     * @param string $comment
     * @throws \Throwable
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\SyntaxError
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     */
    public function stepUpdate(SuperTableBlockElement $step, $sampled, $passed, $comment = '')
    {
        $package = $step->owner;
        $user = LantraHelper::getUser();
        $previousStep = $package->previousStep;
        $step->setFieldValue('reviewUserName', $user ? $user->fullName : 'unknown');
        $step->setFieldValue('reviewSampled', $sampled);
        $step->setFieldValue('reviewPassed', $passed);
        $step->setFieldValue('reviewComment', $comment);
        $step->setFieldValue('reviewDate', time());
        if (!Craft::$app->elements->saveElement($step)) {
            return;
        }
        ## handle pass fail
        if ($passed) {
            if ($step->reviewStepType == 'assessment') {
                $this->endorsePackageUnits($step->owner);
            }
            if ($step->reviewStepType == 'external') {
                $this->completeExternal($package);
            }
            if ($step->reviewStepType == 'complete' || ($step->reviewStepType == 'assessment' && $package->totalSteps == 1)) {
                $this->completePackage($package);
            }
            else {
                $this->stepRequest($step->ownerId);
            }
        } else {
            if ($step->reviewStepType == 'external') {
                $this->completeExternal($package);
                ## duplicate complete step
                $this->_insertReviewStep($package, $previousStep, $step->sortOrder);
            }
            elseif ($step->reviewStepType == 'assessment') {
                $this->unlockPackage($package);
                ## duplicate assessment step
                $this->_insertReviewStep($package, $step, $step->sortOrder);
            }
            elseif ($previousStep) {
                ## duplicate assessment step and review step
                $this->_insertReviewStep($package, $previousStep, $step->sortOrder);
                $this->_insertReviewStep($package, $step, $step->sortOrder);
            }
        }
        ## send notification to reviewer
        if ($step->reviewStepType == 'external' || $step->reviewStepType == 'review') {
            Lantra::$app->notify->sendStepUpdate($step, $previousStep->reviewUser->one());
        }
        ## send notification to user for assessment and complete
        else {
            Lantra::$app->notify->sendStepUpdate($step, $package->author);
        }
        $package->log('Review step update ' . $step->reviewStepType . ' - ' . $step->reviewStepName . ' [' . ($passed ? 'passed' : 'failed') . ']');
    }

    /**
     * @param $package
     */
    public function lockPackage($package)
    {
        $package->setFieldValue('packageStatus', 'locked');
        $package->save();
    }

    /**
     * @param $package
     */
    public function unlockPackage($package)
    {
        $package->setFieldValue('packageStatus', 'active');
        $package->save();
    }

    /**
     * @param $package
     */
    public function failPackage($package)
    {
        $package->setFieldValue('packageStatus', 'failed');
        $package->save();
    }

    /**
     * @param $package
     */
    public function completePackage($package)
    {
        $package->setFieldValue('packageStatus', 'complete');
        $package->save();
    }

    /**
     * @param $package
     */
    public function completeExternal($package)
    {
        $package->setFieldValue('externalStatus', 'sampled');
        $package->save();
    }

    /**
     * @param $userId
     * @param $taskbookId
     * @param $unitId
     * @param $passed
     */
    public function completeByTaskbook($userId, $taskbookId, $unitId, $passed)
    {
        ## get the package for this user
        $user = LantraHelper::getUser($userId);

        $packages = Entry::find()
            ->section('packages')
            ->authorId($user->id)
            ->packagePaid(true)
            ->all();

        ## run through user record to find packages
        ## if we find a match with this taskbook, autocomplete
        foreach ($packages as $package) {
            $taskbook = $package->packageTaskbook->one();
            $workflow = $taskbook->packageWorkflow->one();
            if ($taskbook->id == $taskbookId && $workflow->autoComplete) {
                $this->completePackageAssessmentByUnit($user, $package, $unitId, $passed);
                if ($passed) {
                    $this->completePackage($package);
                }
                else {
                    $this->failPackage($package);
                }
            }
        }
    }

    /**
     * @param $package
     * @param $unitId
     * @param $passed
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     */
    public function completePackageAssessmentByUnit($user, $package, $unitId, $passed)
    {
        $p = $passed ? 1 :0;

        ## get the module groups where this unit is present (should only be one)
        $moduleGroupIds = $this->getPackageUnitModuleGroupIds($package, $unitId);

        foreach ($moduleGroupIds as $moduleGroupId) {
            ## get the package assessment block, mimic assessment form
            $assessment = $this->moduleGroupAssessment($package, $moduleGroupId);
            $data = [$assessment->id => [
                'assessmentPassed' => $p,
                'assessmentComment' => '[auto assessment from unit test]'
            ]];
            $this->assessment($package, $data);
        }
    }

    /**
     * @param $package
     * @param $moduleGroupId
     * @return mixed|null
     */
    public function moduleGroupAssessment($package, $moduleGroupId)
    {
        foreach($package->packageAssessment as $a) {
            if (in_array($moduleGroupId, $a->assessmentModuleGroup->ids())) {
                return $a;
            }
        }
        return null;
    }

    /**
     * @param $package
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     */
    public function endorsePackageUnits($package)
    {
        ## set unit results as endorsed
        $unitResults = Lantra::$app->results->getPackageUserResults($package->id, $package->authorId);
        foreach ($unitResults as $resultEntry) {
            $resultEntry->setFieldValue('resultStatus', 'endorsed');
            Craft::$app->elements->saveElement($resultEntry);
        }
    }

    /**
     * @param $package
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     */
    public function revisionPackageUnits($package)
    {
        ## set unit results as revision
        $unitResults = Lantra::$app->results->getPackageUserResults($package->id, $package->authorId, 'unit');
        foreach ($unitResults as $resultEntry) {
            $resultEntry->setFieldValue('resultStatus', 'revision');
            Craft::$app->elements->saveElement($resultEntry);
        }
    }

    /**
     * @param $package
     * @param $userId
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     */
    public function stepAddExternal($package, $userId)
    {
        $field = Craft::$app->fields->getFieldByHandle('packageReviews');
        $sp = new SuperTableService();
        $stepBlockType = $sp->getBlockTypesByFieldId($field->id)[0];
        $block = new SuperTableBlockElement();
        $block->fieldId = $field->id;
        $block->ownerId = $package->id;
        $block->typeId = $stepBlockType->id;
        $block->sortOrder = $package->packageReviews->count();

        $block->setFieldValues([
            'reviewStepId' => 'external',
            'reviewStepName' => 'External',
            'reviewStepType' => 'external',
            'reviewUser' => [$userId]
        ]);
        Craft::$app->elements->saveElement($block);
    }

    /**
     * @param $package
     * @throws \Throwable
     */
    public function stepRemoveExternal($package)
    {
        foreach($package->packageReviews as $step) {
            if ($step->reviewStepType == 'external') {
                Craft::$app->elements->deleteElementById($step->id);
            }
        }
    }

    /**
     * @param $package
     * @param $step
     * @param $sortOrder
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     */
    private function _insertReviewStep($package, $step, $sortOrder)
    {
        $field = Craft::$app->fields->getFieldByHandle('packageReviews');
        $sp = new SuperTableService();
        $stepBlockType = $sp->getBlockTypesByFieldId($field->id)[0];
        $block = new SuperTableBlockElement();
        $block->fieldId = $field->id;
        $block->ownerId = $package->id;
        $block->typeId = $stepBlockType->id;
        $block->sortOrder = $sortOrder;

        $block->setFieldValues([
            'reviewStepId' => $step->reviewStepId,
            'reviewStepName' => $step->reviewStepName,
            'reviewStepType' => $step->reviewStepType,
            'reviewUser' => [$step->reviewUser->one()->id],
            'reviewSampled' => false
        ]);
        Craft::$app->elements->saveElement($block);
    }

    /**
     * @param Entry|null $package
     * @param $moduleGroupId
     * @return mixed|null
     */
    public function getModuleGroupRow($package = null, $moduleGroupId)
    {
        if (!$package || !$package->packageModuleGroups) {
            return null;
        }
        foreach ($package->packageModuleGroups as $block) {
            $moduleGroup = $block->moduleGroup->one();
            if ($moduleGroup && $moduleGroup->id == $moduleGroupId) {
                return $block;
            }
        }
        return null;
    }

    /**
     * @todo move to behaviour
     *
     * @param $package
     * @param bool $complete
     * @return mixed
     */
    public function countPackageUnits($package, $complete = false)
    {
        $entries = $this->getPackageModuleEntries($package);
        if ($complete) {
            return Lantra::$app->modules->unitsComplete($entries, $package->author);
        }
        return Lantra::$app->modules->totalUnits($entries);
    }

    /**
     * @param $package
     * @return mixed
     */
    public function countPackageUnitsEndorsed($package)
    {
        $return = 0;
        $units = $this->getPackageUnits($package);
        foreach ($units as $unit) {
            $result = Lantra::$app->results->getUnitResult($package->author->id, $unit->id);
            if ($result->resultStatus == 'endorsed') {
                $return++;
            }
        }
        return $return;
    }

    /**
     * @param $package
     * @return bool
     */
    public function isPackageUnitsEndorsed($package)
    {
        $units = $this->countPackageUnits($package);
        $endorsed = $this->countPackageUnitsEndorsed($package);
        return $endorsed == $units;
    }

    /**
     * @param $package
     * @param string $return
     * @return array|\craft\base\ElementInterface[]|Entry[]|int[]
     */
    public function getPackageUnits($package, $return = 'ids')
    {
        $moduleEntryIds = $this->getPackageModuleIds($package);
        $criteria = Entry::find();
        $criteria->section = 'units';
        $criteria->relatedTo(['sourceElement' => $moduleEntryIds, 'field' => 'moduleUnitGroups.unitEntries']);
        return $return == 'ids' ?  $criteria->ids() : $criteria->all();
    }

    /**
     * @param $package
     * @param $unitId
     * @return mixed
     */
    public function getPackageUnitModuleGroupIds($package, $unitId)
    {
        $field = Craft::$app->fields->getFieldByHandle('moduleUnitGroups');

        ## find related modules
        $criteria = MatrixBlock::find();
        $criteria->fieldId($field->id);
        $criteria->relatedTo([$unitId]);

        $moduleIds = [];
        foreach($criteria->all() as $block) {
            $moduleIds[] = $block->ownerId;
        }

        ## find related module groups
        $criteria = Category::find();
        $criteria->structureId = false;
        $criteria->groupId = LantraHelper::groupId('moduleGroups');
        $criteria->relatedTo($moduleIds);

        return $criteria->ids();
    }

    /**
     * @param $package
     * @return mixed
     */
    public function getPackageUnitIds($package)
    {
        return $this->getPackageUnits($package, 'ids');
    }

    /**
     * @param $package
     * @param string $return
     * @return array|\craft\base\ElementInterface[]|Entry[]|int[]
     */
    public function getPackageModuleEntries($package, $return = 'all')
    {
        $categoryIds = $this->getPackageModuleGroupIds($package);
        $criteria = Entry::find();
        $criteria->section = 'modules';
        $criteria->relatedTo(['targetElement' => $categoryIds, 'field' => 'moduleGroup']);
        return $return == 'ids' ?  $criteria->ids() : $criteria->all();
    }

    /**
     *
     * @param $package
     * @return array
     */
    public function getPackageModuleIds($package)
    {
        return $this->getPackageModuleEntries($package, 'ids');
    }

    /**
     * @param $package
     * @return array
     */
    public function getPackageModuleGroupIds($package)
    {
        $categoryIds = [];
        foreach($package->packageModuleGroups->all() as $moduleGroupBlock) {
            if (null != $moduleGroupId = $moduleGroupBlock->moduleGroup->one()->id)  {
                $categoryIds[] = $moduleGroupId;
            }
        }
        return $categoryIds;
    }

    /**
     * @param $packageId
     * @param User $user
     * @return array|\craft\base\ElementInterface|Entry|null
     */
    public function getUserPackage($packageId, User $user)
    {
        $criteria = Entry::find();
        $criteria->id = $packageId;
        $criteria->authorId = $user->id;
        return $criteria->count() ? $criteria->one() : null;
    }

    /**
     * @param User $user
     * @param null $taskbookId
     * @return int|string
     */
    public function userPackageExists(User $user, $taskbookId = null)
    {
        $criteria = Entry::find();
        $criteria->authorId = $user->id;
        $criteria->relatedTo = [
            'targetElement' => $taskbookId,
            'field' => 'packageTaskbook'
        ];
        return $criteria->count();
    }

    /**
     * @param $user
     * @param $paid
     * @return null
     */
    public function getUserPackages(User $user, $paid = true)
    {
        $criteria = Entry::find();
        $criteria->section = 'packages';
        $criteria->authorId = $user->id;
        if ($paid) {
            $criteria->packagePaid = true;
        }
        return $criteria->all();
    }

    /**
     * @param $user
     * @return null
     */
    public function getUnpaidPackages(User $user)
    {
        $criteria = Entry::find();
        $criteria->section = 'packages';
        $criteria->authorId = $user->id;
        $criteria->packagePaid = false;
        return $criteria->all();
    }

    /**
     * @param $package
     * @param $unitId
     * @return bool
     */
    public function unitExists($package, $unitId)
    {
        $units = $this->getPackageUnits($package);
        foreach ($units as $unit) {
            if ($unit->id == $unitId) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param string $search
     * @param int $limit
     * @param string $order
     * @param null $filterBy
     * @param string $filterValue
     * @param null $dateFrom
     * @param null $dateTo
     * @param User $assessor
     * @return \craft\elements\db\ElementQueryInterface|\craft\elements\db\EntryQuery
     * @throws \Exception
     */
    public function packagesCriteria($search = '', $limit = 25, $order = 'title', $filterBy = null, $filterValue = 'all', $dateFrom = null, $dateTo = null, $companyId = null, User $assessor)
    {
        $criteria = Entry::find();
        $criteria->section = 'packages';
        $criteria->limit = $limit;
        $criteria->orderBy = $order;
        if ($search) {
            $criteria->search = 'title:' . $search;
        }
        if ($filterBy == 'status' && $filterValue != 'all') {
            $criteria->packageStatus = $filterValue;
        }
        if ($filterBy == 'name' && $filterValue != 'all') {
            ## assessment gets all assessment steps
            if ($filterValue == 'Assessment') {
                $criteria->id = $this->getRelatedPackageIds($assessor, 'assessment');
            } else {
                $criteria->id = $this->getRelatedPackageIds($assessor, null, $filterValue);
            }
        }
        elseif ($filterBy == 'external') {
            $criteria->id = $this->getExternalPackageIds($assessor, $filterValue, $companyId);
        }
        else {
            $criteria->id = $this->getRelatedPackageIds($assessor);
        }
        ## handle date filters
        $df = $dateFrom ? $this->convertDate($dateFrom) : false;
        $dt = $dateTo ? $this->convertDate($dateTo, 12, 59, 59) : false;
        if ($df && $dt) {
            $criteria->dateCreated = ['and','>= '. $df, '<= '. $dt];
        }
        elseif ($df) {
            $criteria->dateCreated = '> '. $df;
        }
        elseif ($dt) {
            $criteria->dateCreated = '< '. $dt;
        }
        return $criteria;
    }

    /**
     * @param string $dateString
     * @param string $h
     * @param string $m
     * @param string $s
     * @return string|void
     * @throws \Exception
     */
    private function convertDate($dateString = '', $h = '00', $m = '00', $s = '00')
    {
        $parts = explode('/', $dateString);
        if (count($parts) != 3) {
            return;
        }
        return DateTimeHelper::toDateTime($parts[2] . '-' . $parts[1] . '-' . $parts[0])->setTime($h, $m, $s)->format(\DateTime::ATOM);
    }

    /**
     * @param User $assessor
     * @param null $type
     * @param null $name
     * @return array
     */
    public function getRelatedPackageIds(User $assessor, $type = null, $name = null)
    {
        $isAdmin = Lantra::$app->users->isLantraAdmin($assessor);

        ## lantra admin sees all packages
        if ($isAdmin) {
            $query = Entry::find();
            $query->section = 'packages';
            $query->limit = null;
        }
        else {
            $supertableService = new SuperTableService();
            $params = [
                'elementType' => 'craft\\elements\\Entry',
                'relatedTo' => [
                    'targetElement' => $assessor->id,
                    'field' => 'packageReviews.reviewUser'
                ]
            ];
            ## get all the related steps
            $query = $supertableService->getRelatedElementsQuery($params);
        }

        if (!$type && !$name) {
            return $query ? $query->ids() : [];
        }

        ## filter ids by type or name
        $ids = [];
        foreach($query->all() as $packageEntry) {
            ## only add it once
            if (in_array($packageEntry->id, $ids)) {
                continue;
            }
            ## ignore complete packages
            if (null == $nextStep = $packageEntry->getNextStep()) {
                continue;
            }
            ## match both type and name or type or name
            if (($type && $name && $nextStep->reviewStepType == $type && $nextStep->reviewStepName == $name) ||
                ($type && $nextStep->reviewStepType == $type) ||
                ($name && $nextStep->reviewStepName == $name)) {
                $ids[] = $packageEntry->id;
            }
        }
        return $ids;
    }

    public function getExternalUserIds(User $eqa, $companyId = null)
    {
        $companyIds = $companyId ? [$companyId] : $eqa->userExternalCompanies;
        $criteria = User::find();
        $criteria->relatedTo = ['targetElement' => $companyIds, 'field' => 'userCompany'];
        $criteria->limit = null;
        return $criteria->ids();
    }

    /**
     * @param User $eqa
     * @return array|int[]
     */
    public function getExternalPackageIds(User $eqa, $externalStatus = 'all', $companyId = null)
    {
        ## check user can eqa taskbooks
        if (!count($eqa->userExternalTaskbooks)) {
            return [];
        }

        ## reset company id
        $companyId = $companyId == 'all' ? null : (int) $companyId;
        $userIds = $this->getExternalUserIds($eqa, $companyId);

        ## check users exist
        if (!count($userIds)) {
            return [];
        }

        ## get relevant packages
        $criteria = Entry::find();
        $criteria->section = 'packages';
        $relatedTo = [
            'and',
            ['targetElement' => $eqa->userExternalTaskbooks, 'field' => 'packageTaskbook']
        ];
        if ($externalStatus == 'selected') {
            $criteria->externalStatus = 'selected';
            $relatedTo[] = ['targetElement' => $eqa->id, 'field' => 'externalAssessor'];
        }
        elseif ($externalStatus == 'notSelected') {
            $criteria->packageStatus = 'complete';
            $criteria->externalStatus = 'notSelected';
        }
        elseif ($externalStatus == 'sampled') {
            $criteria->externalStatus = 'sampled';
            $relatedTo[] = ['targetElement' => $eqa->id, 'field' => 'externalAssessor'];
        }
        $criteria->relatedTo = $relatedTo;
        $criteria->limit = null;
        $criteria->authorId = $userIds;
        return $criteria->ids();
    }

    /**
     * @param $user
     * @return array
     */
    public function packageTypes($user, $external = true)
    {
        $types = [];
        $criteria = Entry::find();
        $criteria->section = 'workflows';
        $criteria->limit = null;

        $assessment = false;
        foreach($criteria->all() as $entry) {
            foreach($entry->workflow as $workflow) {
                if ($workflow->stepType == 'assessment' && !$assessment) {
                    $assessment = true;
                    ## $ids = $this->getRelatedPackageIds($user, 'assessment');
                    $types[] = [
                        'name' => 'Assessment',
                        'count' => 0 ## count($ids)
                    ];
                }
                ## add review steps as step name
                else {
                    ## $ids = $this->getRelatedPackageIds($user, null, $workflow->stepName);
                    $types[] = [
                        'name' => $workflow->stepName,
                        'count' => 0 ## count($ids)
                    ];
                }
            }
        }
        return $types;
    }

    /**
     * @param $package
     * @param $categoryId
     * @return bool|null
     * @throws \Throwable
     */
    public function removeModuleGroup($package, $categoryId)
    {
        if (null !== $block = $package->moduleGroupBlock($categoryId)) {
           if (Craft::$app->elements->deleteElementById($block->id)) {
                $package->log('Module group removed [' . $categoryId . ']');
           }
        }
        return;
    }

    /**
     * @param Entry $taskbook
     * @param string $singleType
     * @return int
     */
    public function getModuleGroupCost(Entry $taskbook, $singleType = null)
    {
        return $singleType == null ? 0 : ($singleType == 'resit' ?  $taskbook->moduleResitCost : $taskbook->moduleSingleCost);
    }

    /**
     * @param $user
     * @param $taskbook
     * @return null
     */
    public function getReviewers($user, $taskbook, $jobRoleIds)
    {
        if (null == $userCompany = $user->userCompany->one()) {
            return null;
        }
        $criteria = User::find();
        $criteria->relatedTo = [
            'and',
            ['targetElement' => [$userCompany->id], 'field' => 'userTaskbookCompanies'],
            ['targetElement' => [$taskbook->id], 'field' => 'userTaskbooks'],
            ['targetElement' => $jobRoleIds, 'field' => 'userRole']
        ];
        return $criteria->ids();
    }

    /**
     * @param $package
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @throws \yii\web\HttpException
     * @throws \yii\web\RangeNotSatisfiableHttpException
     */
    public function exportPackage($package)
    {
        $filename = 'package-' . $package->id . '-' . time() . '.xlsx';
        $record = $package->author->record;
        $packageItem = $record->getItem($package->id);

        $header = [
            'ID',
            'Module Group Title',
            'Module Title',
            'Unit Title',
            'Unit Heading',
            'Start Date',
            'Finish Date',
            'Expiry Date',
            'Location',
            'Narrative',
            'Evidence',
            'Comments',
            'Status',
            'Endorsed Date'
        ];

        if (getenv('SITE') == 'bics') {
            $header = array_merge($header, ['SA Status', 'AE Status']);
        }

        $data = [$header];
        foreach($packageItem->items as $moduleGroupItem) {
            $moduleGroupTitle = true;
            $moduleGroup = $record->getElement($moduleGroupItem->elementId);
            foreach($moduleGroupItem->items as $moduleItem) {
                $moduleTitle = true;
                $module = $record->getElement($moduleItem->elementId);
                foreach($moduleItem->items as $unitGroupItem) {
                    foreach($unitGroupItem->items as $unitItem) {
                        $unit = $record->getElement($unitItem->elementId);
                        $result = $record->getUnitResult($unit->id);
                        $resultEvidence = [];
                        $resultComments = [];

                        if ($result) {
                            foreach ($result->resultEvidence as $asset) {
                                $resultEvidence[] = $asset->filename;
                            }
                            foreach ($result->resultComments as $comment) {
                                $resultComments[] = '[ ' . ($comment->date ? $comment->date->format('d/m/Y') : '-') . '] ' . $comment->comment;
                            }
                        }

                        $row = [
                            $unit->id,
                            $moduleGroupTitle ? $moduleGroup->title : '',
                            $moduleTitle ? $module->title : '',
                            $unit->title,
                            $unit->unitHeading,
                            $result && $result->resultStartDate ? $result->resultStartDate->format('d/m/Y') : '-',
                            $result && $result->resultFinishDate ? $result->resultFinishDate->format('d/m/Y') : '-',
                            $result && $result->expiryDate ? $result->expiryDate->format('d/m/Y') : '-',
                            $result && $result->resultLocation ? $result->resultLocation : '-',
                            $result && $result->resultNarrative ? strip_tags($result->resultNarrative) : '-',
                            $result && count ($resultEvidence) ? implode(',', $resultEvidence) : '-',
                            $result && count ($resultComments) ? implode(',', $resultComments) : '-',
                            $result ? $result->resultStatus : '-',
                            $result && $result->resultEndorsedDate ? $result->resultEndorsedDate->format('d/m/Y') : '-'
                        ];

                        if (getenv('SITE') == 'bics') {
                            foreach ($result->resultCustom as $r) {
                                if isset($r->customKey == 'sa_status' || $r->customKey == 'ae_status') {
                                    $row[] = $r->customValue;
                                }
                            }
                        }

                        $data[] = $row;

                        $moduleGroupTitle = false;
                        $moduleTitle = false;
                    }
                }
            }
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        for ($i = 0, $l = sizeof($data); $i < $l; $i++) {
            $j = 0;
            foreach ($data[$i] as $k => $v) {
                $sheet->setCellValueByColumnAndRow($j + 1, ($i + 1), $v);
                $j++;
            }
        }
        $mime = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
        $writer = new Xlsx($spreadsheet);

        ob_start();
        $writer->save('php://output');
        $content = ob_get_clean();
        Craft::$app->response->sendContentAsFile($content, $filename, ['mimeType' => $mime]);
    }

    /**
     * @param $taskbook
     * @param $optionalModuleGroups
     * @return bool
     */
    private function isCompleteCredits($taskbook, $optionalModuleGroups)
    {
        if (!$taskbook->moduleMinimumCredits) {
            return true;
        }
        $credits = 0;
        $levelCredits = 0;
        ## add the mandatory credits
        foreach ($taskbook->moduleGroups('mandatory') as $mandatory) {
            $credits += $mandatory['credit'];
            if ($mandatory['level'] <= $taskbook->moduleGroupMinimumLevel) {
                $levelCredits += $mandatory['credit'];
            }
        }
        foreach ($optionalModuleGroups as $id => $optional) {
            $block = $taskbook->moduleGroupBlock($id);
            $credits += $block->moduleGroupCredit;
            if (isset($optional['level']) && $optional['level'] >= $taskbook->moduleGroupMinimumLevel) {
                $levelCredits += $block->moduleGroupCredit;
            }
        }
        if ($taskbook->moduleGroupMinimumLevelCredits) {
            return $credits >= $taskbook->moduleMinimumCredits && $levelCredits >= $taskbook->moduleGroupMinimumLevelCredits;
        }
        return $credits >= $taskbook->moduleMinimumCredits;
    }
}