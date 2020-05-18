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

    private $_elements;

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
                $jobRoleModuleEntries = Lantra::$app->records->getJobRoleModules($jobRoleCategory);
                $moduleGroupCategories = Lantra::$app->records->getModuleGroups($jobRoleModuleEntries);
                $moduleGroups = [];

                foreach($moduleGroupCategories->all() as $moduleGroup) {
                    $items = $this->_getModuleGroupModuleItems($moduleGroup, $jobRoleModuleEntries);
                    $moduleGroups[$moduleGroup->id] = [
                        'item' => $this->addItem('moduleGroup', $moduleGroup, null, $items),
                        'modules' => $items
                     ];
                }
                $jobRole = [
                    'item' => $this->addItem('jobRole', $jobRoleCategory, null, $moduleGroups),
                    'moduleGroups' => $moduleGroups
                ];
                $this->jobRoles[$jobRoleCategory->id] = $jobRole;
            }
        }
    }

    /**
     * @param $type
     * @param $element
     * @param $result
     * @param $items
     * @return RecordItem
     */
    public function addItem($type, Element $element, Element $result = null, $items = [])
    {
        $this->_elements[$element->id] = $element;
        return new RecordItem([
            'record' => $this,
            'type' => $type,
            'elementId' => $element->id,
            'resultId' => $result ? $result->id : null,
            'items' => $items
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
                $module = [
                    'item' => $this->addItem('module', $moduleEntry, null, $items),
                    'result' => $this->_getResult('module', $moduleEntry),
                    'unitGroups' => $items
                ];
                $return[$moduleEntry->id] = $module;
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
            $unitGroup = [
                'item' => $this->addItem('unitGroup', $unitGroupBlock,null, $items),
                'units' => $items
            ];
            $return[$unitGroupBlock->id] = $unitGroup;
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
            $unit = [
                'item' => $this->addItem('unit', $unitEntry),
                'result' => $this->_getResult($unitEntry),
            ];
            $return[$unitEntry->id] = $unit;
        }
        return $return;
    }

    /**
     * @param $type
     * @param $entry
     */
    private function _getResult($type = 'unit', Entry $entry = null)
    {

    }

    public function getData()
    {
        $data = [
            'jobRoles' => []
        ];
        foreach ($this->jobRoles as $id => $jobRole) {
            $data['jobRoles'][$id] = $jobRole['item']->getData();
        }
        return $data;
    }

    public function __toString()
    {
        return \GuzzleHttp\json_encode($this->getData());
    }
}