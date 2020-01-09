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
use craft\services\Routes as RoutesService;
use lantra\sp\Plugin as Lantra;

class Install extends Migration
{
    /**
     * @return bool
     * @throws \yii\db\Exception
     */
    public function safeUp()
    {
        ## @todo create fields
        ## @todo create sections
        ## @todo create volumes
        ## @todo create user groups

        ## fix sections settings
        $query = $this->db->createCommand();
        $query->update('{{%entrytypes}}', ['titleFormat' => '{teamCompany.one.title} - {teamName}'], ['handle' => 'team'])->execute();
        $query->update('{{%entrytypes}}', ['titleFormat' => '[unit {resultUnit.one.id}] {author.firstName} {author.lastName}'], ['handle' => 'unitResult'])->execute();
        $query->update('{{%entrytypes}}', ['titleFormat' => '[unit {attemptUnit.one.id}] {author.firstName} {author.lastName}'], ['handle' => 'attempt'])->execute();
        $query->update('{{%entrytypes}}', ['titleFormat' => '[module {resultModule.one.id}] {author.firstName} {author.lastName} '], ['handle' => 'moduleResult'])->execute();

        ## update status field to dropdown
        $resultStatus = get_class(Craft::$app->fields->getFieldByHandle('resultStatus'));
        if ($resultStatus != 'craft\fields\Dropdown') {
            $query = $this->db->createCommand();
            $settings = '{"options":[{"label":"Draft","value":"draft","default":"1"},{"label":"Pending","value":"pending","default":""},{"label":"Endorsed","value":"endorsed","default":""},{"label":"Failed","value":"failed","default":""},{"label":"Blocked","value":"blocked","default":""},{"label":"Active","value":"active","default":""},{"label":"Complete","value":"complete","default":""}]}';
            $query->update('{{%fields}}', ['type' => 'craft\fields\Dropdown', 'settings' => $settings], ['handle' => 'resultStatus'])->execute();
        }

        ## delete routes
        $results = Craft::$app->getProjectConfig()->get(RoutesService::CONFIG_ROUTES_KEY) ?? [];
        foreach ($results as $routeUid => $route) {
            Craft::$app->routes->deleteRouteByUid($routeUid);
        }

        ## update tables
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
            # copy lantra settings to sp
            $settings = Craft::$app->getProjectConfig()->get('plugins.lantra.settings');
            Lantra::$app->settings->saveSettings($settings);
            Craft::$app->getProjectConfig()->remove('plugins.lantra');
        }
        $this->delete('{{%supertableblocktypes}}', ['fieldLayoutId' => '']);
        Craft::$app->plugins->installPlugin('redactor');
        Craft::$app->getProjectConfig()->remove('plugins.status');
        Craft::$app->getProjectConfig()->remove('plugins.internal-assets');
        Craft::$app->getProjectConfig()->remove('plugins.sprout-reports');
        Craft::$app->getProjectConfig()->remove('plugins.export');
        Craft::$app->getProjectConfig()->remove('plugins.import');
        Craft::$app->getProjectConfig()->remove('plugins.printmaker');
        Craft::$app->getProjectConfig()->remove('plugins.mailer');
        Craft::$app->getProjectConfig()->remove('plugins.migration-manager');
        return true;
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