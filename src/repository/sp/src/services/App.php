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
 *
 * @property-read \lantra\sp\services\Attempts $attempts
 * @property-read \lantra\sp\services\Deploy $deploy
 * @property-read \lantra\sp\services\Licences $licences
 * @property-read \lantra\sp\services\Migrations $migrations
 * @property-read \lantra\sp\services\Notify $notify
 * @property-read \lantra\sp\services\Queue $queue
 * @property-read \lantra\sp\services\Reports $reports
 * @property-read \lantra\sp\services\Results $results
 * @property-read \lantra\sp\services\Settings $settings
 * @property-read \lantra\sp\services\Structure $structure
 * @property-read \lantra\sp\services\Users $users
 *
 */
class App extends Component
{
    public $attempts;
    public $cycles;
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

    /**
     *
     */
    public function init()
    {
        $this->attempts = new Attempts();
        $this->cycles = new Cycles();
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

    /**
     * @param $key
     * @param $default
     * @return mixed|null
     */
    public function getSetting($key, $default = null) {
        return isset($this->settings->$key) ? $this->settings->$key : $default;
    }
}