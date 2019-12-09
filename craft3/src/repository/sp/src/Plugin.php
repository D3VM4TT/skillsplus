<?php
/**
 * Lantra Skills Plus for Craft CMS 3.x
 *
 * @link      https://coffeebean.design
 * @copyright Copyright (c) 2020 Coffee Bean Design
 */

namespace lantra\sp;

use Craft;
use craft\base\Plugin as BasePlugin;

use lantra\sp\services\App;

/**
 * Class LantraPlugin
 * @package Craft
 */
class Plugin extends BasePlugin
{
    /**
     * @var \lantra\sp\services\App The application instance.
     */
    public static $app;

    public $schemaVersion = '0.0.1';

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();

        $this->setComponents([
            'app' => App::class
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getCpNavItem(): array
    {
    }

    /**
     * @inheritdoc
     */
    protected function createSettingsModel()
    {
        return new Settings();
    }

    /**
     * @return mixed|\yii\web\Response
     */
    public function getSettingsResponse()
    {
    }

    /**
     * @return array
     */
    private function getCpUrlRules()
    {
    }

    /**
     * @return array
     */
    private function getSiteUrlRules()
    {
    }
}

