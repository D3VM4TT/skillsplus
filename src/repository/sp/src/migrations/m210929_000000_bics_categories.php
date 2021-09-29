<?php

namespace lantra\sp\migrations;

use Craft;
use craft\db\Migration;
use craft\elements\Entry;


use verbb\supertable\elements\SuperTableBlockElement;
use verbb\supertable\services\SuperTableService;


/**
 * m210721_134639_convertTaskbooks migration.
 */
class m210929_000000_bics_categories extends Migration
{
    /**
     * @return bool|void
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     */
    public function safeUp()
    {
        $field = Craft::$app->fields->getFieldByHandle('packageModuleGroups');

        ## update packages to remove BICS
        $criteria = Entry::find();
        $criteria->section = 'packages';
        $packages = $criteria->all();

        foreach ($packages as $package) {
            $taskbook = $package->taskbook;
            if ($taskbook && $taskbook->id == 371282)
            echo "Converting " . $package->title . '...' ;
            foreach ($package->packageModuleGroups as $row) {
                Craft::$app->elements->deleteElement($row);
            }
            $this->addBlock($field, $package, 307057, 1, $package->packageLevel);
            $this->addBlock($field, $package,307104, 0, $package->packageLevel);
            $this->addBlock($field, $package, 307103, 0, $package->packageLevel);
            $this->addBlock($field, $package, 307105, 0, $package->packageLevel);
            echo "[done]\n\n";
        }
    }

    /**
     * @param $field
     * @param $owner
     * @param $categoryId
     * @param int $mandatory
     * @param int $level
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     */
    public function addBlock ($field, $owner, $categoryId, $mandatory = 0, $level = 1) {

        $sp = new SuperTableService();
        $stepBlockType = $sp->getBlockTypesByFieldId($field->id)[0];
        $block = new SuperTableBlockElement();
        $block->fieldId = $field->id;
        $block->ownerId = $owner->id;
        $block->typeId = $stepBlockType->id;
        $block->setFieldValues([
            'moduleGroup' => [$categoryId],
            'moduleGroupLevel' => $level,
            'moduleGroupMandatory' => $mandatory
        ]);
        Craft::$app->elements->saveElement($block);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m210929_000000_bics_categories cannot be reverted.\n";
        return false;
    }
}
