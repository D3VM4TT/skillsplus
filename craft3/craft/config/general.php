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
        'environmentVariables' => array(
            'basePath' => '/datadisk/sites/' . $site . '/',
            'assetsPath' => '/datadisk/azureshare/' . $site . '/',
            'server' => 'prod',
            'site' => $site,
        ),
    ],
    'local' => [
        'siteUrl' => 'http://craft3.skills-plus.local',
        'devMode' => true,
        'environmentVariables' => array(
            'basePath' => '/websites/skills-plus.net/craft3',
            'assetsPath' => '/websites/skills-plus.net/craft3/',
            'server' => 'local',
        ),
    ],
    'dev' => [
        'devMode' => true,
        'environmentVariables' => array(
            'server' => 'dev',
            'site' => $site
        ),
    ],
    'uat' => [
        'devMode' => true,
        'environmentVariables' => array(
            'server' => 'uat',
            'site' => $site
        ),
        'allowAdminChanges' => false,
    ],
    'prod' => [
        'devMode' => true,
        'environmentVariables' => array(
            'server' => 'uat',
            'site' => $site
        ),
        'allowAdminChanges' => false,
    ],
];
