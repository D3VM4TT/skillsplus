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

class RecordItem extends Model
{
    public $record;
    public $type;
    public $elementId;
    public $results;
    public $items = [];

    /**
     * @return mixed
     */
    public function getElement()
    {
        return $this->record->getElement($this->elementId);
    }

    /**
     * @return mixed
     */
    public function hasResult()
    {
        $results = $this->getResults();
        return count($results) ? true : false;
    }

    /**
     * @return null
     */
    public function getResults()
    {
        if ($this->results === null) {
            $this->results = $this->record->getResults($this->type, $this->elementId);
        }
        return $this->results;
    }

    /**
     * @return null
     */
    public function getResult()
    {
        $results = $this->getResults();
        return count($results) ? $results[0] : null;
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
     * @return array
     */
    public function getData()
    {
        return [
            'type' => $this->type,
            'elementId' => $this->elementId,
            'results' => $this->getResults(),
            'items' => $this->getItems()
        ];
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
     * @return mixed
     */
    public function __get($name)
    {
        if ($name == 'result') {
            return $this->getResult();
        }
        $element = $this->getElement();
        return $element->$name;
    }

    /**
     * @param string $name
     * @param array $params
     * @return mixed
     */
    public function __call($name, $params)
    {
        $element = $this->getElement();
        if (isset($element->$name)) {
            return $element->$name;
        }
        # switch unit group names to title
        if ($name == 'title' && isset($element->groupName)) {
            return $element->groupName;
        }
        if (in_array($name, ['moduleGroups', 'modules', 'unitGroups', 'units'])) {
            return $this->items;
        }
        return $element->$name($params);
    }
}