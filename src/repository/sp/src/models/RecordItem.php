<?php
/**
 * Lantra Skills Plus for Craft CMS 3.x
 *
 * @link      https://coffeebean.design
 * @copyright Copyright (c) 2020 Coffee Bean Design
 */

namespace lantra\sp\models;

use craft\base\Model;
use craft\helpers\Json;
use lantra\sp\helpers\LantraHelper;

class RecordItem extends Model
{
    public $itemType;
    public $elementId;
    public $items = [];
    public $data = [];
    public $isHidden = false;

    /**
     * RecordItem constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);
    }

    /**
     * @return array
     */
    public function allUnits()
    {
        $units = [];
        if ($this->itemType == 'unitGroup') {
            $units = $this->items;
        }
        elseif ($this->itemType == 'module') {
            foreach ($this->items as $unitGroup) {
                $units = array_merge($units, $unitGroup->allUnits());
            }
        }
        elseif ($this->itemType == 'moduleGroup') {
            foreach($this->items as $module) {
                $units = array_merge($units, $module->allUnits());
            }
        }
        elseif ($this->itemType == 'jobRole' || $this->itemType == 'package') {
            foreach($this->items as $moduleGroup) {
                $units = array_merge($units, $moduleGroup->allUnits());
            }
        }
        return $units;
    }

    /**
     * @return int
     */
    public function totalModules()
    {
        return isset($this->data['moduleIds']) ? count($this->data['moduleIds']) : 0;
    }

    /**
     * @return int
     */
    public function totalUnits()
    {
        return isset($this->data['unitIds']) ? count($this->data['unitIds']) : 0;
    }

    /**
     * @return array
     */
    public function moduleIds()
    {
        return isset($this->data['moduleIds']) ? $this->data['moduleIds'] : [];
    }

    /**
     * @return array
     */
    public function unitIds()
    {
        if ($this->itemType == 'unitGroup') {
            return array_keys($this->items);
        }
        if ($this->itemType == 'moduleGroup') {
            $ids = [];
            foreach ($this->items as $moduleItem) {
                foreach ($moduleItem->items as $unitGroupItem) {
                    $ids = array_merge($ids, array_keys($unitGroupItem->items));
                }
            }
            return $ids;
        }
        return isset($this->data['unitIds']) ? $this->data['unitIds'] : [];
    }

    /**
     * @return array
     */
    public function getData()
    {
        return [
            'itemType' => $this->itemType,
            'elementId' => $this->elementId,
            'items' => $this->getItems(),
            'data' => $this->data
        ];
    }

    /**
     * @return array
     */
    public function getItems()
    {
        $data = [];
        foreach ($this->items as $item) {
            $data[] = $item->getData();
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
     * @param string $name
     * @param array $params
     * @return mixed
     */
    public function __call($name, $params)
    {
        if (in_array($name, ['moduleGroups', 'modules', 'unitGroups', 'units'])) {
            return $this->items;
        }
        ## return total items
        if (in_array($name, ['moduleIds', 'unitIds']) && isset($this->data[$name])) {
            return $this->data[$name];
        }
        return null;
    }
}