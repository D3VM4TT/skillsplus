<?php

namespace lantra\sp\migrations;

use Craft;
use craft\db\Migration;

/**
 * m200312_134244_fix_sites migration.
 */
class m200312_134244_fix_sites extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->update('{{%content}}', ['siteId' => 1], ['siteId' => 2]);
        $this->update('{{%elements_sites}}', ['siteId' => 1], ['siteId' => 2]);
        $this->update('{{%categorygroups_sites}}', ['siteId' => 1], ['siteId' => 2]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m200312_134244_fix_sites cannot be reverted.\n";
        return false;
    }
}
