<?php
/**
 * Craft web bootstrap file
 */

// set codebase version
define('VERSION', '1.0');

// Set path constants
define('APP_BASE_PATH', '/datadisk/app/' . VERSION);
define('CRAFT_BASE_PATH', APP_BASE_PATH.'/craft');

define('CRAFT_VENDOR_PATH', CRAFT_BASE_PATH.'/vendor');
define('CRAFT_LOCAL_PATH', '../local');
define('CRAFT_STORAGE_PATH', CRAFT_LOCAL_PATH.'/storage');

// Load Composer's autoloader
require_once CRAFT_VENDOR_PATH.'/autoload.php';

// Load dotenv?
if (class_exists('Dotenv\Dotenv') && file_exists(APP_BASE_PATH.'/.env')) {
    Dotenv\Dotenv::create(APP_BASE_PATH)->load();
}

// Load and run Craft
define('CRAFT_ENVIRONMENT', getenv('ENVIRONMENT') ?: 'production');

// define skills-plus site
if (CRAFT_ENVIRONMENT != 'local') {
    $parts = explode('.', $_SERVER['HTTP_HOST']);
    $site = array_shift($parts);
    putenv('SITE='.$site);
}

$app = require CRAFT_VENDOR_PATH.'/craftcms/cms/bootstrap/web.php';
$app->run();
