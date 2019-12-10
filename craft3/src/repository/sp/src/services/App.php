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
    public $attempts;
    public $deploy;
    public $licences;
    public $migrations;
    public $notify;
    public $queue;
    public $reports;
    public $results;
    public $settings;
    public $structure;
    public $users;

    public function init()
    {
        $this->attempts = new Attempts();
        $this->deploy = new Deploy();
        $this->licences = new Licences();
        $this->migrations = new Migrations();
        $this->notify = new Notify();
        $this->queue = new Queue();
        $this->reports = new Reports();
        $this->results = new Results();
        $this->settings = new Settings();
        $this->structure = new Structure();
        $this->users = new Users();
    }
}