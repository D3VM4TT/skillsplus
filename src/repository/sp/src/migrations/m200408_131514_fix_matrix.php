<?php

namespace lantra\sp\migrations;

use Craft;
use craft\db\Migration;

/**
 * m200408_131514_fix_matrix migration.
 */
class m200408_131514_fix_matrix extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $columns = [
            'matrixcontent_moduleunitgroups' => [
                'unitGroup_enableSubmissions',
                'unitGroup_unitPointsOverride'
            ],
            'stc_columnlayout' => [
                'fieldRequired',
                'fieldDisplay'
            ]
        ];

        foreach($columns as $table => $names) {
            foreach($names as $name) {
                if (!$this->db->columnExists('{{%' . $table . '}}', 'field_' . $name)) {
                    $this->addColumn('{{%' . $table . '}}', 'field_' . $name, $this->boolean());
                }
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m200408_131514_fix_matrix cannot be reverted.\n";
        return false;
    }
}
