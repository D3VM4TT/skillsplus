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
use lantra\sp\Plugin as Lantra;
use verbb\supertable\elements\SuperTableBlockElement;
use verbb\supertable\services\SuperTableService;

class Packages extends Component
{
    /**
     * @param ModelEvent $event
     * @param GlobalSet $globalSet
     */
    public function onBeforeSavePackageWorkflow(ModelEvent $event, GlobalSet $globalSet)
    {
        ## check steps in order assessment -> review -> complete
        $assessment = false;
        $review = false;
        $complete = false;
        $error = false;

        foreach($globalSet->packageWorkflow as $step) {
            if ($step->stepType->value == 'assessment') {
                $assessment = true;
                if ($review || $complete) {
                    $error = 'Assessments can not follow review or complete step.';
                    $event->isValid = false;
                }
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
                $step->addError('stepJobRole',  'Job role(s) required.');
                $event->isValid = false;
            }
            if ($step->stepAssignUserGroup == 'jobRole' && !$step->stepAssignJobRole->count()) {
                $step->addError('stepAssignJobRole', 'Job role(s) required.');
                $event->isValid = false;
            }
        }
        if (!$complete) {
            $error = 'Workflow must contain a complete step.';
            $event->isValid = false;
        }
        if ($error) {
            $globalSet->addError('packageWorkflow', $error);
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
            $entry->addError('packageCoreModule', 'You must select a core module -TEST.');
            $event->isValid = false;
            return;
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

        ## package is free
        if (!$cost) {
            $entry->setFieldValue('packagePaid', true);
        }
    }

    /**
     * @param ModelEvent $event
     * @param Entry $entry
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     */
    public function onSavePackage(ModelEvent $event, Entry $entry)
    {
        if ($event->isNew) {
            ## add package workflow from globals
            if (null != $packageWorkflow = $this->getPackageWorkflow()) {
                $field = Craft::$app->fields->getFieldByHandle('packageReviews');
                $sp = new SuperTableService();
                $stepBlockType = $sp->getBlockTypesByFieldId($field->id)[0];
                foreach ($packageWorkflow as $step) {
                    $review = new SuperTableBlockElement();
                    $review->ownerId = $entry->id;
                    $review->typeId = $stepBlockType->id;
                    $review->fieldId = $field->id;
                    $review->setFieldValue('reviewStepName', $step->stepName);
                    $review->setFieldValue('reviewStepType', $step->stepType);
                    Craft::$app->elements->saveElement($review);
                }
            }
            $this->log($entry, 'Package created');
            ## redirect to paypal payment
            if (Craft::$app->request->isSiteRequest && !$entry->packagePaid) {
                ## @todo redirect to PayPal
            }
        }
    }


    /**
     * @return \verbb\supertable\elements\db\SuperTableBlockQuery
     */
    public function getPackageWorkflow()
    {
        $globalsSet = Craft::$app->globals->getSetByHandle('globalsPackage');
        return $globalsSet->packageWorkflow;
    }

    /**
     * @param $package
     * @param $changed
     * @throws \Throwable
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\SyntaxError
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     */
    public function _onSavePackage($package, $changed)
    {
        if ($changed['assessor']) {
            $this->log($package, 'Assessor assigned' );
            Lantra::$app->notify->sendPackageAssigned($package, 'assessor', $package->packageAssessor->one()->fullName);
        }
        if ($changed['reviewer']) {
            $this->log($package, 'Reviewer assigned');
            Lantra::$app->notify->sendPackageAssigned($package, 'reviewer', $package->packageReviewer->one()->fullName);
        }
        if ($changed['status']) {
            $this->log($package, 'Package status changed to ' . $package->packageStatus);
            Lantra::$app->notify->sendPackageStatus($package);
            if ($package->packageStatus == 'reviewed') {
                Lantra::$app->notify->sendPackageReviewed($package);
            }
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
        foreach($package->packageOptionalModules as $row) {
            if ($row->optionalModule->one()->id == $moduleId) {
                return $row;
            }
        }
        return null;
    }

    /**
     * @todo move to behaviour
     *
     * @param $package
     * @param $comment
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     */
    public function addComment($package, $comment)
    {
        Lantra::$app->packages->log($package, 'Comment: ' . $comment);
        Lantra::$app->notify->sendPackageComment($package, $comment);
    }

    /**
     * @todo move to behaviour
     *
     * @param $package
     * @param $message
     * @return bool
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     */
    public function log($package, $message, $admin = '')
    {
        if (!$message) {
            return;
        }
        $user = Craft::$app->getUser()->getIdentity();
        $new = [
            'col1'       => time(),
            'col2'       => $message,
            'col3'       => $admin,
            'col4'       => $user->id,
            'col5'       => $user->fullName
        ];
        $packageLog = $package->packageLog;
        $packageLog['new1'] = $new;
        $package->setFieldValue('packageLog', $packageLog);
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
                'entry'    => $package->packageCoreModule->one(),
                'level'    => $package->packageCoreLevel
            ]
        ];
        foreach($package->packageOptionalModules as $optionalModuleBlock) {
            $modules[] = [
                'entry'    => $optionalModuleBlock->optionalModule->one(),
                'level'    => $optionalModuleBlock->optionalLevel
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
    public function getUserPackage($packageId, $user)
    {
        $criteria = new Entry();
        $criteria->id = $packageId;
        $criteria->authorId = $user->id;
        return $criteria->count() ? $criteria->one() : null;
    }

    /**
     * @param $user
     * @param $paid
     * @return null
     */
    public function getUserPackages($user, $paid = true)
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
        foreach($units as $unit) {
            if ($unit->id == $unitId) {
                return true;
            }
        }
        return false;
    }

    /**
     * @todo move to behaviour
     *
     * Checks whether this user has been assigned as assessor, reviewer or completer
     *
     * @param $subordinateId
     * @param User|null $manager
     * @return bool
     */
    public function isPackageManager($subordinateId, User $manager = null)
    {
        if (null == $user = Craft::$app->users->getUserById($subordinateId)) {
            return false;
        }
        $packages = $this->getUserPackages($user);

        if (!count($packages)) {
            return false;
        }
        foreach ($packages as $package) {
            if ($this->isAssessor($package, $manager) || $this->isReviewer($package, $manager) || $this->isCompleter($package, $manager)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @todo move to behaviour
     *
     * @param Entry $package
     * @param User $assessor
     * @param bool $includeAdmin
     * @return bool
     */
    public function isAssessor(Entry $package, User $assessor = null, $includeAdmin = true)
    {
        return $this->isStepManager($package, $assessor, $includeAdmin, 'assessment');
    }

    /**
     * @todo move to behaviour
     *
     * @param Entry $package
     * @param User $reviewer
     * @param bool $includeAdmin
     * @return bool
     */
    public function isReviewer(Entry $package, User $reviewer = null, $includeAdmin = true)
    {
        return $this->isStepManager($package, $reviewer, $includeAdmin, 'review');
    }

    /**
     * @todo move to behaviour
     *
     * @param Entry $package
     * @param User $completer
     * @param bool $includeAdmin
     * @return bool
     */
    public function isCompleter(Entry $package, User $completer = null, $includeAdmin = true)
    {
        return $this->isStepManager($package, $completer, $includeAdmin, 'complete');
    }

    /**
     * @todo move to behaviour
     *
     * @param Entry $package
     * @param User $manager
     * @param bool $includeAdmin
     * @param string $type assessment|review|complete
     * @return bool
     */
    private function isStepManager(Entry $package, User $manager = null, $includeAdmin = true, $type = 'assessment')
    {
        if (is_null($manager)) {
            $manager = Craft::$app->getUser()->getIdentity();
        }
        ## admins and scheme managers can manage everyone
        if ($includeAdmin && ($manager->admin || $manager->isInGroup('schemeManagers'))) {
            return true;
        }
        foreach($package->packageReviews as $step) {
            if ($step->stepType == $type && $step->stepUser->count() && $step->stepUser->one()->id == $manager->id) {
                return true;
            }
        }
        return false;
    }


    /**
     * @param int $limit
     * @param string $order
     * @param User|null $assessor
     * @return \verbb\supertable\services\ElementCriteriaModel
     */
    public function assessmentCriteria($limit = 25, $order = 'lastName', User $assessor)
    {
        $supertableService = new SuperTableService();
        $params = [
            'elementType'   => 'craft\\elements\\User',
            'criteria' => [
                'order' => $order,
                'limit' => $limit,
                'reviewStepType' => 'assessment'
            ],
            'relatedTo'     => [
                'targetElement' => $assessor->id,
                'field'         => 'packageReviews.reviewUser'
            ]];
        return $supertableService->getRelatedElementsQuery($params);
    }

    /**
     * @param int $limit
     * @param string $order
     * @param User $reviewer
     * @return \verbb\supertable\services\ElementCriteriaModel
     */
    public function reviewCriteria($limit = 25, $order = 'lastName', User $reviewer)
    {
        $supertableService = new SuperTableService();
        $params = [
            'elementType'   => 'craft\\elements\\User',
            'criteria'      => [
                'order' => $order,
                'limit' => $limit,
                'reviewStepType' => 'review'
            ],
            'relatedTo'     => [
                'targetElement' => $reviewer->id,
                'field'         => 'packageReviews.reviewUser'
            ]];
        return $supertableService->getRelatedElementsQuery($params);
    }
}