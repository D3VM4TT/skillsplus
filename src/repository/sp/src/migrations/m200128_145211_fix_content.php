<?php

namespace lantra\sp\migrations;

use Craft;
use craft\db\Migration;

/**
 * m200128_145211_fix_content migration.
 */
class m200128_145211_fix_content extends Migration
{
    /**
     * @return bool|void
     * @throws \yii\base\NotSupportedException
     */
    public function safeUp()
    {
        $fieldsService = Craft::$app->getFields();
        foreach($fieldsService->getAllFields() as $field) {
            if ($field->hasContentColumn())
            if (!$this->db->columnExists('{{%content}}', 'field_' . $field->handle)) {
                $this->addColumn('{{%content}}', 'field_' . $field->handle, $field->getContentColumnType());
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m200128_145211_fix_content cannot be reverted.\n";
        return false;
    }
}
