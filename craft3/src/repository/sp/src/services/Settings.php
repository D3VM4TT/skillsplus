<?php
/**
 * Lantra Skills Plus for Craft CMS 3.x
 *
 * @link      https://coffeebean.design
 * @copyright Copyright (c) 2020 Coffee Bean Design
 */

namespace lantra\sp\services;

use Craft;
use craft\base\Component;
use craft\helpers\Json as JsonHelper;

use lantra\sp\Plugin as Lantra;
use lantra\sp\helpers\LantraHelper;

class Settings extends Component
{
    /**
     * @param $settings
     * @return bool
     */
    public function saveSettings($settings)
    {
        $settings = JsonHelper::encode($settings);
        $affectedRows = Craft::$app->db->createCommand()->update('plugins', ['settings' => $settings], ['class' => 'Lantra']);
        return (bool)$affectedRows;
    }

    /**
     * @return array
     */
    public function getDbSettings()
    {
        $result = Craft::$app->db->createCommand()
            ->select('settings')
            ->from('plugins')
            ->where(['class' => 'Lantra'])
            ->queryRow();
        return $result ? JsonHelper::decode($result['settings']) : [];
    }

    /**
     * @return mixed
     */
    public function getSettings()
    {
        return Lantra::getInstance()->getSettings();
    }

    /**
     * @param $key
     * @param null $value
     * @return bool
     */
    public function saveSetting($key, $value = null)
    {
        $settings = $this->getSettings();
        $settings[$key] = $value;
        return $this->saveSettings($settings);
    }

    /**
     * @param $key
     * @param null $default
     * @return mixed|null
     */
    public function getSetting($key, $default = null)
    {
        if ($key == 'jsDateFormat') {
            return $this->getJsDateFormat();
        }
        $settings = $this->getSettings();
        return isset($settings[$key]) ? $settings[$key] : $default;
    }

    /**
     * @return mixed
     */
    public function getJsDateFormat()
    {
        $dateFormat = $this->getSetting('themeDateFormat', 'd-m-Y');
        $p = ['d', 'm', 'Y'];
        $j = ['dd', 'mm', 'yyyy'];
        return str_replace($p, $j, $dateFormat);
    }

    /**
     * @param $key
     * @throws \yii\db\Exception
     */
    public function resetDataClean($key)
    {
        $key = 'field_dataClean' . $key;
        $mysql = "UPDATE craft_content SET `" . $key . "` = '0' WHERE `" . $key . "` = '1'";
        Craft::$app->db->createCommand($mysql)->query();
    }
}