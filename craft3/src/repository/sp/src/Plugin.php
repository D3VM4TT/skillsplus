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
use craft\events\RegisterUrlRulesEvent;
use craft\web\twig\variables\CraftVariable;
use craft\helpers\App as AppHelper;
use craft\web\UrlManager;
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
            UrlManager::class,
            UrlManager::EVENT_REGISTER_CP_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                $event->rules = array_merge($event->rules, $this->getCpUrlRules());
            }
        );

        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_SITE_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                $event->rules = array_merge($event->rules, $this->getSiteUrlRules());
            }
        );

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
                $entry = $event->sender;
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
            function (Event $event) {
                $entry = $event->sender;
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
        return [];
    }

    /**
     * @return array
     */
    private function getSiteUrlRules()
    {
        return [
            ## public routes
            'public/certificate/<userId>/<resultId>'    => ['template' => 'public/certificate'],
            'public/passport/<userId>'                  => ['template' => 'public/passport'],
            'public/licence/thanks'                     => ['template' => 'public/licence'],

            ## cpd routes
            'profile'                                   => ['template' => 'profile/index'],
            'cpd/<userId>'                              => ['template' => 'cpd/index'],
            'cpd/<userId>/archive'                      => ['template' => 'cpd/index'],
            'cpd/<userId>/print'                        => ['template' => 'cpd/index'],
            'cpd/<userId>/<moduleId>/<unitId>'          => ['template' => 'cpd/unit'],
            'cpd/<userId>/<moduleId>/<unitId>/test'     => ['template' => 'cpd/unit'],
            'cpd/<userId>/achievement'                  => ['template' => 'cpd/achievement'],
            'cpd/<userId>/achievement/<resultId>'       => ['template' => 'cpd/achievement'],
            'cpd/<userId>/result/<resultId>'            => ['template' => 'cpd/achievement'],
            'result/<resultId>'                         => ['template' => 'result/_form'],

            ## management routes
            'management/<section>/edit/<elementId>'     => ['template' => 'management/index'],
            'management/<section>/new'                  => ['template' => 'management/index'],
            'management/users/company/<companyId>'      => ['template' => 'management/users'],

            ## reporting routes
            'reporting/custom/edit/<reportId>'          => ['template' => 'reporting/custom/_form'],
            'reporting/custom/new'                      => ['template' => 'reporting/custom/_form'],
            'reporting/user/<userId>'                   => ['template' => 'reporting/user'],
            'reporting/standard/<reportSlug>/csv'       => ['template' => 'reporting/standard'],
            'reporting/standard/<reportSlug>'           => ['template' => 'reporting/standard'],

            ## action routes
            'sp/users/hierarchy'                        => 'sp/users/hierarchy',
            'sp/users/refresh-hierarchy'                => 'sp/users/refresh-hierarchy',
            'sp/users/suspend-user'                     => 'sp/users/suspend-user',
            'sp/users/delete-user'                      => 'sp/users/delete-user',
            'sp/users/restore-user'                     => 'sp/users/restore-user',
            'sp/users/company-managers'                 => 'sp/users/company-managers',
            'sp/users/save-user'                        => 'sp/users/save-user',

            'sp/entries/reset-result'                   => 'sp/entries/reset-result',
            'sp/entries/delete-entry'                   => 'sp/entries/delete-entry',
            'sp/entries/endorse-evidence'               => 'sp/entries/endorse-evidence',
            'sp/entries/pending-result'                 => 'sp/entries/pending-result',

            'sp/categories/delete-category'             => 'sp/categories/delete-category',

            'sp/reports/save-report'                    => 'sp/reports/save-report',
            'sp/reports/delete-report'                  => 'sp/reports/delete-report',
            'sp/reports/run-report'                     => 'sp/reports/run-report',
        ];
    }

    /**
     *
     */
    private function resetUploads()
    {
        unset($_FILES);
        ## UploadedFile::reset();
    }
}

