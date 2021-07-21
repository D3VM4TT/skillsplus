<?php

namespace lantra\sp\migrations;

use Craft;
use craft\db\Migration;
use craft\elements\Category;
use craft\elements\Entry;

use lantra\sp\helpers\LantraHelper;
use verbb\supertable\elements\SuperTableBlockElement;
use verbb\supertable\services\SuperTableService;

/**
 * m210721_134639_convertTaskbooks migration.
 */
class m210721_134639_convert_taskbooks extends Migration
{
    /**
     * @return bool|void
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     */
    public function safeUp()
    {
        $sectionId = LantraHelper::sectionId('taskbooks');
        $typeId = LantraHelper::entryTypeId('taskbooks', 'taskbook');

        $field = Craft::$app->fields->getFieldByHandle('packageModuleGroups');
        $sp = new SuperTableService();

        $criteria = Category::find();
        $criteria->group = 'moduleGroups';
        $criteria->moduleGroupTaskbooks = true;
        $categories = $criteria->all();
        foreach ($categories as $category) {

            $entry = new Entry();
            $entry->sectionId = $sectionId;
            $entry->typeId = $typeId;
            $entry->enabled = true;
            $entry->title = $category->title;

            if (!Craft::$app->elements->saveElement($entry)) {
                echo "Could not create " . $category->title;
                return false;
            }

            if ($entry->id) {
                ## update content table
                $query = $this->db->createCommand();
                $query->delete('{{%content}}', ['elementId' => $entry->id])->execute();
                $query->update('{{%content}}', ['elementId' => $entry->id], ['elementId' => $category->id])->execute();


                ## update packages to point to new taskbooks
                $criteria = Entry::find();
                $criteria->section = 'packages';
                $criteria->relatedTo = ['targetElement' => $category->id, 'field' => 'packageModuleGroup'];
                $packages = $criteria->all();
                foreach ($packages as $package) {
                    $package->setFieldValue('packageTaskbook', [$entry->id]);
                    if (Craft::$app->elements->saveElement($package)) {

                        ## add previous module group to new module groups field
                        $stepBlockType = $sp->getBlockTypesByFieldId($field->id)[0];
                        $block = new SuperTableBlockElement();
                        $block->fieldId = $field->id;
                        $block->ownerId = $package->id;
                        $block->typeId = $stepBlockType->id;
                        $block->setFieldValues([
                            'moduleGroup' => [$category->id],
                            'moduleGroupLevel' => $package->packageLevel
                        ]);
                        Craft::$app->elements->saveElement($block);

                        echo "Updated package " . $package->id . "\n\n";
                    }
                }

                echo "Converted " . $category->title . "\n\n";
                Craft::$app->elements->deleteElementById($category->id);
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m210721_134639_convertTaskbooks cannot be reverted.\n";
        return false;
    }
}
