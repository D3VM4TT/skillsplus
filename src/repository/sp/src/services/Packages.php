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
        $coreModule = $entry->packageCoreModule ? $entry->packageCoreModule->one() : null;
        if (!$coreModule) {
            $entry->addError('packageCoreModule', 'You must select a core module.');
            $event->isValid = false;
            return;
        }
        if ($coreModule->type->name != 'Taskbook' || !$coreModule->moduleCoreModule) {
            $entry->addError('packageCoreModule', 'You must select a Taskbook type core module.');
            $event->isValid = false;
        }

        $entry->title = '[' . $coreModule->title . '] ' . $entry->author->fullname;
        $totalOptional = $entry->packageOptionalModules ? $entry->packageOptionalModules->count() : 0;

        ## check minimum optional modules
        if ($coreModule->moduleMinimumOptional && $totalOptional < $coreModule->moduleMinimumOptional) {
            $entry->addError('packageOptionalModules', 'You must select a minimum of ' . $coreModule->moduleMinimumOptional . ' optional modules.');
            $event->isValid = false;
        }

        ## calculate cost
        $cost = $coreModule->moduleMaxCost;
        if ($coreModule->moduleCosts) {
            foreach ($coreModule->moduleCosts as $row) {
                if ($totalOptional == $row['optionalModules']) {
                    $cost = (int)$row['cost'];
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
        if (!$entry->packageReviews->count()) {
            $this->applyPackageWorkflow($entry);
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
                $step->stepId =  'step-' . $s;
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
            $packageReviews['new'.$n] = [
                'type'      => $stepBlockType->id,
                'enabled'   => true,
                'fields' => [
                    'reviewStepId'      => $step->stepId,
                    'reviewStepName'    => $step->stepName,
                    'reviewStepType'    => $step->stepType
                ]
            ];
            $n++;
        }
        $entry->setFieldValues(['packageReviews' => $packageReviews]);
        Craft::$app->elements->saveElement($entry);
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
     * @throws \Throwable
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\SyntaxError
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     */
    public function stepRequest($packageId)
    {
        if (null == $package =  Entry::findOne($packageId)) {
            return null;
        }

        if (null != $nextStep = $package->nextStep) {
            $this->setStatus($package->id, $nextStep->reviewStepType);
            Lantra::$app->notify->sendStepRequest($nextStep);
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
        $step->setFieldValue('reviewPassed', $passed);
        $step->setFieldValue('reviewComment', $comment);
        $step->setFieldValue('reviewDate', time());
        if(Craft::$app->elements->saveElement($step)) {
            Lantra::$app->notify->sendStepUpdate($step);
        };
        ## ask for the next step if applicable
        if ($passed) {
            $this->stepRequest($step->ownerId);
        }
    }

    /**
     * @param Entry|null $package
     * @param $moduleId
     * @return mixed|null
     */
    public function getOptionalModuleRow($package = null, $moduleId)
    {
        if (!$package || !$package->packageOptionalModules) {
            return null;
        }
        foreach ($package->packageOptionalModules as $row) {
            if ($row->optionalModule->one()->id == $moduleId) {
                return $row;
            }
        }
        return null;
    }

    /**
     *
     * @param $packageId
     * @param $payment
     * @return bool
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     */
    public function setPaid($packageId, $payment = [])
    {
        if (null == $package = Entry::findOne($packageId)) {
            return false;
        }
        $package->setFieldValue('packagePaid', true);
        return Craft::$app->elements->saveElement($package);
    }

    /**
     * @todo move to behaviour
     *
     * @param $packageId
     * @param $status
     * @return bool
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     */
    public function setStatus($packageId, $status)
    {
        if (null == $package = Entry::findOne($packageId)) {
            return false;
        }
        $package->setFieldValue('packageStatus', $status);
        return Craft::$app->elements->saveElement($package);
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
     * @todo move to behaviour
     *
     * @param $package
     * @return array
     */
    public function getPackageModules($package)
    {
        if (!$package) {
            return [];
        }
        $modules = [
            [
                'entry' => $package->packageCoreModule->one(),
                'level' => $package->packageCoreLevel
            ]
        ];
        foreach ($package->packageOptionalModules as $optionalModuleBlock) {
            $modules[] = [
                'entry' => $optionalModuleBlock->optionalModule->one(),
                'level' => $optionalModuleBlock->optionalLevel
            ];
        }
        return $modules;
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
     * @todo move to behaviour
     *
     * @param $package
     * @return array
     */
    public function getPackageModuleEntries($package)
    {
        $modules = $this->getPackageModules($package);
        $entries = [];
        foreach ($modules as $module) {
            $entries[] = $module['entry'];
        }
        return $entries;
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