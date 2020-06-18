<?php
/**
 * General Configuration
 *
 * All of your system's general configuration settings go in here. You can see a
 * list of the available settings in vendor/craftcms/cms/src/config/GeneralConfig.php.
 *
 * @see \craft\config\GeneralConfig
 */

return [
    '*' => [
        'enableGql' => false,
        'elevatedSessionDuration' => 0,
        'enableCsrfProtection' => false,
        'defaultWeekStartDay' => 0,
        'omitScriptNameInUrls' => true,
        'cpTrigger' => 'admin',
        'devMode' => false,
        'allowAdminChanges' => false,
        'loginPath' => '/public',
        'setPasswordPath' => '/public/password/set',
        'setPasswordSuccessPath' => '/',
        'useEmailAsUsername' => false,
        'autoLoginAfterAccountActivation' => true,
        'securityKey' => getenv('SECURITY_KEY'),
        'useProjectConfigFile' => true,
        'backupOnUpdate' => false,
        'autoLoginAfterAccountActivation' => true,
        'phpMaxMemoryLimit' => '4096M',
        'maxUploadFileSize' => '2147483648',
        'aliases' => [
            '@basePath' => '/datadisk/sites/' . getenv('SITE') . '/',
            '@assetsPath' => '/datadisk/azureshare/' . getenv('SITE') . '/',
            '@server' => getenv('ENVIRONMENT'),
            '@site' => getenv('SITE'),
        ],
    ],
    'cbd' => [
        'siteUrl' => 'http://sp.coffeebean.design',
        'devMode' => true,
        'allowAdminChanges' => true,
        'aliases' => [
            '@basePath' => '/var/www/sp.coffeebean.design',
            '@assetsPath' => '/var/www/sp.coffeebean.design/assets/',
        ],
    ],
    'local' => [
        'siteUrl' => 'http://skills-plus.local',
        'devMode' => true,
        'allowAdminChanges' => true,
        'aliases' => [
            '@basePath' => '/websites/skills-plus.net/',
            '@assetsPath' => '/websites/skills-plus.net/craft-assets/',
        ],
    ],
    'dev' => [
        'devMode' => true,
        'allowAdminChanges' => true,
    ],
    'uat' => [
    ],
    'prod' => [
    ],
];
