<?php
/**
 * Lantra Skills Plus for Craft CMS 3.x
 *
 * @link      https://coffeebean.design
 * @copyright Copyright (c) 2020 Coffee Bean Design
 */

namespace lantra\sp\helpers;

use craft\elements\Entry;
use lantra\sp\Plugin as Lantra;
use lantra\sp\models\Cycle;

class CycleHelper
{
    /**
     * @param $moduleEntry
     * @return Cycle|void
     */
    static function getModuleCycle($moduleEntry)
    {
        if ($moduleEntry->type != 'cpd') {
            return;
        }
        $cycle = new Cycle();
        return $cycle->setModule($moduleEntry)->setCycles();
    }

    /**
     * @param $moduleEntry
     * @return array
     */
    static function getModuleCycles($moduleEntry)
    {
        if ($moduleEntry->type != 'cpd') {
            return;
        }
        $cycle = new Cycle();
        return $cycle->setModule($moduleEntry)->setCycles()->getCycles();
    }

    /**
     * @param $moduleEntry
     * @return \lantra\sp\models\CyclePeriod|void
     */
    static function getModuleCurrentCycle($moduleEntry)
    {
        if ($moduleEntry->type != 'cpd') {
            return;
        }
        $cycle = new Cycle();
        return $cycle->setModule($moduleEntry)->setCycles()->getCurrent();
    }

    /**
     * @param $resultEntry
     * @param $current
     * @return \lantra\sp\models\CyclePeriod
     */
    static function getResultCycle($resultEntry, $current = false)
    {
        $cycle = new Cycle($resultEntry);
        return $current ? $cycle->getCurrent() : $cycle->getCycle();
    }

    /**
     * @param $resultEntry
     * @param string $code
     * @param string $type
     * @return Cycle|null
     */
    static function getUnitResultRecurringCycle($resultEntry, $code = '', $type = 'monthly')
    {
        $cycle = new Cycle($resultEntry);
        $recurring = $cycle->getCycle()->getRecurring($type);
        foreach($recurring as $cyclePeriod) {
            if($code && $cyclePeriod->code == $code) {
                return $cyclePeriod;
            }
        }
        return null;
    }

    /**
     * @return mixed
     */
    static function getCpdModules()
    {
        $criteria = Entry::find();
        $criteria->section = 'modules';
        $criteria->type = 'cpd';
        $criteria->limit = null;
        return $criteria->all();
    }

}