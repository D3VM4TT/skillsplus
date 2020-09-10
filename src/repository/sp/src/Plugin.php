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
use craft\base\Element;
use craft\elements\Asset;
use craft\elements\User;
use craft\elements\Entry;
use craft\events\ModelEvent;
use craft\events\RegisterUrlRulesEvent;
use craft\events\RegisterUserPermissionsEvent;
use craft\events\TemplateEvent;
use craft\events\DefineBehaviorsEvent;
use craft\events\UserEvent;
use craft\web\View;
use craft\services\Users;
use craft\services\UserPermissions;
use craft\web\twig\variables\CraftVariable;
use craft\helpers\App as AppHelper;
use craft\helpers\UrlHelper;
use craft\helpers\ElementHelper;
use craft\log\FileTarget;
use craft\web\UrlManager;
use lantra\sp\models\Record;
use yii\base\Event;
use yii\db\Query;

use lantra\sp\Plugin as Lantra;
use lantra\sp\behaviors\PackageBehavior;
use lantra\sp\behaviors\UserRecordBehavior;
use lantra\sp\services\App;
use lantra\sp\models\Settings;
use lantra\sp\variables\LantraVariable;
use lantra\sp\assetbundles\SpCpAsset;
use lantra\sp\migrations\m200128_160852_rename_unitValue;



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
    private $sectionIdModules   = 6;
    private $sectionIdUnits     = 7;
    private $sectionIdResults   = 10;
    private $sectionIdAttempts  = 12;
    private $sectionIdPackages  = 15;
    private $sectionIdWorkflows = 16;

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

        ## add the lantra log file
        $fileTarget = new FileTarget(['logFile' => '@storage/logs/lantra.log', 'categories' => ['lantra\sp\*']]);
        Craft::getLogger()->dispatcher->targets[] = $fileTarget;

        Event::on(
            View::class,
            View::EVENT_BEFORE_RENDER_TEMPLATE,
            function (TemplateEvent $event) {
                $request = Craft::$app->getRequest();
                if ($request->getIsConsoleRequest()) {
                    return;
                }
                if ($request->getUrl() == '/actions/update/updateDatabase') {
                    return;
                }
                if ($request->isCpRequest) {
                    $view = Craft::$app->getView();
                    $view->registerAssetBundle(SpCpAsset::class);
                    $js = "Craft.schemeName='" . Lantra::$app->settings->getSetting('schemeName') ."'";
                    $view->registerJs($js, View::POS_END);
                }
            }
        );

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
            Users::class,
            Users::EVENT_AFTER_ACTIVATE_USER,
            function (UserEvent $event) {
                Lantra::$app->users->onActivateUser($event, $event->user);
            }
        );

        Event::on(
            Entry::class,
            Entry::EVENT_BEFORE_SAVE,
            function (ModelEvent $event) {
                $entry = $event->sender;
                ## ignore cli
                if (Craft::$app->request->isConsoleRequest) {
                    return;
                }
                ## ignore drafts and revisions
                if (ElementHelper::isDraftOrRevision($entry)) {
                    return;
                }
                if ($entry->sectionId == $this->sectionIdResults) {
                    Lantra::$app->results->onBeforeSaveResult($event, $entry);
                } elseif ($entry->sectionId == $this->sectionIdModules) {
                    Lantra::$app->modules->onBeforeSaveModule($event, $entry);
                } elseif ($entry->sectionId == $this->sectionIdAttempts) {
                    Lantra::$app->results->onBeforeSaveAttempt($event, $entry);
                } elseif ($entry->sectionId == $this->sectionIdCompanies) {
                    Lantra::$app->structure->onBeforeSaveCompany($event, $entry);
                } elseif ($entry->sectionId == $this->sectionIdPackages)
                    Lantra::$app->packages->onBeforeSavePackage($event, $entry);
                elseif ($entry->sectionId == $this->sectionIdWorkflows)
                    Lantra::$app->packages->onBeforeSavePackageWorkflow($event, $entry);
            }
        );

        Event::on(
            Entry::class,
            Entry::EVENT_AFTER_SAVE,
            function (ModelEvent $event) {
                $entry = $event->sender;
                ## ignore cli
                if (Craft::$app->request->isConsoleRequest) {
                    return;
                }
                ## ignore drafts and revisions
                if (ElementHelper::isDraftOrRevision($entry)) {
                    return;
                }
                if ($entry->sectionId == $this->sectionIdCompanies) {
                    Lantra::$app->structure->onSaveCompany($event, $entry);
                } elseif ($entry->sectionId == $this->sectionIdResults) {
                    Lantra::$app->results->onSaveResult($event, $entry);
                } elseif ($entry->sectionId == $this->sectionIdAttempts) {
                    Lantra::$app->results->onSaveAttempt($event, $entry);
                } elseif ($entry->sectionId == $this->sectionIdUnits) {
                    ## add result cache unit column (if enabled)
                    Lantra::$app->results->addUnitColumn($entry->id);
                } elseif ($entry->sectionId == $this->sectionIdPackages) {
                    Lantra::$app->packages->onSavePackage($event, $entry);
                } elseif ($entry->sectionId == $this->sectionIdWorkflows) {
                    Lantra::$app->packages->onSavePackageWorkflow($event, $entry);
                }
        });

        Event::on(
            Entry::class,
            Entry::EVENT_AFTER_DELETE,
            function (Event $event) {
                $entry = $event->sender;
                ## ignore cli
                if (Craft::$app->request->isConsoleRequest) {
                    return;
                }
                ## ignore drafts and revisions
                if (ElementHelper::isDraftOrRevision($entry)) {
                    return;
                }
                if ($entry->sectionId == $this->sectionIdUnits) {
                    ## delete result cache unit column (if enabled)
                    Lantra::$app->results->removeUnitColumn($entry->id);
                }
                if ($entry->sectionId == $this->sectionIdResults) {
                    Lantra::$app->results->onDeleteResult($event, $entry);
                }
            });

        Event::on(
            Entry::class,
            Entry::EVENT_DEFINE_BEHAVIORS,
            function(DefineBehaviorsEvent $event) {
                if ($event->sender->sectionId == 15) {
                    $event->behaviors[] = PackageBehavior::class;
                }
            }
        );

        Event::on(
            User::class,
            User::EVENT_DEFINE_BEHAVIORS,
            function(DefineBehaviorsEvent $event) {
                $event->behaviors[] = UserRecordBehavior::class;
            });

        Event::on(
            UserPermissions::class,
            UserPermissions::EVENT_REGISTER_PERMISSIONS,
            function(RegisterUserPermissionsEvent $event) {
                $event->permissions['Lantra Skills Plus'] = [
                    'manageCompanies' => ['label' => 'Manage Companies'],
                    'manageTeams' => ['label' => 'Manage Teams'],
                    'manageJobRoles' => ['label' => 'Manage Job Roles'],
                    'manageModules' => ['label' => 'Manage Modules'],
                    'accessReports' => ['label' => 'Access Reports'],
                ];
            });

        ## add geo location to assets
        Event::on(
            Asset::class,
            Asset::EVENT_AFTER_SAVE,
            function (ModelEvent $event) {
                ## ignore cli
                if (Craft::$app->request->isConsoleRequest) {
                    return;
                }
                $asset = $event->sender;
                $volume = $asset->getVolume();
                ## add coordinates to evidence
                if ($volume->handle == 'evidence') {
                    Lantra::$app->evidence->onSaveEvidence($event, $asset);
                }
            }
        );
    }

    /**
     * @inheritdoc
     */
    public function getCpNavItem(): array
    {
        $ret = parent::getCpNavItem();
        $ret['url'] = 'sp';
        $ret['label'] = 'Lantra Skills+';
        $ret['subnav'] = [
            'settings' => ['label' => 'Settings', 'url' => 'sp/settings'],
            'notifications' => ['label' => 'Notifications', 'url' => 'sp/notifications'],
            'import' => ['label' => 'Import', 'url' => 'sp/import'],
            'tools' => ['label' => 'Tools', 'url' => 'sp/tools'],
            'queue' => ['label' => 'Queue', 'url' => 'sp/queue']
        ];
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
        Craft::$app->getResponse()->redirect(UrlHelper::cpUrl('sp/settings'));
    }

    /**
     * @return array
     */
    private function getCpUrlRules()
    {
        return [
            'sp'                                => 'sp/cp/settings/index',
            'sp/settings'                       => 'sp/cp/settings/index',
            'sp/notifications'                  => 'sp/cp/settings/notifications',
            'sp/queue'                          => 'sp/cp/settings/queue',
            'sp/cache'                          => 'sp/cp/settings/cache',
            'sp/tools'                          => 'sp/cp/tools',
            'sp/import'                         => 'sp/cp/import/index',
            'sp/queue/delete-job'               => 'sp/cp/settings/delete-job',
        ];
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

            ## internal assets
            'internal/<assetId>'                        => 'sp/assets/internal',

            ## cpd routes

            ## taskbook routes
            'cpd/<userId>/taskbooks'                    => ['template' => 'record/index'],
            'cpd/<userId>/taskbooks/manage'             => ['template' => 'record/_taskbooks/manage'],
            'cpd/<userId>/taskbooks/new'                => ['template' => 'record/_taskbooks/new'],
            'cpd/<userId>/taskbooks/single'             => ['template' => 'record/_taskbooks/single'],
            'cpd/<userId>/taskbooks/<entryId>'          => ['template' => 'record/index'],
            'cpd/<userId>/taskbooks/<entryId>/pay'      => ['template' => 'record/index'],

            'profile'                                   => ['template' => 'profile/index'],
            'cpd/<userId>/achievement/<entryId>'        => ['template' => 'record/achievement'],
            'cpd/<userId>/result/<entryId>'             => ['template' => 'record/achievement'],
            'cpd/<userId>/<moduleId>/<unitId>/add'      => ['template' => 'record/unit'],
            'cpd/<userId>/<moduleId>/<unitId>/test'     => ['template' => 'record/unit'],
            'cpd/<userId>/<moduleId>/<unitId>/<resultId>'   => ['template' => 'record/unit'],
            'cpd/<userId>/<moduleId>/<unitId>'          => ['template' => 'record/unit'],
            'cpd/<userId>/archive'                      => ['template' => 'record/index'],
            'cpd/<userId>/print'                        => ['template' => 'record/index'],
            'cpd/<userId>'                              => ['template' => 'record/index'],
            'result/<resultId>'                         => ['template' => 'result/_form'],

            ## taskbook review
            'management/taskbooks/manage/<elementId>/review' => ['template' => 'management/taskbooks/manage'],
            'management/taskbooks/manage/<elementId>'   => ['template' => 'management/taskbooks/manage'],

            ## management routes
            'management/<section>/edit/<elementId>'     => ['template' => 'management/index'],
            'management/<section>/new'                  => ['template' => 'management/index'],
            'management/users/company/<companyId>'      => ['template' => 'management/users'],

            ## reporting routes
            'reporting/custom/edit/<reportId>'          => ['template' => 'reporting/custom/_form'],
            'reporting/custom/new'                      => ['template' => 'reporting/custom/_form'],
            'reporting/user/<userId>'                   => ['template' => 'reporting/user'],
            'reporting/standard/<reportSlug>'           => ['template' => 'reporting/standard'],

            ## action routes
            'sp/users/hierarchy'                        => 'sp/users/hierarchy',
            'sp/users/refresh-hierarchy'                => 'sp/users/refresh-hierarchy',
            'sp/users/suspend-user'                     => 'sp/users/suspend-user',
            'sp/users/delete-user'                      => 'sp/users/delete-user',
            'sp/users/restore-user'                     => 'sp/users/restore-user',
            'sp/users/company-managers'                 => 'sp/users/company-managers',
            'sp/users/save-user'                        => 'sp/users/save-user',

            'sp/results/refresh'                        => 'sp/users/refresh-results',

            'sp/entries/reset-result'                   => 'sp/entries/reset-result',
            'sp/entries/delete-entry'                   => 'sp/entries/delete-entry',
            'sp/entries/endorse-evidence'               => 'sp/entries/endorse-evidence',
            'sp/entries/pending-result'                 => 'sp/entries/pending-result',

            'sp/packages/request-assessment'            => 'sp/packages/request-assessment',
            'sp/packages/update-package'                => 'sp/packages/update-package',
            'sp/packages/pay/<packageId>'               => 'sp/packages/pay',
            'sp/packages/payments'                      => 'sp/packages/payments',

            'sp/categories/delete-category'             => 'sp/categories/delete-category',

            'sp/reports/save-report'                    => 'sp/reports/save-report',
            'sp/reports/delete-report'                  => 'sp/reports/delete-report',
            'sp/reports/run-report'                     => 'sp/reports/run-report',
            'sp/reports/standard-report/<reportType>'   => 'sp/reports/standard-report',

            'sp/assets/delete-evidence'                 => 'sp/assets/delete-evidence',
            'sp/assets/upload-evidence'                 => 'sp/assets/upload-evidence',

            'sp/paypal/ipn'                             => 'sp/paypal/ipn',
        ];
    }

    /**
     *
     */
    private function _runMigrations()
    {
        $exists = (new Query())->from('{{%stc_columnlayout}}')->where(['field_fieldType' => 'unitValue'])->count();
        if ($exists) {
            $migration = new m200128_160852_rename_unitValue();
            $migration->safeUp();
        }
    }
}

