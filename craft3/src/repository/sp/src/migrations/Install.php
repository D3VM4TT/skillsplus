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