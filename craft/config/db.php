<?php
/**
 * Database Configuration
 *
 * All of your system's database connection settings go in here. You can see a
 * list of the available settings in vendor/craftcms/cms/src/config/DbConfig.php.
 *
 * @see craft\config\DbConfig
 */

// $environment = getenv('ENVIRONMENT');
// $dbServer = $environment == 'prod' ? '10.1.4.4' : '10.1.4.6';
$dbPort = '3306';
$dbServer = '10.1.4.6';

define('DB_SERVER', $dbServer);
define('DB_PORT', $dbPort);

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
    'local' => [
        'server' => getenv('DB_SERVER'),
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
