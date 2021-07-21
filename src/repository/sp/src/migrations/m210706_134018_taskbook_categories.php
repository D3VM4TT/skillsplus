<?php

namespace lantra\sp\migrations;

use Craft;
use craft\db\Migration;
use craft\elements\Category;
use craft\elements\Entry;

use craft\records\CategoryGroup as CategoryGroupRecord;
use craft\records\Field as FieldRecord;


/**
 * m210706_134018_taskbook_categories migration.
 */
class m210706_134018_taskbook_categories extends Migration
{
    /**
     * @return bool|void
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     */
    public function safeUp()
    {
        $group = CategoryGroupRecord::find()->where(['handle' => 'taskbookGroups'])->one();
        $criteria = Category::find();
        $criteria->group = 'moduleGroups';
        $criteria->moduleGroupTaskbooks = true;
        foreach ($criteria->all() as $category) {
            $category->groupId = $group->id;
            if (!Craft::$app->elements->saveElement($category)) {
                echo "could not update {$category->title}!";
                continue;
            }
            ## update structure table
            $query = $this->db->createCommand();
            $query->update('{{%structureelements}}', ['structureId' => $group->structureId], ['elementId' => $category->id])->execute();

            ## update elements table
            $query = $this->db->createCommand();
            $query->update('{{%elements}}', ['fieldLayoutId' => $group->fieldLayoutId], ['id' => $category->id])->execute();
            echo "updated {$category->title}!\n\n";
        }

        ## copy over module groups to taskbook groups
        $moduleGroupField = FieldRecord::find()->where(['handle' => 'moduleGroup'])->one();
        $taskbookGroupField = FieldRecord::find()->where(['handle' => 'taskbookGroup'])->one();
        ## copy over module groups to taskbook groups
        $criteria = Entry::find();
        $criteria->section = 'modules';
        $criteria->type = 'taskbook';
        foreach ($criteria->all() as $module) {
            ## update structure table
            $query = $this->db->createCommand();
            $query->update('{{%relations}}', ['fieldId' => $taskbookGroupField->id], ['sourceId' => $module->id, 'fieldId' => $moduleGroupField->id])->execute();
            echo "updated {$module->title}!";
        }
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m210706_134018_taskbook_categories cannot be reverted.\n";
        return false;
    }
}
