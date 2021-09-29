<?php

namespace lantra\sp\migrations;

use Craft;
use craft\db\Migration;
use craft\elements\Entry;

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
        ## update packages to remove BICS
        $criteria = Entry::find();
        $criteria->section = 'packages';
        $packages = $criteria->all();

        foreach ($packages as $package) {
            foreach ($package->packageModuleGroups as $row) {
                $ids = $row->moduleGroup->ids();
                if (count($ids) == 2) {
                    foreach (array_keys($ids, 307057, true) as $key) {
                        unset($ids[$key]);
                    }
                    $row->setFieldValue('moduleGroup', $ids);
                    Craft::$app->elements->saveElement($row);
                }
            }
        }
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
