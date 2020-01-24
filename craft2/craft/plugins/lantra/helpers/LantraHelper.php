<?php

namespace Craft;

/**
 * Class LantraHelper
 */
class LantraHelper
{
    /**
     *
     * @return string
     */
    public static function getRelease()
    {
        $release = IOHelper::getFileContents(CRAFT_CONFIG_PATH . '.release', false, true);
        return $release ? $release : 'unknown';
    }
}