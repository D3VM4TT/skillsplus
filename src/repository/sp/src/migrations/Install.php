<?php
/**
 * Lantra Skills Plus for Craft CMS 3.x
 *
 * @link      https://coffeebean.design
 * @copyright Copyright (c) 2020 Coffee Bean Design
 */

namespace lantra\sp\migrations;

use Craft;
use craft\db\Migration;
use craft\elements\User;
use craft\services\Routes as RoutesService;
use lantra\sp\Plugin as Lantra;

class Install extends Migration
{
    /**
     * @return bool
     * @throws \Throwable
     * @throws \craft\errors\InvalidPluginException
     * @throws \yii\db\Exception
     */
    public function safeUp()
    {
        ## sort tables
        $this->_updateTables();

        ## fix stuff
        # $this->_removeLantraUsersTable();
        # $this->_fixSections();
        # $this->_fixModulesSection();
        # $this->_fixLantraResultsCache();

        ## update config
        # $this->_updateStatusField();
        # $this->_deleteRoutes();

        ## sort other plugins
        # $this->_removePlugins();
        Craft::$app->plugins->installPlugin('redactor');
        Craft::$app->plugins->installPlugin('super-table');

        ## $this->_fixConfig();

        $this->_createAdmins();
        
        return true;
    }

    /**
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     */
    private function _createAdmins()
    {
        $admins = [
          'robin@coffeebean.design' => 'Robin Willmott',
          'jason@thisistraffic.co.uk' => 'Jason Church',
          'portia.hartley@skills-plus.co.uk' => 'Portia Hartley'
        ];

        echo "create default Skills+ admin users:";

        foreach ($admins as $email => $fullname) {

            $names = explode(' ', $fullname);
            $user = new User();
            $user->firstName = $names[0];
            $user->lastName = $names[1];
            $user->email = $user->username = $email;
            $user->admin = true;
            if (!Craft::$app->elements->saveElement($user)) {
                echo "could not create account for {$fullname}!";
                continue;
            }
            echo "successfully created account for {$fullname}!";
        }
    }

    /**
     * @throws \yii\base\NotSupportedException
     * @throws \yii\db\Exception
     */
    private function _fixConfig()
    {
        $migration = new m200110_105532_fix_config();
        $migration->safeUp();
    }

    /**
     * make all lantra result cache columns blobs
     */
    private function _fixLantraResultsCache()
    {
        $table = '{{%lantra_result_cache}}';
        $db = Craft::$app->getDb();
        $dbSchema = $db->schema;
        if (false != $tableSchema = $dbSchema->getTableSchema($table)) {
            $columns = $tableSchema->getColumnNames();
            foreach ($columns as $column) {
                if (substr($column, 0, 4) == 'unit') {
                    $this->alterColumn($table, $column, 'blob');
                }
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
            $modules->type = 'structure';
            $modules->structureId = null;
            $sectionsService->saveSection($modules);
        }
    }

    /**
     *
     */
    private function _updateTables()
    {
        if (!$this->db->tableExists('{{%lantra_import}}')) {
            $this->createTable('{{%lantra_import}}', [
                'id' => $this->primaryKey(),
                'type' => $this->string(),
                'data' => $this->string(),
                'processed' => $this->boolean(),
                'dateCreated' => $this->dateTime()->notNull(),
                'dateUpdated' => $this->dateTime()->notNull(),
                'uid' => $this->uid()
            ]);
        }
        if (!$this->db->tableExists('{{%lantra_queue}}')) {
            $this->createTable('{{%lantra_queue}}', [
                'id' => $this->primaryKey(),
                'elementId' => $this->integer(),
                'status' => $this->string(),
                'processed' => $this->boolean(),
                'priority' => $this->integer(),
                'dateCreated' => $this->dateTime()->notNull(),
                'dateUpdated' => $this->dateTime()->notNull(),
                'uid' => $this->uid()
            ]);
        }
        if (!$this->db->tableExists('{{%lantra_result_cache}}')) {
            $this->createTable('{{%lantra_result_cache}}', [
                'userId' => $this->primaryKey(),
                'dateCreated' => $this->dateTime()->notNull(),
                'dateUpdated' => $this->dateTime()->notNull(),
                'uid' => $this->uid()
            ]);
            $this->addForeignKey(
                $this->db->getForeignKeyName('{{%lantra_result_cache}}', 'userId'),
                '{{%lantra_result_cache}}', 'userId', '{{%users}}', 'id', 'CASCADE', null);
        }
        if (!$this->db->tableExists('{{%lantra_settings}}')) {
            $this->createTable('{{%lantra_settings}}', [
                'key' => $this->string(),
                'value' => $this->text(),
                'dateCreated' => $this->dateTime()->notNull(),
                'dateUpdated' => $this->dateTime()->notNull(),
                'uid' => $this->uid()
            ]);
            $this->createIndex('key', '{{%lantra_settings}}', 'key', true);
            # copy default yaml settings to db
            $settings = Craft::$app->getProjectConfig()->get('plugins.sp.settings');
            Lantra::$app->settings->saveSettings($settings);
        }
    }

    /**
     *
     */
    private function _deleteRoutes()
    {
        ## delete routes
        $results = Craft::$app->getProjectConfig()->get(RoutesService::CONFIG_ROUTES_KEY) ?? [];
        foreach ($results as $routeUid => $route) {
            Craft::$app->routes->deleteRouteByUid($routeUid);
        }
    }

    /**
     * @throws \yii\db\Exception
     */
    private function _fixSections() {
        ## fix sections settings
        $query = $this->db->createCommand();
        $query->update('{{%entrytypes}}', ['titleFormat' => '{teamCompany.one.title} - {teamName}'], ['handle' => 'team'])->execute();
        $query->update('{{%entrytypes}}', ['titleFormat' => '[unit {resultUnit.one.id}] {author.firstName} {author.lastName}'], ['handle' => 'unitResult'])->execute();
        $query->update('{{%entrytypes}}', ['titleFormat' => '[unit {attemptUnit.one.id}] {author.firstName} {author.lastName}'], ['handle' => 'attempt'])->execute();
        $query->update('{{%entrytypes}}', ['titleFormat' => '[module {resultModule.one.id}] {author.firstName} {author.lastName} '], ['handle' => 'moduleResult'])->execute();
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
     * @throws \yii\db\Exception
     */
    private function _updateStatusField ()
    {
        ## update status field to dropdown
        $resultStatus = get_class(Craft::$app->fields->getFieldByHandle('resultStatus'));
        if ($resultStatus != 'craft\fields\Dropdown') {
            $query = $this->db->createCommand();
            $settings = '{"options":[{"label":"Draft","value":"draft","default":"1"},{"label":"Pending","value":"pending","default":""},{"label":"Endorsed","value":"endorsed","default":""},{"label":"Failed","value":"failed","default":""},{"label":"Blocked","value":"blocked","default":""},{"label":"Active","value":"active","default":""},{"label":"Complete","value":"complete","default":""}]}';
            $query->update('{{%fields}}', ['type' => 'craft\fields\Dropdown', 'settings' => $settings], ['handle' => 'resultStatus'])->execute();
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
     * @return bool
     */
    public function safeDown()
    {
        $this->dropTableIfExists('{{%lantra_import}}');
        $this->dropTableIfExists('{{%lantra_queue}}');
        $this->dropTableIfExists('{{%lantra_result_cache}}');
        $this->dropTableIfExists('{{%lantra_settings}}');
        return true;
    }
}