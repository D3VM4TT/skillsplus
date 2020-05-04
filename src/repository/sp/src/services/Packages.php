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

use lantra\sp\Plugin as Lantra;
use verbb\supertable\elements\SuperTableBlockElement;

class Packages extends Component
{
    /**
     * @param ModelEvent $event
     * @param SuperTableBlockElement $packageBlock
     */
    public function onBeforeSavePackage(ModelEvent $event, SuperTableBlockElement $packageBlock)
    {
        $assessorId = $packageBlock->packageAssessor->count() ? $packageBlock->packageAssessor->one()->id : null;
        $reviewerId = $packageBlock->packageReviewer->count() ? $packageBlock->packageReviewer->one()->id : null;

        if ($assessorId && $reviewerId && $assessorId == $reviewerId) {
            $packageBlock->addError('packageAssessor', 'Assessor and Reviewer can not be the same.');
            $event->isValid = false;
        }
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
    public function onSavePackage($package, $changed)
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
     * @param $package
     * @param $message
     * @return bool
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     */
    public function log($package, $message, $admin)
    {
        $user = Craft::$app->getUser()->getIdentity();
        $new = [
            'col1'       => time(),
            'col2'       => $message,
            'col3'       => $admin,
            'col4'       => $user->fullName,
            'col5'       => $user->id
        ];
        $packageLog = $package->packageLog;
        $packageLog['new1'] = $new;
        $package->setFieldValue('packageLog', $packageLog);
        return Craft::$app->elements->saveElement($package);
    }

    /**
     * @param $packageId
     * @param $status
     * @return bool
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     */
    public function setStatus($packageId, $status)
    {
        if (null == $package = SuperTableBlockElement::findOne($packageId)) {
            return false;
        }
        $package->setFieldValue('packageStatus', $status);
        return Craft::$app->elements->saveElement($package);
    }

    /**
     * @param $package
     * @param bool $complete
     * @return mixed
     */
    public function countPackageUnits($package, $complete = false)
    {
        $user = Craft::$app->users->getUserById($package->ownerId);
        $entries = $this->getPackageModuleEntries($package);
        if ($complete) {
            return Lantra::$app->modules->unitsComplete($entries, $user);
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
        $user = Craft::$app->users->getUserById($package->ownerId);
        $units = $this->getPackageUnits($package);
        foreach ($units as $unit) {
            $result = Lantra::$app->results->getUnitResult($user->id, $unit->id);
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
                'level'    => $optionalModuleBlock->level
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
        if (is_int($user) || is_string($user)) {
            $user = Craft::$app->users->getUserById($user);
        }
        if (is_object($user)) {
            foreach ($user->userPackages as $package) {
                if ($package->id == $packageId) {
                    return $package;
                }
            }
        }
        return null;
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
}