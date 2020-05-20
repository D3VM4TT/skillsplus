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
use craft\helpers\Json;
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
    private $_results = [
        'units'     => [],
        'modules'   => [],
        'other'     => []
    ];

    /**
     * Record constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        parent::__construct(['user' => $user]);
        $this->setResults();
        $this->setRecord();
    }

    /**
     *
     */
    public function setResults()
    {
        $criteria = Lantra::$app->results->getAllResults($this->user->id);
        $results = $criteria->all();
        foreach($results as $row) {
            if ($row->resultUnit) {
                $this->_results['unit'][$row->resultUnit->one()->id][] = $row;
            }
            elseif ($row->resultModule) {
                $this->_results['module'][$row->resultModule->one()->id][] = $row;
            }
            else {
                $this->_results['other'][] = $row;
            }
            $this->addItem('result', $row);
        }
    }

    /**
     * @param string $type
     * @param int $elementId
     * @return array
     */
    public function getResults($type = 'unit', $elementId = null)
    {
        if ($elementId && isset($this->_results[$type][$elementId])) {
            return $this->_results[$type][$elementId];
        }
        return [];
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
                    $moduleGroups[$moduleGroup->id] = $this->addItem('moduleGroup', $moduleGroup, $items);
                }
                $this->jobRoles[$jobRoleCategory->id] = $this->addItem('jobRole', $jobRoleCategory, $moduleGroups);
            }
        }
    }

    /**
     * @param $type
     * @param $element
     * @param $items
     * @return RecordItem
     */
    public function addItem($type, Element $element, $items = [])
    {
        $this->_elements[$element->id] = $element;
        return new RecordItem([
            'record' => $this,
            'type' => $type,
            'elementId' => $element->id,
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
     * @return array
     */
    public function getData()
    {
        $data = [
            'jobRoles' => []
        ];
        foreach ($this->jobRoles as $id => $jobRole) {
            $data['jobRoles'][$id] = $jobRole->getData();
        }
        return $data;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return Json::encode($this->getData());
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
        }
        return $return;
    }
}