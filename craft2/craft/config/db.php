<?php

$parts = explode('.', $_SERVER['HTTP_HOST']);
$site = array_shift($parts);

return array(
    /* all environments */
    '*' => array(
        'tablePrefix' => 'craft',
        'server' => '10.1.4.6',
        'port' => '3306',
        'user' => 'craft',
        'password' => 'RIrYmX!rFp2fxPRT%yu4',
        // cleansed database for cleansed data
        'cleansedServer' => '10.1.4.6',
        'cleansedUser' => 'craft',
        'cleansedPassword' => 'RIrYmX!rFp2fxPRT%yu4',
        'database' => 'prod-' . $site,
    ),
    /* local dev server */
    'craft2.skills-plus.local' => array(
        'server' => 'localhost',
        'database' => 'skills-plus-test',
        'user' => 'root',
        'password' => 'root',
        // cleansed database for cleansed data
        'cleansedServer' => 'localhost',
        'cleansedDatabase' => 'cpd_clone',
        'cleansedUser' => 'root',
        'cleansedPassword' => 'root',
    ),
    /* lantra demo server */
    'splusdev.ukwest.cloudapp.azure.com' => array(
        'database' => 'craft',
    ),
    /* dev server */
    'newdev.skills-plus.net' => array(
        'database' => 'dev-' . $site,
    ),
    /* stg server */
    'newstg.skills-plus.net' => array(
        'database' => 'stg-' . $site,
    ),
    /* uat server */
    'newuat.skills-plus.net' => array(
        'database' => 'uat-' . $site,
    ),
);
