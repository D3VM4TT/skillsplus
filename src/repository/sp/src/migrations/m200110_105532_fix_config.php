<?php

namespace lantra\sp\migrations;

use Craft;
use craft\db\Migration;

/**
 * m200128_105532_fix_config migration.
 */
class m200110_105532_fix_config extends Migration
{
    /**
     * @return bool|void
     * @throws \yii\base\NotSupportedException
     * @throws \yii\db\Exception
     */
    public function safeUp()
    {
        $sql = file_get_contents(__DIR__ . '/../resources/sql/config.sql');
        Craft::$app->db->createCommand($sql)->execute();

        ## fix content columns
        $columns = [
            'content' => [
                'userEditStartDate'
            ]
        ];

        foreach($columns as $table => $names) {
            foreach($names as $name) {
                if (!$this->db->columnExists('{{%' . $table . '}}', 'field_' . $name)) {
                    $this->addColumn('{{%' . $table . '}}', 'field_' . $name, $this->text());
                }
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m200128_105532_fix_config cannot be reverted.\n";
        return false;
    }
}
