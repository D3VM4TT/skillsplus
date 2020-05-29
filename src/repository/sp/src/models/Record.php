<?php
/**
 * Lantra Skills Plus for Craft CMS 3.x
 *
 * @link      https://coffeebean.design
 * @copyright Copyright (c) 2020 Coffee Bean Design
 */

namespace lantra\sp\models;

use Craft;
use craft\base\Element;
use craft\base\Model;
use craft\elements\Category;
use craft\elements\Entry;
use craft\elements\User;
use craft\elements\MatrixBlock;
use craft\elements\db\ElementQueryInterface;

use lantra\sp\Plugin as Lantra;

class Record extends Model
{
    public $user;
    public $jobRoles = [];
    public $packages = [];

    private $_elements = [];
    private $_items = [];
    private $_data = [];

    /**
     * Record constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        parent::__construct(['user' => $user]);
        $this->setRecord();
    }

    /**
     *
     */
    public function setRecord()
    {
        ## set job role cpd
        $userRoles = $this->user->userRole->all();
        if ($userRoles) {
            foreach ($userRoles as $jobRoleCategory) {
                $this->_resetData();
                $relatedModules = Lantra::$app->records->getRelatedModules($jobRoleCategory);
                $moduleGroupCategories = Lantra::$app->records->getModuleGroups($relatedModules);
                $moduleGroups = [];
                foreach($moduleGroupCategories->all() as $moduleGroup) {
                    $items = $this->_getModuleGroupModuleItems($moduleGroup, $relatedModules);
                    $moduleGroups[$moduleGroup->id] = $this->addItem('moduleGroup', $moduleGroup, $items);
                }
                $this->jobRoles[$jobRoleCategory->id] = $this->addItem('jobRole', $jobRoleCategory, $moduleGroups, $this->_data);
            }
        }
        ## set user packages
        $packages = Lantra::$app->packages->getUserPackages($this->user);
        foreach ($packages as $package) {
            $this->_resetData();
            $moduleGroups = [];
            foreach($package->moduleGroupCategories() as $moduleGroup) {
                $relatedModules = Lantra::$app->records->getRelatedModules($moduleGroup);
                $items = $this->_getModuleGroupModuleItems($moduleGroup, $relatedModules);
                $moduleGroups[$moduleGroup->id] = $this->addItem('moduleGroup', $moduleGroup, $items);
            }
            $this->packages[$package->id] = $this->addItem('package', $package, $moduleGroups, $this->_data);
        }
    }

    /**
     * @param $itemType
     * @param $element
     * @param $items
     * @param $data
     * @return RecordItem
     */
    public function addItem($itemType, Element $element, $items = [], $data = [])
    {
        $this->_elements[$element->id] = $element;
        $item = new RecordItem([
            'record' => $this,
            'itemType' => $itemType,
            'elementId' => $element->id,
            'items' => $items,
            'data' => $data
        ]);
        $this->_items[$element->id] = $item;
        return $item;
    }

    /**
     * @param $elementId
     * @return bool
     */
    public function hasElement($elementId)
    {
        return isset($this->_elements[$elementId]);
    }

    /**
     * @param $elementId
     * @return Element|null
     */
    public function getElement($elementId)
    {
        return $this->hasElement($elementId) ? $this->_elements[$elementId] : null;
    }

    /**
     * @param $elementId
     * @return RecordItem|null
     */
    public function getItem($elementId)
    {
        return isset($this->_items[$elementId]) ? $this->_items[$elementId] : null;
    }

    /**
     * @param $moduleGroup
     * @param $relatedEntries
     * @return array
     */
    private function _getModuleGroupModuleItems(Category $moduleGroup, ElementQueryInterface $relatedEntries)
    {
        $return = [];
        foreach ($relatedEntries->with(['moduleUnitGroups.unitGroup:unitEntries'])->all() as $moduleEntry) {
            if (in_array($moduleGroup->id, $moduleEntry->moduleGroup->ids())) {
                $items = $this->_getModuleUnitGroupItems($moduleEntry->moduleUnitGroups);
                $return[$moduleEntry->id] = $this->addItem('module', $moduleEntry, $items);
                $this->_data['moduleIds'][] = $moduleEntry->id;
            }
        }
        return $return;
    }

    /**
     * @param $moduleUnitGroups
     * @return array
     */
    private function _getModuleUnitGroupItems($moduleUnitGroups)
    {
        $return = [];
        foreach ($moduleUnitGroups as $unitGroupBlock) {
            $items = $this->_getUnitGroupUnitItems($unitGroupBlock->unitEntries);
            $return[$unitGroupBlock->id] = $this->addItem('unitGroup', $unitGroupBlock, $items);
        }
        return $return;
    }

    /**
     * @param $unitEntries
     * @return array
     */
    private function _getUnitGroupUnitItems($unitEntries)
    {
        $return = [];
        foreach ($unitEntries as $unitEntry) {
            $return[$unitEntry->id] = $this->addItem('unit', $unitEntry);
            $this->_data['unitIds'][] = $unitEntry->id;
        }
        return $return;
    }

    /**
     *
     */
    private function _resetData()
    {
        $this->_data = [
            'unitIds' => [],
            'moduleIds' => []
        ];
    }
}