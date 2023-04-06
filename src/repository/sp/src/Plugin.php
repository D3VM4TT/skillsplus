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
use craft\elements\Category;
use craft\elements\User;
use craft\elements\Entry;
use craft\events\AuthenticateUserEvent;
use craft\events\ModelEvent;
use craft\events\RegisterUrlRulesEvent;
use craft\events\RegisterUserPermissionsEvent;
use craft\events\TemplateEvent;
use craft\events\DefineBehaviorsEvent;
use craft\events\UserEvent;
use craft\events\UserAssignGroupEvent;
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
use yii\web\User as YiiUser;
use yii\web\UserEvent as YiiUserEvent;

use lantra\sp\Plugin as Lantra;
use lantra\sp\behaviors\PackageBehavior;
use lantra\sp\behaviors\ModuleBehavior;
use lantra\sp\behaviors\ModuleGroupBehavior;
use lantra\sp\behaviors\UserBehavior;
use lantra\sp\behaviors\TaskbookBehavior;
use lantra\sp\behaviors\MagicTitleBehavior;
use lantra\sp\helpers\LantraHelper;
use lantra\sp\services\App;
use lantra\sp\models\Settings;
use lantra\sp\variables\LantraVariable;
use lantra\sp\assetbundles\SpCpAsset;

