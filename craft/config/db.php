<?php
/**
 * Database Configuration
 *
 * All of your system's database connection settings go in here. You can see a
 * list of the available settings in vendor/craftcms/cms/src/config/DbConfig.php.
 *
 * @see craft\config\DbConfig
 */

$dbServerProd = '10.1.4.4';
$dbServerDev = '10.1.4.6';

## these are defined here so they can be used in db copy
define('DB_HOST_PROD', $dbServerProd);
define('DB_HOST_DEV', $dbServerDev);

## switch db server if on prod
$environment = getenv('ENVIRONMENT');
$dbServer = $environment == 'prod' ? $dbServerProd : $dbServerDev;

$dbPort = '3306';

return [
    '*' => [
        'driver' => 'mysql',
        'user' => getenv('DB_USER'),
        'password' => getenv('DB_PASSWORD'),
        'schema' => 'public',
        'tablePrefix' => 'craft',
        'port' => $dbPort
    ],
    'cbd' => [
        'server' => 'localhost',
        'database' => getenv('DB_DATABASE'),
    ],
    'jason' => [
        'server' => 'localhost',
        'database' => getenv('DB_DATABASE'),
    ],
    'dev' => [
        'server' => $dbServer,
        'database' => 'dev-' . getenv('SITE'),
    ],
    'uat' => [
        'server' => $dbServer,
        'database' => 'uat-' . getenv('SITE'),
    ],
    'prod' => [
        'server' => $dbServer,
        'database' => 'prod-' . getenv('SITE'),
    ]
];
