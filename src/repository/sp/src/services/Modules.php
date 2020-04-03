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
use craft\elements\Entry;
use craft\events\ModelEvent;

use lantra\sp\Plugin as Lantra;

class Modules extends Component
{
    /**
     * @param ModelEvent $event
     * @param Entry $entry
     */
    public function onBeforeSaveModule(ModelEvent $event, Entry $entry)
    {
        ## make sure recurring units are in cpd type modules
        if ($entry->type != 'cpd' && $entry->moduleUnitGroups) {
            $unitGroups = $entry->moduleUnitGroups->all();
            foreach ($unitGroups as $unitGroup) {
                if (!$unitGroup->unitEntries) {
                    continue;
                }
                $unitEntries = $unitGroup->unitEntries->all();
                foreach ($unitEntries as $unitEntry) {
                    if ($unitEntry->unitRecurring) {
                        $event->isValid = false;
                        $entry->addError('moduleUnitGroups', $unitEntry->title . ' is a recurring unit and can only be added to a CPD type module.');
                    }
                }
            }
        }
        ## make sure there is only one recurring unit per module
        if ($entry->type == 'cpd' && $this->countRecurringUnits($entry) > 1) {
            $event->isValid = false;
            $entry->addError('moduleUnitGroups', 'You can only add one recurring unit per module.');
        }
    }

    /**
     * @param $moduleEntry
     * @return bool
     */
    private function countRecurringUnits($entry)
    {
        $total = 0;
        if ($entry->moduleUnitGroups) {
            $unitGroups = $entry->moduleUnitGroups->all();
            foreach ($unitGroups as $unitGroup) {
                if (!$unitGroup->unitEntries) {
                    continue;
                }
                $unitEntries = $unitGroup->unitEntries->all();
                foreach ($unitEntries as $unitEntry) {
                    if ($unitEntry->unitRecurring) {
                        $total++;
                    }
                }
            }
        }
        return $total;
    }
}