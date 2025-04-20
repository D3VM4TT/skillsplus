<?php
/**
 * Lantra Skills Plus for Craft CMS 3.x
 *
 * @link      https://coffeebean.design
 * @copyright Copyright (c) 2020 Coffee Bean Design
 */

namespace lantra\sp\services;

use craft\base\Component;

use lantra\sp\services\paypal\PayPal;
use lantra\spbase\services\SpBase;

/**
 * Class App
 * @package lantra\sp\services
 *
 * @property-read \lantra\sp\services\Evidence $evidence
 * @property-read \lantra\sp\services\Attempts $attempts
 * @property-read \lantra\sp\services\Deploy $deploy
 * @property-read \lantra\sp\services\Licences $licences
 * @property-read \lantra\sp\services\Migrations $migrations
 * @property-read \lantra\sp\services\Notify $notify
 * @property-read \lantra\sp\services\Queue $queue
 * @property-read \lantra\sp\services\Packages $packages
 * @property-read \lantra\sp\services\Products $products
 * @property-read \lantra\sp\services\paypal\PayPal $paypal
 * @property-read \lantra\sp\services\Records $records
 * @property-read \lantra\sp\services\Reports $reports
 * @property-read \lantra\sp\services\Results $results
 * @property-read \lantra\sp\services\Settings $settings
 * @property-read \lantra\spbase\services\SpBase $spbase
 * @property-read \lantra\sp\services\Structure $structure
 * @property-read \lantra\sp\services\Users $users
 *
 */
class App extends Component
{
    public $evidence;
    public $attempts;
    public $cycles;
    public $deploy;
    public $licences;
    public $migrations;
    public $modules;
    public $notify;
    public $queue;
    public $packages;
    public $paypal;
    public $products;
    public $records;
    public $reports;
    public $results;
    public $settings;
    public $spbase;
    public $structure;
    public $users;

    /**
     *
     */
    public function init() : void
    {
        $this->evidence = new Evidence();
        $this->attempts = new Attempts();
        $this->cycles = new Cycles();
        $this->deploy = new Deploy();
        $this->licences = new Licences();
        $this->migrations = new Migrations();
        $this->modules = new Modules();
        $this->notify = new Notify();
        $this->queue = new Queue();
        $this->paypal = new PayPal();
        $this->packages = new Packages();
        $this->products = new Products();
        $this->records = new Records();
        $this->reports = new Reports();
        $this->results = new Results();
        $this->settings = new Settings();
        $this->spbase = new SpBase();
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