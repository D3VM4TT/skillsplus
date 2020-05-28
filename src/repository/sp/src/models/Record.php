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
        $userRoles = $this->user->userRole->all();
        if ($userRoles) {
            foreach ($userRoles as $jobRoleCategory) {
                $this->_resetData();
                $jobRoleModuleEntries = Lantra::$app->records->getJobRoleModules($jobRoleCategory);
                $moduleGroupCategories = Lantra::$app->records->getModuleGroups($jobRoleModuleEntries);
                $moduleGroups = [];
                foreach($moduleGroupCategories->all() as $moduleGroup) {
                    $items = $this->_getModuleGroupModuleItems($moduleGroup, $jobRoleModuleEntries);
                    $moduleGroups[$moduleGroup->id] = $this->addItem('moduleGroup', $moduleGroup, $items);
                }
                $this->jobRoles[$jobRoleCategory->id] = $this->addItem('jobRole', $jobRoleCategory, $moduleGroups, $this->_data);
            }
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
        return new RecordItem([
            'record' => $this,
            'itemType' => $itemType,
            'elementId' => $element->id,
            'items' => $items,
            'data' => $data
        ]);
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
     * @return bool
     */
    public function getElement($elementId)
    {
        return $this->hasElement($elementId) ? $this->_elements[$elementId] : null;
    }

    /**
     * @param $moduleGroup
     * @param $jobRoleModuleEntries
     * @return array
     */
    private function _getModuleGroupModuleItems(Category $moduleGroup, ElementQueryInterface $jobRoleModuleEntries)
    {
        $return = [];
        foreach ($jobRoleModuleEntries as $moduleEntry) {
            if (in_array($moduleGroup->id, $moduleEntry->moduleGroup->ids())) {
                $items = $this->_getModuleUnitGroupItems($moduleEntry);
                $return[$moduleEntry->id] = $this->addItem('module', $moduleEntry, $items);
                $this->_data['moduleIds'][] = $moduleEntry->id;
            }
        }
        return $return;
    }

    /**
     * @param $moduleEntry
     * @return array
     */
    private function _getModuleUnitGroupItems(Entry $moduleEntry)
    {
        $return = [];
        foreach ($moduleEntry->moduleUnitGroups->all() as $unitGroupBlock) {
            $items = $this->_getUnitGroupUnitItems($unitGroupBlock);
            $return[$unitGroupBlock->id] = $this->addItem('unitGroup', $unitGroupBlock, $items);
        }
        return $return;
    }

    /**
     * @param $unitGroupBlock
     * @return array
     */
    private function _getUnitGroupUnitItems(MatrixBlock $unitGroupBlock)
    {
        $return = [];
        foreach ($unitGroupBlock->unitEntries->all() as $unitEntry) {
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