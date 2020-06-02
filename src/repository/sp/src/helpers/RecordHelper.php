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
     * @return int
     */
    public static function totalComplete(RecordItem $recordItem)
    {
        return Lantra::$app->results->countUnitResults($recordItem->record->user->id, $recordItem->unitIds());
    }
}