use lantra\spbase\services\SpBase;

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
    public $hasCpSection = true;

    public $schemaVersion = '0.0.1';

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
        $fileTarget = new FileTarget([
            'logVars' => [],
            'logFile' => '@storage/logs/lantra.log',
            'categories' => ['lantra\sp\*']
        ]);
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
            YiiUser::class,
            YiiUser::EVENT_BEFORE_LOGIN,
            function (YiiUserEvent $event) {
                $user = User::findOne($event->identity->id);
                Lantra::$app->users->onBeforeLoginUser($event, $user);
            }
        );

        Event::on(
            YiiUser::class,
            YiiUser::EVENT_AFTER_LOGIN,
            function (YiiUserEvent $event) {
                $user = User::findOne($event->identity->id);
                Lantra::$app->users->onAfterLoginUser($event, $user);
            }
        );

        Event::on(
            User::class,
            User::EVENT_AFTER_SAVE,
            function (ModelEvent $event) {
                $user = $event->sender;
                ## gah..! preparse field makes all elements save twice
                foreach (debug_backtrace(2, 12) as $trace) {
                    if (isset($trace['class']) && $trace['class'] == 'besteadfast\preparsefield\PreparseField') {
                        return;
                    }
                }
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
            User::class,
            User::EVENT_AFTER_DELETE,
            function (Event $event) {
                $user = $event->sender;
                Lantra::$app->users->onAfterDeleteUser($user, $event);
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
            Users::class,
            Users::EVENT_AFTER_ASSIGN_USER_TO_DEFAULT_GROUP,
            function(UserAssignGroupEvent $event) {
                Lantra::$app->users->onAssignUser($event, $event->user);
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
                if ($entry->sectionId == $this->sectionId('results')) {
                    Lantra::$app->results->onBeforeSaveResult($event, $entry);
                } elseif ($entry->sectionId == $this->sectionId('modules')) {
                    Lantra::$app->modules->onBeforeSaveModule($event, $entry);
                } elseif ($entry->sectionId == $this->sectionId('attempts')) {
                    Lantra::$app->results->onBeforeSaveAttempt($event, $entry);
                } elseif ($entry->sectionId == $this->sectionId('companies')) {
                    Lantra::$app->structure->onBeforeSaveCompany($event, $entry);
                } elseif ($entry->sectionId == $this->sectionId('packages'))
                    Lantra::$app->packages->onBeforeSavePackage($event, $entry);
                elseif ($entry->sectionId == $this->sectionId('workflows'))
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
                if ($entry->sectionId == $this->sectionId('companies')) {
                    Lantra::$app->structure->onSaveCompany($event, $entry);
                } elseif ($entry->sectionId == $this->sectionId('results')) {
                    Lantra::$app->results->onSaveResult($event, $entry);
                } elseif ($entry->sectionId == $this->sectionId('attempts')) {
                    Lantra::$app->results->onSaveAttempt($event, $entry);
                } elseif ($entry->sectionId == $this->sectionId('modules')) {
                    Lantra::$app->modules->onSaveModule($event, $entry);
                } elseif ($entry->sectionId == $this->sectionId('units')) {
                    ## add result cache unit column (if enabled)
                    Lantra::$app->results->addUnitColumn($entry->id);
                } elseif ($entry->sectionId == $this->sectionId('packages')) {
                    Lantra::$app->packages->onSavePackage($event, $entry);
                } elseif ($entry->sectionId == $this->sectionId('workflows')) {
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
                if ($entry->sectionId == $this->sectionId('units')) {
                    ## delete result cache unit column (if enabled)
                    Lantra::$app->results->removeUnitColumn($entry->id);
                }
                if ($entry->sectionId == $this->sectionId('results')) {
                    Lantra::$app->results->onDeleteResult($event, $entry);
                }
                if ($entry->sectionId == $this->sectionId('companies')) {
                    Lantra::$app->structure->onDeleteCompany($event, $entry);
                }
            });

        Event::on(
            Entry::class,
            Entry::EVENT_DEFINE_BEHAVIORS,
            function(DefineBehaviorsEvent $event) {
                if ($event->sender->sectionId == $this->sectionId('packages')) {
                    $event->behaviors[] = PackageBehavior::class;
                }
                if ($event->sender->sectionId == $this->sectionId('modules')) {
                    $event->behaviors[] = ModuleBehavior::class;
                    $event->behaviors[] = MagicTitleBehavior::class;
                }
                if ($event->sender->sectionId == $this->sectionId('taskbooks')) {
                    $event->behaviors[] = TaskbookBehavior::class;
                    $event->behaviors[] = MagicTitleBehavior::class;
                }
                if ($event->sender->sectionId == $this->sectionId('units')) {
                    $event->behaviors[] = MagicTitleBehavior::class;
                }
            }
        );

        Event::on(
            Category::class,
            Category::EVENT_DEFINE_BEHAVIORS,
            function(DefineBehaviorsEvent $event) {
                if ($event->sender->groupId == $this->groupId('moduleGroups')) {
                    $event->behaviors[] = ModuleGroupBehavior::class;
                    $event->behaviors[] = MagicTitleBehavior::class;
                }
            }
        );

        Event::on(
            User::class,
            User::EVENT_DEFINE_BEHAVIORS,
            function(DefineBehaviorsEvent $event) {
                $event->behaviors[] = UserBehavior::class;
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

        ## check for maintenance mode
        if (Craft::$app->request->isSiteRequest && Lantra::$app->settings->getSetting('maintenanceMode')) {
            $this->maintenanceMode();
        }
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
            'queue' => ['label' => 'Queue', 'url' => 'sp/queue'],
            'spbase' => ['label' => 'Base', 'url' => 'sp/spbase']
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
            'sp/spbase'                         => 'sp/cp/spbase',
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
            'public/certificate/<section>/<userId>/<resultId>'    => ['template' => 'public/certificate'],
            'public/passport/<userId>'                  => ['template' => 'public/passport'],
            'public/licence/thanks'                     => ['template' => 'public/licence'],

            ## membership routes
            'public/register/membership/thanks'         => ['template' => 'public/register/membership'],

            ## internal assets
            'internal/<assetId>'                        => 'sp/assets/internal',

            ## cpd routes

            ## product routes
            'cpd/<userId>/products'                            => ['template' => 'record/index'],

            ## taskbook routes
            'cpd/<userId>/taskbooks'                            => ['template' => 'record/index'],
            'cpd/<userId>/taskbooks/manage'                     => ['template' => 'record/_taskbooks/manage'],
            'cpd/<userId>/taskbooks/new'                        => ['template' => 'record/_taskbooks/new'],
            'cpd/<userId>/taskbooks/single'                     => ['template' => 'record/_taskbooks/single'],
            'cpd/<userId>/taskbooks/<entryId>'                  => ['template' => 'record/index'],
            'cpd/<userId>/taskbooks/<entryId>/<moduleGroupId>'  => ['template' => 'record/index'],
            'cpd/<userId>/taskbooks/<entryId>/pay'              => ['template' => 'record/index'],

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
            'management/companies/results/<companyId>'  => ['template' => 'management/companies/results'],
            'management/<section>/edit/<elementId>'     => ['template' => 'management/index'],
            'management/<section>/new'                  => ['template' => 'management/index'],
            'management/users/company/<companyId>'      => ['template' => 'management/users'],
            'management/products/results/<productId>'   => ['template' => 'management/products/results'],
            'management/skills/matrix/<moduleId>'       => ['template' => 'management/skills/matrix'],
            'management/skills/matrix/<moduleId>/<tableView>' => ['template' => 'management/skills/matrix'],

            ## reporting routes
            'reporting/edit/<reportId>'                 => ['template' => 'reporting/_form'],
            'reporting/data/<reportId>'                 => ['template' => 'reporting/_data'],
            'reporting/new'                             => ['template' => 'reporting/_form'],
            'reporting/user/<userId>'                   => ['template' => 'reporting/user'],

            ## action routes
            'sp/users/hierarchy'                        => 'sp/users/hierarchy',
            'sp/users/refresh-hierarchy'                => 'sp/users/refresh-hierarchy',
            'sp/users/suspend-user'                     => 'sp/users/suspend-user',
            'sp/users/delete-user'                      => 'sp/users/delete-user',
            'sp/users/restore-user'                     => 'sp/users/restore-user',
            'sp/users/company-managers'                 => 'sp/users/company-managers',
            'sp/users/save-user'                        => 'sp/users/save-user',
            'sp/users/privacy-confirm'                  => 'sp/users/privacy-confirm',
            'sp/users/switch-user'                      => 'sp/users/switch-user',

            'sp/results/refresh'                        => 'sp/users/refresh-results',

            'sp/entries/reset-result'                   => 'sp/entries/reset-result',
            'sp/entries/delete-entry'                   => 'sp/entries/delete-entry',
            'sp/entries/endorse-evidence'               => 'sp/entries/endorse-evidence',
            'sp/entries/pending-result'                 => 'sp/entries/pending-result',

            'sp/packages/request-assessment'            => 'sp/packages/request-assessment',
            'sp/packages/update-package'                => 'sp/packages/update-package',
            'sp/packages/reset-package/<entryId>'       => 'sp/packages/reset-package',
            'sp/packages/export-package/<entryId>'      => 'sp/packages/export-package',
            'sp/packages/delete-results'                => 'sp/packages/delete-results',
            'sp/packages/load-template'                 => 'sp/packages/load-template',
            'sp/packages/external-status'               => 'sp/packages/external-status',

            'sp/categories/delete-category'             => 'sp/categories/delete-category',

            'sp/reports/save-report'                    => 'sp/reports/save-report',
            'sp/reports/delete-report'                  => 'sp/reports/delete-report',
            'sp/reports/run-custom-report/<entryId>'    => 'sp/reports/run-custom-report',
            'sp/reports/download-report/<ext>/<entryId>'=> 'sp/reports/download-report',

            'sp/assets/delete-evidence'                 => 'sp/assets/delete-evidence',
            'sp/assets/upload-evidence'                 => 'sp/assets/upload-evidence',
            'sp/assets/browse-evidence'                 => 'sp/assets/browse-evidence',
            'sp/packages/browse-packages'                 => 'sp/packages/browse-packages',

            'sp/paypal/process/<userId>/<paymentId>'    => 'sp/paypal/process',
        ];
    }

    /**
     * @param $handle
     * @return null
     */
    private function sectionId($handle)
    {
        return LantraHelper::sectionId($handle);
    }

    /**
     * @param $handle
     * @return null
     */
    private function groupId($handle)
    {
        return LantraHelper::groupId($handle);
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    protected function maintenanceMode()
    {
        $maintenanceUrl = '/503';
        if (Craft::$app->request->getUrl() != $maintenanceUrl) {
            Craft::$app->response->redirect($maintenanceUrl);
        }
    }
}

