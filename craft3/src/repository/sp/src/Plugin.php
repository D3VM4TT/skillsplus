<?php
/**
 * Lantra Skills Plus for Craft CMS 3.x
 *
 * @link      https://coffeebean.design
 * @copyright Copyright (c) 2020 Coffee Bean Design
 */

namespace lantra\sp;

use Craft;
use craft\base\Plugin as BasePlugin;
use craft\elements\User;
use craft\elements\Entry;
use craft\events\ModelEvent;
use craft\web\twig\variables\CraftVariable;
use craft\helpers\App as AppHelper;
use yii\base\Event;

use lantra\sp\Plugin as Lantra;
use lantra\sp\services\App;
use lantra\sp\models\Settings;
use lantra\sp\variables\LantraVariable;

/**
 * Class LantraPlugin
 * @package Craft
 */
class Plugin extends BasePlugin
{
    /**
     * @var \lantra\sp\services\App The application instance.
     */
    public static $app;
    public static $plugin;

    public $schemaVersion = '0.0.1';

    private $sectionIdCompanies = 3;
    private $sectionIdUnits     = 7;
    private $sectionIdResults   = 10;
    private $sectionIdAttempts  = 12;

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        AppHelper::maxPowerCaptain();

        $this->setComponents([
            'app' => App::class
        ]);

        $this::$app = $this->get('app');

        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            function (Event $event) {
                $variable = $event->sender;
                $variable->set('lantra', LantraVariable::class);
            }
        );

        Event::on(
            User::class,
            User::EVENT_AFTER_SAVE,
            function (ModelEvent $event) {
                $user = $event->sender;
                Lantra::$app->users->onSaveUser($event, $user);
            }
        );

        Event::on(
            User::class,
            User::EVENT_BEFORE_SAVE,
            function (ModelEvent $event) {
                $user = $event->sender;
                Lantra::$app->users->onBeforeSaveUser($event, $user);
            }
        );

        Event::on(
            User::class,
            User::EVENT_BEFORE_DELETE,
            function (ModelEvent $event) {
                $user = $event->sender;
                Lantra::$app->users->onBeforeDeleteUser($user, $event);
            }
        );

        Event::on(
            Entry::class,
            Entry::EVENT_BEFORE_SAVE,
            function (ModelEvent $event) {
                $entry = $event->sender;
                if ($entry->sectionId == $this->sectionIdResults) {
                    Lantra::$app->results->onBeforeSaveResult($event, $entry);
                }
                elseif($entry->sectionId == $this->sectionIdAttempts) {
                    Lantra::$app->attempts->onBeforeSaveAttempt($event, $entry);
                }
                elseif ($entry->sectionId == $this->sectionIdCompanies) {
                    Lantra::$app->structure->onBeforeSaveCompany($event, $entry);
                }
            }
        );

        Event::on(
            Entry::class,
            Entry::EVENT_AFTER_SAVE,
            function (ModelEvent $event) {
                $this->resetUploads();
                $entry = $event->params['entry'];
                if ($entry->sectionId == $this->sectionIdCompanies) {
                    Lantra::$app->structure->onSaveCompany($event, $entry);
                }
                elseif ($entry->sectionId == $this->sectionIdResults) {
                    Lantra::$app->results->onSaveResult($event, $entry);
                }
                elseif ($entry->sectionId == $this->sectionIdAttempts) {
                    Lantra::$app->attempts->onSaveResult($event, $entry);
                }
                elseif ($entry->sectionId == $this->sectionIdUnits) {
                    Lantra::$app->structure->onSaveUnit($event, $entry);
                }
        });

        Event::on(
            Entry::class,
            Entry::EVENT_AFTER_DELETE,
            function (ModelEvent $event) {
                $entry = $event->params['entry'];
                if ($entry->sectionId == $this->sectionIdUnits) {
                    Lantra::$app->structure->onDeleteUnit($event, $entry);
                }
            });
    }

    /**
     * @inheritdoc
     */
    public function getCpNavItem(): array
    {
        $ret = parent::getCpNavItem();
        $ret['label'] = 'Lantra Skills Plus';
        return $ret;
    }

    /**
     * @inheritdoc
     */
    protected function createSettingsModel()
    {
        return new Settings();
    }

    /**
     * @return mixed|\yii\web\Response
     */
    public function getSettingsResponse()
    {
    }

    /**
     * @return array
     */
    private function getCpUrlRules()
    {
    }

    /**
     * @return array
     */
    private function getSiteUrlRules()
    {
    }
}

