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
     * @param $moduleResult
     */
    public function checkTaskbookComplete($moduleResult)
    {
        ## run through user packages and if module exists
    }
}