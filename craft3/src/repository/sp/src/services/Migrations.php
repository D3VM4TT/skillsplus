<?php
/**
 * Lantra Skills Plus for Craft CMS 3.x
 *
 * @link      https://coffeebean.design
 * @copyright Copyright (c) 2020 Coffee Bean Design
 */

namespace lantra\sp\services;

use craft\base\Component;
use craft\records\Field;
use craft\records\FieldLayout;

class Migrations extends Component
{

    /**
     * @param $groupId
     * @param $name
     * @param $handle
     * @param $type
     * @param $settings
     * @return bool|Field
     */
    public function addField($groupId, $name, $handle, $type, $settings)
    {
        $field = new Field();
        $field->groupId = $groupId;
        $field->name = $name;
        $field->handle = $handle;
        $field->translatable = true;
        $field->type = $type;
        $field->settings = $settings;
        if ($field->save()) {
            return $field;
        }
        return false;
    }

    /**
     * @param $layoutId
     * @param $tabId
     * @param $fieldId
     * @param $required
     * @param $sortOrder
     * @return int
     */
    public function addLayoutField($layoutId, $tabId, $fieldId, $required, $sortOrder)
    {
        $fieldRecord = new FieldLayout();
        $fieldRecord->layoutId  = $layoutId;
        $fieldRecord->tabId     = $tabId;
        $fieldRecord->fieldId   = $fieldId;
        $fieldRecord->required  = $required;
        $fieldRecord->sortOrder = $sortOrder;
        $fieldRecord->save(false);
        return $fieldRecord->id;
    }
}