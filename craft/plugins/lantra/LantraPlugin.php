<?php

namespace Craft;

class LantraPlugin extends BasePlugin
{
    function getName()
    {
        return Craft::t('Lantra Tools');
    }

    function getVersion()
    {
        return '0.0.1';
    }

    function getDeveloper()
    {
        return 'Traffic Marketing';
    }

    function getDeveloperUrl()
    {
        return 'http://thisistraffic.co';
    }

    public function init()
    {

    }
}
