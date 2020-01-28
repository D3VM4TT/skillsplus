<?php

namespace lantra\sp\migrations;

use Craft;
use craft\db\Migration;

/**
 * m200128_160852_rename_unitValue migration.
 */
class m200128_160852_rename_unitValue extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->update('{{%stc_columnlayout}}', ['field_fieldType' => 'unitPoints'], ['field_fieldType' => 'unitValue']);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m200128_160852_rename_unitValue cannot be reverted.\n";
        return false;
    }
}
