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
    private $taskbookModuleGroups;
    private $packageModuleGroups;
    private $sp;

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

        $this->sp = new SuperTableService();
        $this->taskbookModuleGroups = Craft::$app->fields->getFieldByHandle('taskbookModuleGroups');
        $this->packageModuleGroups = Craft::$app->fields->getFieldByHandle('packageModuleGroups');

        $criteria = Category::find();
        $criteria->group = 'moduleGroups';
        $criteria->moduleGroupTaskbooks = true;
        $criteria->level = 1;
        $categories = $criteria->all();
        foreach ($categories as $category) {
            $entry = new Entry();
            $entry->sectionId = $sectionId;
            $entry->typeId = $typeId;
            $entry->enabled = true;
            $entry->title = $category->title;

            $moduleCosts = [];
            foreach($category->moduleCosts as $row) {
                $moduleCosts[] = [
                    'col1' => $row['optionalModules'],
                    'col2' => $row['cost']
                ];
            }

            $entry->setFieldValues([
                'moduleMinimumOptional' => $category->moduleMinimumOptional,
                'moduleMaxCost' => $category->moduleMaxCost,
                'moduleResitCost' => $category->moduleResitCost,
                'moduleSingleCost' => $category->moduleSingleCost,
                'moduleCosts' => $moduleCosts
            ]);

            if (!Craft::$app->elements->saveElement($entry)) {
                echo "Could not create " . $category->title . ' ' . var_dump($entry->getFirstErrors(), true);
                return false;
            }

            $categories = [
                $category->id => 1
            ];

            foreach($category->getChildren() as $optional) {
                $categories[$optional->id] = 0;
            }

            if ($entry->id) {

                ## add previous module groups to new module groups field
                foreach($categories as $categoryId => $mandatory) {
                    $this->addModuleGroupBlock($this->taskbookModuleGroups, $entry, $categoryId, $mandatory);
                }

                ## update packages to point to new taskbooks
                $criteria = Entry::find();
                $criteria->section = 'packages';
                $criteria->relatedTo = ['targetElement' => $category->id, 'field' => 'packageModuleGroup'];
                $packages = $criteria->all();
                foreach ($packages as $package) {
                    $package->setFieldValue('packageTaskbook', [$entry->id]);
                    if (!Craft::$app->elements->saveElement($package)) {
                        echo "Could not save " . $package->title;
                        return false;
                    }

                    ## add previous module group to new package module groups field
                    $this->addModuleGroupBlock($this->packageModuleGroups, $package, $category->id, 1, $package->packageLevel);

                    echo "Updated package " . $package->id . "\n\n";

                }
                echo "Converted " . $category->title . "\n\n";
            }

            $criteria = Category::find();
            $criteria->group = 'moduleGroups';
            $criteria->moduleGroupTaskbooks = true;
            $criteria->level = 1;
            $categories = $criteria->all();
            foreach ($categories as $category) {
                $this->resetCategoryChildren($category);
            }
        }
    }

    function resetCategoryChildren($category)
    {
        foreach($category->getChildren() as $optional) {
            $optional->parent = null;
            Craft::$app->elements->saveElement($optional);
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
    function addModuleGroupBlock($field, $owner, $categoryId, $mandatory = 0, $level = 1)
    {
        $stepBlockType = $this->sp->getBlockTypesByFieldId($field->id)[0];
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
        echo "m210721_134639_convertTaskbooks cannot be reverted.\n";
        return false;
    }
}
