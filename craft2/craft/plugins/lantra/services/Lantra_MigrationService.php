<?php
namespace Craft;

class Lantra_MigrationService extends BaseApplicationComponent
{
    public function addField($groupId, $name, $handle, $type, $settings)
    {
        $field = new FieldModel();
        $field->groupId = $groupId;
        $field->name = $name;
        $field->handle = $handle;
        $field->translatable = true;
        $field->type = $type;
        $field->settings = $settings;
        if (craft()->fields->saveField($field)) {
            return $field;
        }
        return false;
    }

    public function addLayoutField($layoutId, $tabId, $fieldId, $required, $sortOrder)
    {
        $fieldRecord = new FieldLayoutFieldRecord();
        $fieldRecord->layoutId  = $layoutId;
        $fieldRecord->tabId     = $tabId;
        $fieldRecord->fieldId   = $fieldId;
        $fieldRecord->required  = $required;
        $fieldRecord->sortOrder = $sortOrder;
        $fieldRecord->save(false);
        return $fieldRecord->id;
    }
}