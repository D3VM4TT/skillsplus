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
        'devMode' => false,
        'loginPath' => '/public',
        'setPasswordPath' => '/public/password/set',
        'setPasswordSuccessPath' => '/',
        'useEmailAsUsername' => false,
        'autoLoginAfterAccountActivation' => true,
        'phpMaxMemoryLimit' => '4096M',
        'environmentVariables' => array(
            'basePath' => '/datadisk/sites/' . $site . '/',
        ),
    ),
    /* local server */
    'cpd.lantra.local' => array(
        'devMode' => true,
        'siteUrl' => 'http://cpd.lantra.local',
        'environmentVariables' => array(
            'basePath' => '/websites/cpd.lantra.co.uk/'
        ),
    ),
    /* demo server */
    'splusdev.ukwest.cloudapp.azure.com' => array(
        'devMode' => true,
    ),
    /* dev server */
    'newdev.skills-plus.net' => array(
        'devMode' => true,
    ),
    /* uat server */
    'newuat.skills-plus.net' => array(
        'devMode' => false,
    ),
    /* stg server */
    'newstg.skills-plus.net' => array(
        'devMode' => false,
    )
);
