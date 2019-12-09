<?php
/**
 * Lantra Skills Plus for Craft CMS 3.x
 *
 * @link      https://coffeebean.design
 * @copyright Copyright (c) 2020 Coffee Bean Design
 */

namespace lantra\sp;

use craft\db\Migration;

class Install extends Migration
{
    /**
     * @return bool
     */
    public function safeUp()
    {
        ## update status field to dropdown
        $query = $this->db->createCommand();
        $settings = '{"options":[{"label":"Draft","value":"draft","default":""},{"label":"Pending","value":"pending","default":"1"},{"label":"Endorsed","value":"endorsed","default":""},{"label":"Failed","value":"failed","default":""},{"label":"Blocked","value":"blocked","default":""},{"label":"Active","value":"active","default":""},{"label":"Complete","value":"complete","default":""}]}';
        $query->update('{{%fields}}', ['type', 'settings'], ['craft\fields\Dropdown', $settings], ['handle' => 'resultStatus']);
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