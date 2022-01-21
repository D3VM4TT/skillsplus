<?php
namespace lantra\spbase;

use Craft;

use lantra\sp\Plugin as Lantra;
use craft\log\FileTarget;
use yii\log\Logger;

class Module extends \yii\base\Module
{
    /**
     *
     */
    public function init()
    {
        ## add the spbase log file
        $fileTarget = new FileTarget([
            'logVars' => [],
            'logFile' => '@storage/logs/spbase.log',
            'categories' => ['spbase']
        ]);
        Craft::getLogger()->dispatcher->targets[] = $fileTarget;

        parent::init();
    }

    /**
     * @param $message
     */
    public static function error($message)
    {
        Lantra::$app->notify->notifyAdmin('Skills+ Base Critical Error', $message);

        self::log($message, Logger::LEVEL_ERROR);
    }

    /**
     * @param $message
     */
    public static function warning($message)
    {
        self::log($message, Logger::LEVEL_WARNING);
    }

    /**
     * @param $message
     * @param string $level
     */
    public static function log($message, $level = Logger::LEVEL_INFO)
    {
        Craft::getLogger()->log($message, $level, 'spbase');
    }
}