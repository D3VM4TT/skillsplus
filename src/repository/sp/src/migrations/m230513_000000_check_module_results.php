<?php

namespace lantra\sp\migrations;

use Craft;
use craft\db\Migration;
use craft\elements\Entry;
use lantra\sp\Plugin as Lantra;

/**
 * m230513_000000_check_module_results migration.
 */
class m230513_000000_check_module_results extends Migration
{
    /**
     * @return bool|void
     */
    public function safeUp()
    {
        $criteria = Entry::find()
            ->section('modules')
            ->type('cpd');

        $cpdModuleIds = $criteria->ids();

        $criteria = Entry::find()
            ->section('results')
            ->type('moduleResult')
            ->relatedTo(['targetElement' => $cpdModuleIds, 'field' => 'resultModule']);

        $moduleResults = $criteria->all();

        foreach ($moduleResults as $result) {
            Lantra::$app->results->checkModuleResult($result->resultModule->one(), $result->authorId, $result);
        }
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m230513_000000_resave_results cannot be reverted.\n";
        return false;
    }
}
