<?php
/**
 * Lantra Skills Plus for Craft CMS 3.x
 *
 * @link      https://coffeebean.design
 * @copyright Copyright (c) 2020 Coffee Bean Design
 */

namespace lantra\sp\helpers;

use lantra\sp\Plugin;
use craft\helpers\FileHelper;

class LantraHelper
{
    /**
     *
     * @return string
     */
    public static function getRelease()
    {
        $release = file_get_contents(CRAFT_BASE_PATH . '/config/.release');
        return $release ? $release : 'unknown';
    }

    /**
     *
     * @return string
     */
    public static function getSettings()
    {
        return Plugin::getInstance()->getSettings();
    }

    /**
     * @param string $key
     * @return mixed|null
     */
    public static function setting($key = '')
    {
        $settings = Plugin::getInstance()->getSettings();
        return isset($settings[$key]) ? $settings[$key] : null;
    }
}