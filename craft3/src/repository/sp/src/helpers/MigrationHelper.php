<?php
/**
 * Lantra Skills Plus for Craft CMS 3.x
 *
 * @link      https://coffeebean.design
 * @copyright Copyright (c) 2020 Coffee Bean Design
 */

namespace lantra\sp\helpers;

use Craft;
use craft\behaviors\FieldLayoutBehavior;

use lantra\sp\Plugin as Lantra;

class MigrationHelper
{
    /**
     * @param FieldLayoutBehavior $object
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public static function getFieldLayoutArray($object)
    {
        $tabs = $object->getFieldLayout()->getTabs();
        $fieldLayout = [];
        foreach($tabs as $tab) {
            $fieldIds = [];
            foreach($tab->getFields() as $field) {
                $fieldIds[] = $field->id;
            }
            $fieldLayout[$tab->name] = $fieldIds;
        }
        return $fieldLayout;
    }


    /**
     * @param $type
     * @param $name
     * @param $handle
     * @param null $groupId
     * @param array $settings
     * @param string $instructions
     * @param string $context
     * @return \craft\base\FieldInterface|null
     * @throws \Throwable
     */
    public static function createField($type, $name, $handle, $groupId = null, $settings = [], $instructions = '', $context = 'global')
    {
        if (false != $field = self::getFieldByHandle($handle)) {
            return $field;
        }

        $fieldsService = Craft::$app->getFields();
        $field = $fieldsService->createField([
            'type' => $type,
            'groupId' => $groupId,
            'name' => $name,
            'handle' => $handle,
            'instructions' => $instructions,
            'context' => $context,
            'translationMethod' => 'none',
            'translationKeyFormat' => NULL,
            'settings' => $settings
        ]);

        $fieldsService->saveField($field);
        return $field;
    }

    /**
     * Get field by handle (regardless of context)
     *
     * @param $handle
     * @return bool|\craft\base\FieldInterface
     */
    public static function getFieldByHandle($handle)
    {
        $fieldsService = Craft::$app->getFields();
        $allFields = $fieldsService->getAllFields(false);
        foreach($allFields as $field) {
            if ($field->handle == $handle) {
                return $field;
            }
        }
        return false;
    }


    /**
     * @param $handle
     * @return \craft\models\EntryType|null
     */
    public static function getEntryTypeByHandle($handle)
    {
        foreach (Craft::$app->getSections()->getEntryTypesByHandle($handle) as $entryType) {
            if ($entryType->handle == $handle) {
                return $entryType;
            }
        }
        return null;
    }
}