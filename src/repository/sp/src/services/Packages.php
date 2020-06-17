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
use \DateTime;
use lantra\sp\helpers\LantraHelper;
use lantra\sp\Plugin as Lantra;
use verbb\supertable\elements\SuperTableBlockElement;
use verbb\supertable\services\SuperTableService;

class Packages extends Component
{
    /**
     * @param ModelEvent $event
     * @param Entry $entry
     */
    public function onBeforeSavePackageWorkflow(ModelEvent $event, Entry $entry)
    {
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
            if ($step->stepAssignUserGroup == 'jobRole' && !$step->stepAssignJobRole->count()) {
                $step->addError('stepAssignJobRole', 'Job role(s) required.');
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
        $moduleGroup = $entry->packageModuleGroup ? $entry->packageModuleGroup->last() : null;
        if (!$moduleGroup) {
            $entry->addError('packageModuleGroup', 'You must select a module group.');
            $event->isValid = false;
            return;
        }
        if (!$moduleGroup->moduleGroupTaskbooks) {
            $entry->addError('packageModuleGroup', 'You must select a Taskbook type module group.');
            $event->isValid = false;
        }

        $entry->title = '[' . $moduleGroup->title . '] ' . $entry->author->fullname;
        $totalOptional = $entry->packageOptionalModuleGroups ? $entry->packageOptionalModuleGroups->count() : 0;

        ## check minimum optional modules
        if ($moduleGroup->moduleMinimumOptional && $totalOptional < $moduleGroup->moduleMinimumOptional) {
            $entry->addError('packageOptionalModules', 'You must select a minimum of ' . $moduleGroup->moduleMinimumOptional . ' optional modules.');
            $event->isValid = false;
        }

        ## calculate cost
        if ($moduleGroup->level == 2) {
            $cost = $moduleGroup->moduleSingleCost;
        } else {
            $cost = $moduleGroup->moduleMaxCost;
            if ($moduleGroup->moduleCosts) {
                foreach ($moduleGroup->moduleCosts as $row) {
                    if ($totalOptional == $row['optionalModules']) {
                        $cost = (int)$row['cost'];
                    }
                }
            }
        }
        $entry->setFieldValue('packageCost', $cost);

        ## make sure log is clear
        $entry->setFieldValue('packageLog', []);

        ## package is free
        if (!$cost) {
            $entry->setFieldValue('packagePaid', true);
        }

        ## set default workflow
        if (!$entry->packageWorkflow->count()) {
            $defaultWorkflow = LantraHelper::setting('defaultWorkflow');
            if (!$defaultWorkflow) {
                $entry->addError('packageWorkflow', 'You must select a package workflow or set default workflow in settings.');
                $event->isValid = false;
            } else {
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
        if (!$entry->packageReviews->count()) {
            $this->applyPackageWorkflow($entry);
        }
        if (!$entry->packageAssessment->count()) {
            $this->applyPackageAssessment($entry);
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
     * @param $entry
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     */
    public function applyPackageWorkflow(Entry $entry)
    {
        $packageWorkflow = $entry->packageWorkflow->one()->workflow;
        if (!count($packageWorkflow)) {
            return;
        }
        $sp = new SuperTableService();
        $field = Craft::$app->fields->getFieldByHandle('packageReviews');
        $stepBlockType = $sp->getBlockTypesByFieldId($field->id)[0];
        $packageReviews = [];
        $n = 1;
        foreach ($packageWorkflow as $step) {
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
     * @param $entry
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     */
    public function applyPackageAssessment(Entry $entry)
    {
        $sp = new SuperTableService();
        $field = Craft::$app->fields->getFieldByHandle('packageAssessment');
        $assessmentBlockType = $sp->getBlockTypesByFieldId($field->id)[0];
        $moduleGroupIds = [$entry->packageModuleGroup->last()->id];
        foreach ($entry->packageOptionalModuleGroups as $block) {
            $moduleGroupIds[] = $block->optionalModuleGroup->last()->id;
        }
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
        $entry->setFieldValues(['packageAssessment' => $packageAssessment]);
        Craft::$app->elements->saveElement($entry);
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
    }

    /**
     * @param SuperTableBlockElement $step
     * @param $passed
     * @param string $comment
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     */
    public function stepUpdate(SuperTableBlockElement $step, $passed, $comment = '')
    {
        $package = $step->owner;
        $previousStep = $package->previousStep;
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
            if ($step->reviewStepType == 'complete' || ($step->reviewStepType == 'assessment' && $package->totalSteps == 1)) {
                $this->completePackage($package);
            } else {
                $this->stepRequest($step->ownerId);
            }
        } else {
            if ($step->reviewStepType == 'assessment') {
                $this->unlockPackage($package);
                ## duplicate assessment step
                $this->_insertReviewStep($package, $step, $step->sortOrder);
            }
            if ($previousStep) {
                ## duplicate assessment step and review step
                $this->_insertReviewStep($package, $previousStep, $step->sortOrder);
                $this->_insertReviewStep($package, $step, $step->sortOrder);
            }
        }
        ## send notification to reviewer
        if ($step->reviewStepType == 'review') {
            Lantra::$app->notify->sendStepUpdate($step, $previousStep->reviewUser->one());
        } ## send notification to user for assessment and complete
        else {
            Lantra::$app->notify->sendStepUpdate($step, $package->author);
        }
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
    public function completePackage($package)
    {
        $package->setFieldValue('packageStatus', 'complete');
        $package->save();
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
            'reviewUser' => [$step->reviewUser->one()->id]
        ]);
        Craft::$app->elements->saveElement($block);
    }

    /**
     * @param Entry|null $package
     * @param $moduleGroupId
     * @return mixed|null
     */
    public function getOptionalModuleGroupRow($package = null, $moduleGroupId)
    {
        if (!$package || !$package->packageOptionalModuleGroups) {
            return null;
        }
        foreach ($package->packageOptionalModuleGroups as $row) {
            if ($row->optionalModuleGroup->one()->id == $moduleGroupId) {
                return $row;
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
     * @return array
     */
    public function getPackageUnits($package)
    {
        $moduleEntries = $this->getPackageModuleEntries($package);
        return Lantra::$app->modules->moduleUnits($moduleEntries);
    }

    /**
     *
     * @param $package
     * @return array
     */
    public function getPackageModuleEntries($package)
    {
        $criteria = Entry::find();
        $criteria->section = 'modules';
        $criteria->relatedTo(['targetElement' => $package->moduleGroup->id, 'field' => 'moduleGroup']);
        return $criteria->all();
    }

    /**
     * @param $packageId
     * @param $user
     * @return null
     */
    public function getUserPackage($packageId, User $user)
    {
        $criteria = Entry::find();
        $criteria->id = $packageId;
        $criteria->authorId = $user->id;
        return $criteria->count() ? $criteria->one() : null;
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
     * @param int $limit
     * @param string $order
     * @param User $assessor
     * @param null $type
     * @return \craft\elements\db\ElementQueryInterface|\craft\elements\db\EntryQuery|null
     */
    public function packagesCriteria($limit = 25, $order = 'title', User $assessor, $type = null)
    {
        $criteria = Entry::find();
        $criteria->section = 'packages';
        $criteria->limit = $limit;
        $criteria->orderBy = $order;

        if ($assessor->admin || $assessor->isInGroup('schemeManagers')) {
            $criteria->authorId = 'not ' . $assessor->id;
        } else {
            $ids = $this->getRelatedPackageIds($assessor, $type);
            if (!count($ids)) {
                return null;
            }
            $criteria->id = $ids;
        }

        return $criteria;
    }

    /**
     * @param User $assessor
     * @param null $type
     * @return array
     */
    public function getRelatedPackageIds(User $assessor, $type = null)
    {
        $supertableService = new SuperTableService();
        $params = [
            'elementType' => 'craft\\elements\\Entry',
            'relatedTo' => [
                'targetElement' => $assessor->id,
                'field' => 'packageReviews.reviewUser'
            ]];
        if ($type) {
            $params['criteria']['reviewStepType'] = $type;
        }
        $query = $supertableService->getRelatedElementsQuery($params);
        return $query ? $query->ids() : [];
    }
}