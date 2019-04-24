<?php

$parts = explode('.', $_SERVER['HTTP_HOST']);
$site = array_shift($parts);

return array(
    /* all environments */
    '*' => array(
        'environmentVariables' => array(),
        'defaultWeekStartDay' => 0,
        'enableCsrfProtection' => false,
        'omitScriptNameInUrls' => true,
        'cpTrigger' => 'admin',
        'devMode' => true,
        'loginPath' => '/public',
        'setPasswordPath' => '/public/password/set',
        'setPasswordSuccessPath' => '/',
        'useEmailAsUsername' => false,
        'autoLoginAfterAccountActivation' => true,
        'phpMaxMemoryLimit' => '4096M',
        'maxUploadFileSize' => '2147483648',
        'environmentVariables' => array(
            'basePath' => '/datadisk/sites/' . $site . '/',
            'server' => 'dev',
        ),
    ),
    /* local server */
    'cpd.lantra.local' => array(
        'siteUrl' => 'http://cpd.lantra.local',
        'environmentVariables' => array(
            'basePath' => '/websites/cpd.lantra.co.uk/',
            'server' => 'local',
        ),
    ),
    /* demo server */
    'splusdev.ukwest.cloudapp.azure.com' => array(
        'environmentVariables' => array(
            'server' => 'demo',
        ),
    ),
    /* dev server */
    'newdev.skills-plus.net' => array(
        'environmentVariables' => array(
            'basePath' => '/websites/cpd.lantra.co.uk/',
            'server' => 'dev',
        ),
    ),
    /* uat server */
    'newuat.skills-plus.net' => array(
        'devMode' => false,
        'environmentVariables' => array(
            'server' => 'uat',
        ),
    ),
    /* stg server */
    'newstg.skills-plus.net' => array(
        'devMode' => false,
        'environmentVariables' => array(
            'server' => 'stg',
        ),
    ),
    /* prod server */
    'newprod.skills-plus.net' => array(
        'devMode' => false,
        'environmentVariables' => array(
            'server' => 'prod',
        ),
    ),
);
