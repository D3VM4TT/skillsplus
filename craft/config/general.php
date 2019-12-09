<?php

$parts = explode('.', $_SERVER['HTTP_HOST']);
$site = array_shift($parts);

return array(
    /* all environments */
    '*' => array(
        'backupDbOnUpdate'  => false,
        'defaultWeekStartDay' => 0,
        'enableCsrfProtection' => false,
        'omitScriptNameInUrls' => true,
        'cpTrigger' => 'admin',
        'devMode' => false,
        'loginPath' => '/public',
        'setPasswordPath' => '/public/password/set',
        'setPasswordSuccessPath' => '/',
        'useEmailAsUsername' => false,
        'autoLoginAfterAccountActivation' => true,
        'phpMaxMemoryLimit' => '4096M',
        'maxUploadFileSize' => '2147483648',
        'environmentVariables' => array(
            'basePath' => '/datadisk/sites/' . $site . '/',
            'assetsPath' => '/datadisk/azureshare/' . $site . '/',
            'server' => 'prod',
            'site' => $site,
        ),
    ),
    /* local server */
    'skills-plus.local' => array(
        'siteUrl' => 'http://craft2.skills-plus.net',
        'devMode' => true,
        'environmentVariables' => array(
            'basePath' => '/websites/skills-plus.net/',
            'assetsPath' => '/websites/skills-plus.net/craft-assets/',
            'server' => 'local',
        ),
    ),
    /* demo server */
    'splusdev.ukwest.cloudapp.azure.com' => array(
        'devMode' => true,
        'environmentVariables' => array(
            'server' => 'demo',
        ),
    ),
    /* dev server */
    'newdev.skills-plus.net' => array(
        'devMode' => true,
        'environmentVariables' => array(
            'server' => 'dev',
        ),
    ),
    /* uat server */
    'newuat.skills-plus.net' => array(
        'environmentVariables' => array(
            'server' => 'uat',
        ),
    ),
    /* stg server */
    'newstg.skills-plus.net' => array(
        'environmentVariables' => array(
            'server' => 'stg',
        ),
    ),
    /* prod server */
    'newprod.skills-plus.net' => array(
        'environmentVariables' => array(
            'server' => 'prod',
        ),
    ),
);
