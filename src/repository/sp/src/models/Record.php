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
use craft\elements\Entry;
use craft\elements\Category;
use craft\elements\MatrixBlock;
use craft\elements\User;
use craft\elements\db\ElementQueryInterface;
use craft\helpers\Json;

use lantra\sp\Plugin as Lantra;

class Record extends Model
{
    public $user;
    private $_record = [
        'jobRoles'  => [],
        'packages'  => []
    ];
    private $_elements = [];
    private $_elementIds = [];
    private $_results = [];
    private $_items = [];
    private $_data = [];

    /**
     * Record constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        parent::__construct(['user' => $user]);
        $this->_setRecordFromDb();
        $this->_setUnitResults();
    }

    /**
     * @param string $name
     * @param array $params
     * @return mixed|null
     */
    public function __call($name, $params)
    {
        return isset($this->_record[$name]) ? $this->_record[$name] : null;
    }

    /**
     * @return string
     */
    public function toJson()
    {
        return Json::encode($this->getData());
    }

    /**
     * @return int
     */
    public function totalJobRoles()
    {
        return count($this->_record['jobRoles']);
    }

    /**
     * @return int
     */
    public function totalPackages()
    {
        return count($this->_record['packages']);
    }

    /**
     * @return mixed|null
     */
    public function getFirstPackage()
    {
        if (!count($this->_record['packages'])) {
            return null;
        }
        foreach($this->_record['packages'] as $package) {
            return $package;
        }
    }

