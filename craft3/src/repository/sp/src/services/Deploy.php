<?php
/**
 * Lantra Skills Plus for Craft CMS 3.x
 *
 * @link      https://coffeebean.design
 * @copyright Copyright (c) 2020 Coffee Bean Design
 */

namespace lantra\sp\services;

use Craft;
use craft\helpers\App;
use craft\base\Component;

class Deploy extends Component
{
    public $server;
    public $user;
    public $password;
    public $message;

    /**
     * @param string $target
     * @return bool
     */
    public function copyDatabase($target = 'dev')
    {
        $dbConfig = Craft::$app->getConfig()->getDb();
        $this->server = $dbConfig->server;
        $this->user = $dbConfig->user;
        $this->password = $dbConfig->password;

        $parts = explode('.', $_SERVER['HTTP_HOST']);
        $site = array_shift($parts);

        $currentDatabase = $dbConfig->database;
        $targetDatabase = $target . '-' . $site;

        $filename = '/tmp/' . date('ymd') . '.' . $site . '.sql';

        if ($this->export($currentDatabase, $filename)) {
            $this->message = 'Database export failed.';
            return false;
        }
        if ($this->import($targetDatabase, $filename)) {
            $this->message = 'Database import failed.';
            return false;
        }
        $this->message = 'Database has been copied to ' . $targetDatabase . '.';
        return true;
    }

    /**
     * @param $database
     * @param $filename
     * @return bool
     */
    public function export($database, $filename) {
        $command = "mysqldump --opt -h " . $this->server . " -u " . $this->user . " -p'". $this->password . "' " . $database . " > " . $filename;
        exec($command, $output, $return);
        return $return != 0;
    }

    /**
     * @param $database
     * @param $filename
     * @return bool
     */
    public function import($database, $filename) {
        $command = "mysql -h " . $this->server . " -u " . $this->user . " -p'" . $this->password . "' " . $database . " < " . $filename;
        exec($command, $output, $return);
        return $return != 0;
    }
}
