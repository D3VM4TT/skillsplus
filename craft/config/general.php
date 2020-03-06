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
        'elevatedSessionDuration' => 0,
        'enableCsrfProtection' => false,
        'defaultWeekStartDay' => 0,
        'omitScriptNameInUrls' => true,
        'cpTrigger' => 'admin',
        'devMode' => false,
        'allowAdminChanges' => true,
        'loginPath' => '/public',
        'setPasswordPath' => '/public/password/set',
        'setPasswordSuccessPath' => '/',
        'useEmailAsUsername' => false,
        'securityKey' => getenv('SECURITY_KEY'),
        'useProjectConfigFile' => false,
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
