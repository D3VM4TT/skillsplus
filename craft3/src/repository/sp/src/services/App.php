<?php
/**
 * Lantra Skills Plus for Craft CMS 3.x
 *
 * @link      https://coffeebean.design
 * @copyright Copyright (c) 2020 Coffee Bean Design
 */

namespace lantra\sp\services;

use craft\base\Component;


/**
 * Class App
 * @package lantra\sp\services
 */
class App extends Component
{
    public $attempt;
    public $deploy;
    public $licence;
    public $migration;
    public $notify;
    public $queue;
    public $report;
    public $result;
    public $settings;
    public $structure;
    public $user;

    public function init()
    {
        $this->attempt = new Attempt();
        $this->deploy = new Deploy();
        $this->licence = new Licence();
        $this->migration = new Migration();
        $this->notify = new Notify();
        $this->queue = new Queue();
        $this->report = new Report();
        $this->result = new Result();
        $this->settings = new Settings();
        $this->structure = new Structure();
        $this->user = new User();
    }
}