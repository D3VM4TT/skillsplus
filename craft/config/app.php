<?php
/**
 * Yii Application Config
 *
 * Edit this file at your own risk!
 *
 * The array returned by this file will get merged with
 * vendor/craftcms/cms/src/config/app.php and app.[web|console].php, when
 * Craft's bootstrap script is defining the configuration for the entire
 * application.
 *
 * You can define custom modules and system components, and even override the
 * built-in system components.
 *
 * If you want to modify the application config for *only* web requests or
 * *only* console requests, create an app.web.php or app.console.php file in
 * your config/ folder, alongside this one.
 */

use craft\helpers\App;
use lantra\sp\helpers\LantraHelper;

return [
    '*' => [

    ],
    'local' => [
        'components' => [
            'mailer' => function() {
                $settings = App::mailSettings();
                $settings->fromEmail = LantraHelper::setting('notifyFromEmail', 'No-Reply@skills-plus.net');
                $settings->fromName = LantraHelper::setting('notifyFromName', 'Skills Plus');
                $settings->transportType = \craft\mail\transportadapters\Gmail::class;
                $settings->transportSettings = [
                    'username'  => getenv('SMTP_USERNAME'),
                    'password'  => getenv('SMTP_PASSWORD')
                ];
                $config = App::mailerConfig($settings);
                return Craft::createObject($config);
            },
            'mutex' => function() {
                $config = craft\helpers\App::mutexConfig();
                $config['isWindows'] = getenv('ENVIRONMENT') == 'local';
                return Craft::createObject($config);
            },
            'dbCleansed' => [
                'class' => craft\db\Connection::class,
                'driver' => getenv('DB_DRIVER'),
                'schema' => getenv('DB_SCHEMA'),
                'tablePrefix' => getenv('DB_TABLE_PREFIX'),
                'port' => getenv('DB_PORT'),
                'server' => getenv('DB_CLEANSED_SERVER'),
                'username' => getenv('DB_CLEANSED_USER'),
                'password' => getenv('DB_CLEANSED_PASSWORD'),
                'database' => getenv('DB_CLEANSED_DATABASE')
            ],

        ]
    ],
];
