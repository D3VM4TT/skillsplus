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
    public $server;
    public $user;
    public $password;
    public $message;

    public function copyDatabase($target = 'dev') {

        $this->server = craft()->config->get('server', ConfigFile::Db);
        $this->user = craft()->config->get('user', ConfigFile::Db);
        $this->password = craft()->config->get('password', ConfigFile::Db);

        $parts = explode('.', $_SERVER['HTTP_HOST']);
        $site = array_shift($parts);

        $currentDatabase = craft()->config->get('database', ConfigFile::Db);
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

    public function export($database, $filename) {
        $command = "mysqldump --opt -h " . $this->server . " -u " . $this->user . " -p'". $this->password . "' " . $database . " > " . $filename;
        exec($command, $output, $return);
        return $return != 0;
    }

    public function import($database, $filename) {
        $command = "mysql -h " . $this->server . " -u " . $this->user . " -p'" . $this->password . "' " . $database . " < " . $filename;
        exec($command, $output, $return);
        return $return != 0;
    }
}
