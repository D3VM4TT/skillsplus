<?php

namespace lantra\sp\migrations;

use Craft;
use craft\db\Migration;

/**
 * m200120_163910_craft3 migration.
 */
class m200120_163910_craft3 extends Migration
{
    /**
     * @return bool|void
     * @throws \Throwable
     * @throws \craft\errors\InvalidPluginException
     * @throws \craft\errors\SectionNotFoundException
     */
    public function safeUp()
    {
        $this->_removePlugins();
        $this->_removeLantraUsersTable();
        $this->_fixModulesSection();
        $this->_fixLantraResultsCache();
    }

    /**
     * make all lantra result cache columns blobs
     */
    private function _fixLantraResultsCache()
    {
        $table = '{{%lantra_result_cache}}';
        $db = Craft::$app->getDb();
        $dbSchema = $db->schema;
        $tableSchema = $dbSchema->getTableSchema($table);
        $columns = $tableSchema->getColumnNames();
        foreach($columns as $column) {
            if (substr($column, 0, 4) == 'unit') {
                $this->alterColumn($table, $column, 'blob');
            }
        }
    }


    /**
     * @throws \Throwable
     * @throws \craft\errors\SectionNotFoundException
     */
    private function _fixModulesSection()
    {
        $sectionsService = Craft::$app->getSections();
        if(false != $modules = $sectionsService->getSectionByHandle('modules')) {
            $modules->type = 'channel';
            $modules->structureId = null;
            $sectionsService->saveSection($modules);
        }
    }

    /**
     *
     */
    private function _removeLantraUsersTable ()
    {
        $this->dropTableIfExists('lantra_users');
    }

    /**
     * @throws \Throwable
     * @throws \craft\errors\InvalidPluginException
     */
    private function _removePlugins()
    {
        $pluginsService = Craft::$app->getPlugins();
        $dbService = Craft::$app->getDb();
        $plugins = ['lantra', 'status', 'internal-assets', 'sprout-reports', 'export', 'import', 'printmaker', 'mailer', 'migration-manager'];
        foreach($plugins as $plugin) {
            if ($pluginsService->getPlugin($plugin)) {
                $pluginsService->enablePlugin($plugin);
                $pluginsService->uninstallPlugin($plugin);
            }
            $dbService->createCommand()->delete('{{%plugins}}', ['handle' => $plugin])->execute();
        }
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m200121_103249_craft3 cannot be reverted.\n";
        return false;
    }
}
