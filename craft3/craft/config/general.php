<?php
/**
 * General Configuration
 *
 * All of your system's general configuration settings go in here. You can see a
 * list of the available settings in vendor/craftcms/cms/src/config/GeneralConfig.php.
 *
 * @see \craft\config\GeneralConfig
 */

return [
    '*' => [
        'defaultWeekStartDay' => 1,
        'omitScriptNameInUrls' => true,
        'cpTrigger' => 'admin',
        'securityKey' => getenv('SECURITY_KEY'),
        'useProjectConfigFile' => false,
        'backupOnUpdate' => false
    ],
    'local' => [
        'devMode' => true,
    ],
    'dev' => [
        'devMode' => true,
    ],
    'uat' => [
        'allowAdminChanges' => false,
    ],
    'prod' => [
        'allowAdminChanges' => false,
    ],
];
