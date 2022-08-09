<?php
/**
 * Lantra Skills Plus for Craft CMS 3.x
 *
 * @link      https://coffeebean.design
 * @copyright Copyright (c) 2020 Coffee Bean Design
 */

namespace lantra\sp\helpers;

use lantra\sp\Plugin as Lantra;
use lantra\sp\models\RecordItem;
use craft\elements\Entry;
use craft\elements\User;

class RecordHelper
{
    /**
     * @param RecordItem $recordItem
     * @return int
     */
    public static function totalUnits(RecordItem $recordItem)
    {
        $total = 0;
        if ($recordItem->itemType == 'unitGroup') {
            $total = count($recordItem->items);
        }
        elseif ($recordItem->itemType == 'module') {
            foreach ($recordItem->items as $unitGroup) {
                $total += count($unitGroup->items);
            }
        }
        elseif ($recordItem->itemType == 'moduleGroup') {
            foreach($recordItem->items as $module) {
                $total += self::totalUnits($module);
            }
        }
        elseif ($recordItem->itemType == 'jobRole' || $recordItem->itemType == 'package') {
            foreach($recordItem->items as $moduleGroup) {
                $total += self::totalUnits($moduleGroup);
            }
        }
        return $total;
    }

    /**
     * @param RecordItem $recordItem
     * @return int
     */
    public static function totalModules(RecordItem $recordItem)
    {
        $total = 0;
        if ($recordItem->itemType == 'moduleGroup') {
            $total = $recordItem->totalModules();
        }
        elseif ($recordItem->itemType == 'jobRole' || $recordItem->itemType == 'package') {
            foreach($recordItem->items as $moduleGroup) {
                $total += self::totalModules($moduleGroup);
            }
        }
        return $total;
    }

    /**
     * @param RecordItem $recordItem
     * @param User $user
     * @param Entry|null $taskbook
     * @return int
     */
    public static function totalComplete(RecordItem $recordItem, User $user, Entry $taskbook = null, $packageId = null, $moduleGroupId = null)
    {
        ## taskbook unitEndorse requires individual units to be endorsed
        $status = $taskbook && $taskbook->unitEndorse ? ['endorsed'] : ['not', 'draft'];
        $unitIds = $recordItem->unitIds();
        $unitResults = Lantra::$app->results->countUnitResults($user->id, $unitIds, $status, $packageId, $moduleGroupId);
        $totalUnits = count($unitIds);
        ## hack to fix duplicated unit results
        return $unitResults > $totalUnits ? $totalUnits : $unitResults;
    }

    /**
     * @param RecordItem $recordItem
     * @param User $user
     * @return null
     */
    public static function totalEndorsed(RecordItem $recordItem, User $user, $packageId = null, $moduleGroupId = null)
    {
        return Lantra::$app->results->countUnitResults($user->id, $recordItem->unitIds(), ['endorsed'], $packageId, $moduleGroupId);
    }

    /**
     * @param RecordItem $recordItem
     * @param User $user
     * @return null
     */
    public static function totalPending(RecordItem $recordItem, User $user, $packageId = null, $moduleGroupId = null)
    {
        return Lantra::$app->results->countUnitResults($user->id, $recordItem->unitIds(), ['pending'], $packageId, $moduleGroupId);
    }

    /**
     * @param RecordItem $recordItem
     * @param User $user
     * @param Entry|null $taskbook
     * @return bool
     */
    public static function isComplete(RecordItem $recordItem, User $user, Entry $taskbook = null)
    {
        return self::totalComplete($recordItem, $user, $taskbook) == self::totalUnits($recordItem);
    }

    /**
     * @param RecordItem $recordItem
     * @return int|string
     */
    public static function hasAssessmentUnit(RecordItem $recordItem)
    {
        $unitIds = $recordItem->unitIds();
        if (!count($unitIds)) {
            return false;
        }
        $criteria = Entry::find();
        $criteria->section = 'units';
        $criteria->where(['in', 'entries.id', $unitIds]);
        $criteria->andWhere(['field_unitType' => 'elearning']);
        return $criteria->count();
    }
}