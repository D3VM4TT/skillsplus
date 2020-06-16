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
use lantra\sp\records\Settings as SettingsRecord;

class Settings extends Component
{
    /**
     * @param array $settings
     * @return int
     * @throws \yii\db\Exception
     */
    public function saveSettings($settings)
    {
        $count = 0;
        if (is_countable($settings)) {
            foreach ($settings as $key => $value) {
                if ($this->saveSetting($key, $value)) {
                    $count++;
                }
            }
        }
        return $count;
    }

    /**
     * @return mixed
     */
    public function getSettings()
    {
        return Lantra::getInstance()->getSettings();

    }

    /**
     * @return array
     */
    public function getDbSettings()
    {
        $settings = [];
        foreach(SettingsRecord::find()->all() as $row) {
            $settings[$row->key] = JsonHelper::decodeIfJson($row->value);
        }
        return $settings;
    }

    /**
     * @param $key
     * @param null $value
     * @return \yii\db\DataReader
     * @throws \yii\db\Exception
     */
    public function saveSetting($key, $value = null)
    {
        $value = is_array($value) ? JsonHelper::encode($value) : $value;
        return Craft::$app->db->createCommand()->upsert('{{%lantra_settings}}', ['value' => $value, 'key' => $key], ['value' => $value])->query();
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
        if ($key == 'schemeLogo') {
            return $settings['schemeLogo'] ? $settings['schemeLogo'][0] : null;
        }
        if ($key == 'defaultWorkflow') {
            return $settings['defaultWorkflow'] ? $settings['defaultWorkflow'][0] : null;
        }
        if ($key == 'taskbookIntro') {
            return $settings['taskbookIntro'] ? $settings['taskbookIntro'][0] : null;
        }
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