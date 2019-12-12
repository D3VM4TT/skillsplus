<?php
/**
 * General Configuration
 *
 * All of your system's general configuration settings go in here. You can see a
 * list of the available settings in vendor/craftcms/cms/src/config/GeneralConfig.php.
 *
 * @see \craft\config\GeneralConfig
 */

$parts = explode('.', $_SERVER['HTTP_HOST']);
$site = array_shift($parts);

return [
    '*' => [
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
        'securityKey' => getenv('SECURITY_KEY'),
        'useProjectConfigFile' => false,
        'backupOnUpdate' => false,
        'autoLoginAfterAccountActivation' => true,
        'phpMaxMemoryLimit' => '4096M',
        'maxUploadFileSize' => '2147483648',
        'aliases' => [
            '@basePath' => '/datadisk/sites/' . $site . '/',
            '@assetsPath' => '/datadisk/azureshare/' . $site . '/',
            '@server' => getenv('ENVIRONMENT'),
            '@site' => $site,
        ],
    ],
    'local' => [
        'siteUrl' => 'http://craft3.skills-plus.local',
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
