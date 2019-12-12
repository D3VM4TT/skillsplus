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
        return true;
    }

    /**
     * @return bool
     */
    public function safeDown()
    {
        return true;
    }
}