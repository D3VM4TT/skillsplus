<?php
/**
 * Lantra Skills Plus for Craft CMS 3.x
 *
 * @link      https://coffeebean.design
 * @copyright Copyright (c) 2020 Coffee Bean Design
 */

namespace lantra\sp\services;

use Craft;
use craft\base\Component;

class Deploy extends Component
{
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
        $this->user = $dbConfig->user;
        $this->password = $dbConfig->password;

        $parts = explode('.', $_SERVER['HTTP_HOST']);
        $site = array_shift($parts);

        $currentDatabase = $dbConfig->database;
        $targetDatabase = $target . '-' . $site;

        $filename = '/tmp/' . date('ymd') . '.' . $site . '.sql';

        ## switch server if on prod
        $dbServerProd = getenv('DB_HOST_PROD');
        $dbServerDev = getenv('DB_HOST_DEV');
        $environment = getenv('ENVIRONMENT');

        $currentServer = $environment == 'prod' ? $dbServerProd : $dbServerDev;
        $targetServer = $target == 'prod' ? $dbServerProd : $dbServerDev;

        if (!$this->export($currentServer, $currentDatabase, $filename)) {
            $this->message = 'Database export failed.';
            return false;
        }
        if (!$this->import($targetServer, $targetDatabase, $filename)) {
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
    public function export($server, $database, $filename) {
        $command = "mysqldump --opt -h " . $server . " -u " . $this->user . " -p'". $this->password . "' " . $database . " > " . $filename;
        exec($command, $output, $return);
        return $return != 0;
    }

    /**
     * @param $database
     * @param $filename
     * @return bool
     */
    public function import($server, $database, $filename) {
        $command = "mysql -h " . $server . " -u " . $this->user . " -p'" . $this->password . "' " . $database . " < " . $filename;
        exec($command, $output, $return);
        return $return != 0;
    }
}
