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

use lantra\sp\Plugin as Lantra;
use verbb\supertable\elements\SuperTableBlockElement;

class Packages extends Component
{
    /**
     * @param $package
     * @param $changed
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\SyntaxError
     */
    public function onSavePackage($package, $changed)
    {
        if ($changed['assessor']) {
            Lantra::$app->notify->sendPackageAssigned($package, 'assessor');
        }
        if ($changed['reviewer']) {
            Lantra::$app->notify->sendPackageAssigned($package, 'reviewer');
        }
        if ($changed['status']) {
            Lantra::$app->notify->sendPackageStatus($package);
            if ($package->packageStatus == 'reviewed') {
                Lantra::$app->notify->sendPackageReviewed($package);
            }
        }
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