    /**
     * @return array
     */
    public function getData()
    {
        $array = [];
        foreach($this->_record as $recordType => $items) {
            foreach($items as $elementId => $item) {
                $array['record'][$recordType][$elementId] = $item->getData();
            }
        }
        $array['elementIds'] = $this->_elementIds;
        return $array;
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
     * @return \craft\base\ElementInterface|mixed|null
     */
    public function getElement($elementId)
    {
        return $this->hasElement($elementId) ? $this->_elements[$elementId] : null;
    }


    /**
     * @param $elementId
     * @return bool
     */
    public function hasUnitResult($elementId)
    {
        return isset($this->_results[$elementId]);
    }

    /**
     * @param $elementId
     * @return bool
     */
    public function getUnitResult($elementId)
    {
        return $this->hasUnitResult($elementId) ? $this->_results[$elementId] : null;
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
     * @return mixed|null
     */
    public function getUnitIds()
    {
        return $this->_getData('unitIds');
    }

    /**
     * @return mixed|null
     */
    public function getModuleIds()
    {
        return $this->_getData('moduleIds');
    }

    /**
     *
     */
    private function _setRecordFromDb()
    {
        ## set job role cpd
        $userRoles = $this->user->userRole->all();
        if ($userRoles) {
            foreach ($userRoles as $jobRoleCategory) {
                ## $this->_resetData();
                $relatedModules = Lantra::$app->records->getRelatedModules($jobRoleCategory);
                $moduleGroupCategories = Lantra::$app->records->getModuleGroups($relatedModules);
                $moduleGroups = [];
                foreach ($moduleGroupCategories->all() as $moduleGroup) {
                    ## skip if user company does not match this module group
                    if ($moduleGroup->isCompany() && !$moduleGroup->isUserCompany($this->user->id)) {
                        continue;
                    }
                    $items = $this->_getModuleGroupModuleItems($moduleGroup, $relatedModules);
                    $moduleGroups[$moduleGroup->id] = $this->_addItem('moduleGroup', $moduleGroup, $items);
                }
                $this->_record['jobRoles'][$jobRoleCategory->id] = $this->_addItem('jobRole', $jobRoleCategory, $moduleGroups, $this->_data);
            }
        }
        ## set user packages
        $packages = Lantra::$app->packages->getUserPackages($this->user);
        foreach ($packages as $package) {
            ## $this->_resetData();
            $moduleGroups = [];
            foreach ($package->moduleGroupCategories() as $moduleGroup) {
                $relatedModules = Lantra::$app->records->getRelatedModules($moduleGroup);
                $items = $this->_getModuleGroupModuleItems($moduleGroup, $relatedModules);
                $moduleGroups[$moduleGroup->id] = $this->_addItem('moduleGroup', $moduleGroup, $items);
            }
            $this->_record['packages'][$package->id] = $this->_addItem('package', $package, $moduleGroups, $this->_data);
        }
        ## $this->_setCache();
    }

    /**
     * @param $itemType
     * @param $element
     * @param $items
     * @param $data
     * @return RecordItem
     */
    private function _addItem($itemType, Element $element, $items = [], $data = [])
    {
        $this->_elements[$element->id] = $element;
        $item = new RecordItem([
            'itemType' => $itemType,
            'elementId' => $element->id,
            'items' => $items,
            'data' => $data
        ]);
        $this->_items[$element->id] = $item;
        if ($item->itemType == 'moduleGroup' || $itemType == 'jobRole') {
            $this->_addElementId('Category', $element->id);
        }
        elseif ($item->itemType == 'package' || $item->itemType == 'module' || $item->itemType == 'unit') {
            $this->_addElementId('Entry', $element->id);
        }
        elseif ($item->itemType == 'unitGroup') {
            $this->_addElementId('MatrixBlock', $element->id);
        }
        return $item;
    }

    /**
     * @param $elementType
     * @param $id
     */
    private function _addElementId($elementType, $id)
    {
        $this->_elementIds[$elementType][] = $id;
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
            ## add optional module check
            if (!Lantra::$app->modules->isUserModule($moduleEntry, $this->user)) {
                continue;
            }
            if (in_array($moduleGroup->id, $moduleEntry->moduleGroup->ids())) {
                $items = $this->_getModuleUnitGroupItems($moduleEntry->moduleUnitGroups);
                $return[$moduleEntry->id] = $this->_addItem('module', $moduleEntry, $items);
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
            $return[$unitGroupBlock->id] = $this->_addItem('unitGroup', $unitGroupBlock, $items);
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
            $return[$unitEntry->id] = $this->_addItem('unit', $unitEntry);
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

    private function _getData($key)
    {
        return isset($this->_data[$key]) && count($this->_data[$key]) ? $this->_data[$key] : null;
    }

    /**
     *
     */
    private function _setUnitResults()
    {
        if (null == $unitIds = $this->getUnitIds()) {
            return;
        }
        $results = Lantra::$app->results->getAllUnitResults($this->user->id, $unitIds)->all();
        foreach($results as $result) {
            $resultUnitId = $result->resultUnit->one()->id;
            $this->_results[$resultUnitId] = $result;
        }
    }

    /**
     *
     */
    private function _setCache()
    {
        Craft::$app->cache->set('record' . $this->user->id, $this->toJson());
    }

    /**
     *
     */
    private function _setRecordFromCache()
    {
        if (null == $cache = Craft::$app->cache->get('record' . $this->user->id)) {
            return;
        }

        $data = Json::decode($cache);

        foreach($data['elementIds'] as $type => $ids) {
            $class = "craft\\elements\\$type";
            $criteria = $class::find();
            $criteria->id = $ids;
            foreach($criteria->all() as $element) {
                $this->_elements[$element->id] = $element;
            }
        }
        foreach($data['record'] as $type => $items) {
            foreach($items as $id => $item) {
                $this->_record[$type][$id] = $this->_addItemFromCache($item);
            }
        }
    }

    /**
     * @param $item
     * @return RecordItem
     */
    private function _addItemFromCache($item)
    {
        $items = [];
        if (count($item['items'])) {
            foreach ($item['items'] as $i) {
                $items = array_merge($items, [$this->_addItemFromCache($i)]);
            }
        }
        return $this->addItem($item['itemType'], $this->getElement($item['elementId']), $items, $this->_data);
    }
}