<?php

namespace lantra\sp\migrations;

use Craft;
use craft\db\Migration;

/**
 * m200316_155706_fix_globals migration.
 */
class m200316_155706_fix_globals extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->update('{{%elements}}', ['fieldLayoutId' => 402], ['type' => 'craft\elements\GlobalSet']);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m200316_155706_fix_globals cannot be reverted.\n";
        return false;
    }
}
