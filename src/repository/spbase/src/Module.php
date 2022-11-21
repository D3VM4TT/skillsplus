<?php
namespace lantra\spbase;

use Craft;

use craft\events\RegisterUrlRulesEvent;
use craft\web\UrlManager;
use craft\log\FileTarget;
use craft\events\RegisterTemplateRootsEvent;
use craft\web\View;

use lantra\sp\Plugin as Lantra;
use lantra\spbase\services\SpBase;

use yii\base\Event;
use yii\log\Logger;

class Module extends \yii\base\Module
{
    public static $plugin;

    /**
     *
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        $this->setComponents([
            'spbase' => SpBase::class
        ]);

        ## add the spbase log file
        $fileTarget = new FileTarget([
            'logVars' => [],
            'logFile' => '@storage/logs/spbase.log',
            'categories' => ['spbase']
        ]);
        Craft::getLogger()->dispatcher->targets[] = $fileTarget;

        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_SITE_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                $event->rules = array_merge($event->rules, $this->getSiteUrlRules());
            }
        );

        Event::on(
            View::class,
            View::EVENT_REGISTER_SITE_TEMPLATE_ROOTS,
            function(RegisterTemplateRootsEvent $event) {
                $event->roots['spbase'] = __DIR__ . '/templates/';
            }
        );

        parent::init();
    }

    /**
     * @param $message
     * @return string[]
     */
    public static function getSiteUrlRules()
    {
        return [
            'spbase/paypal/process/<userId{slug}>/<reference{slug}>' => 'spbase/paypal/process',
            'spbase/users/info/<action{slug}>' => 'spbase/users/info',
            'spbase/users/resave' => 'spbase/users/resave'
        ];
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