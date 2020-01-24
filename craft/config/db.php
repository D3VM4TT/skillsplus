<?php
/**
 * Database Configuration
 *
 * All of your system's database connection settings go in here. You can see a
 * list of the available settings in vendor/craftcms/cms/src/config/DbConfig.php.
 *
 * @see craft\config\DbConfig
 */

return [
    '*' => [
        'driver' => 'mysql',
        'server' => getenv('DB_SERVER'),
        'user' => getenv('DB_USER'),
        'password' => getenv('DB_PASSWORD'),
        'schema' => 'public',
        'tablePrefix' => 'craft',
        'port' => getenv('DB_PORT')
    ],
    'local' => [
        'database' => getenv('DB_DATABASE'),
    ],
    'dev' => [
        'database' => 'dev-' . getenv('SITE'),
    ],
    'uat' => [
        'database' => 'uat-' . getenv('SITE'),
    ],
    'prod' => [
        'database' => 'prod-' . getenv('SITE'),
    ]
];
