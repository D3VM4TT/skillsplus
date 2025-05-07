<?php
/**
 * General Configuration
 *
 * All of your system's general configuration settings go in here. You can see a
 * list of the available settings in vendor/craftcms/cms/src/config/GeneralConfig.php.
 *
 * @see \craft\config\GeneralConfig
 */

/*
 * TODO: Update general config
 * TODO: Add to github traffic developers
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
        'securityKey' => getenv('SECURITY_KEY'),
        'backupOnUpdate' => false,
        'autoLoginAfterAccountActivation' => true,
        'userSessionDuration' => 7200,
        'preserveExifData' => true,
        'phpMaxMemoryLimit' => '4096M',
        'maxUploadFileSize' => '2147483648',
        'aliases' => [
            '@basePath' => '/datadisk/sites/' . getenv('SITE') . '/',
            '@assetsPath' => '/datadisk/azureshare/' . getenv('SITE') . '/',
            '@server' => getenv('ENVIRONMENT'),
            '@site' => getenv('SITE'),
            '@spBaseUrl' => 'https://base.skills-plus.net'
        ],
    ],
    'cbd' => [
        'devMode' => true,
        'allowAdminChanges' => true,
        'aliases' => [
            '@basePath' => '/var/www/sp.coffeebean.design',
            '@assetsPath' => '/var/www/sp.coffeebean.design/craft-assets/',
            '@spBaseUrl' => 'https://base.coffeebean.design'
        ],
    ],
    'local' => [
        'devMode' => true,
        'allowAdminChanges' => true,
        'aliases' => [
            '@basePath' => '/app/',
            '@assetsPath' => '/app/craft-assets/',
            '@spBaseUrl' => 'https://base.coffeebean.design'
        ],
    ],
    'jason' => [
        'devMode' => true,
        'allowAdminChanges' => true,
        'aliases' => [
            '@basePath' => 'user/sites/cpd.lantra.co.uk/',
            '@assetsPath' => 'user/sites/cpd.lantra.co.uk/craft-assets/',
        ],
    ],
    'dev' => [
        'devMode' => true,
        'allowAdminChanges' => true,
        'aliases' => [
            '@spBaseUrl' => 'https://base.coffeebean.design'
        ]
    ],
    'uat' => [
        'aliases' => [
            '@spBaseUrl' => 'http://base.newuat.skills-plus.net'
        ]
    ],
    'prod' => [
    ],
];
