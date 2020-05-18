<?php
/**
 * Lantra Skills Plus for Craft CMS 3.x
 *
 * @link      https://coffeebean.design
 * @copyright Copyright (c) 2020 Coffee Bean Design
 */

namespace lantra\sp\models;

use craft\base\Model;

class RecordItem extends Model
{
    public $record;
    public $type;
    public $elementId;
    public $resultId;
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
        return $this->type == 'module' || $this->type == 'unit' ? $this->record->hasElement($this->resultId): false;
    }


    public function getItems()
    {
        $data = [];
        foreach ($this->items as $item) {
            $data[] = $item['item']->getData();
        }
        return $data;
    }

    public function getData()
    {
        return [
            'type' => $this->type,
            'elementId' => $this->elementId,
            'resultId' => $this->resultId,
            'items' => $this->getItems()
        ];
    }

        /**
     * @return string
     */
    public function __toString()
    {
        return \GuzzleHttp\json_encode($this->getData());
    }
}