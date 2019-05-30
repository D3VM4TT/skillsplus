<?php
namespace Craft;

class Lantra_SettingsService extends BaseApplicationComponent
{
    public function saveSettings($settings)
    {
        $settings = JsonHelper::encode($settings);
        $affectedRows = craft()->db->createCommand()->update('plugins', array('settings' => $settings), array('class' => 'Lantra'));
        return (bool)$affectedRows;
    }

    public function getSettings()
    {
        $plugin = craft()->plugins->getPlugin('lantra');
        return $plugin->getSettings();
    }

    public function saveSetting($key, $value = null)
    {
        $settings = $this->getSettings();
        $settings[$key] = $value;
        return $this->saveSettings($settings);
    }

    public function getSetting($key, $default = null)
    {
        if ($key == 'jsDateFormat') {
            return $this->getJsDateFormat();
        }
        $setting = $this->getSettings()->getAttribute($key);
        return $setting ? $setting : $default;
    }

    public function getConfig($key, $default = null)
    {
        $config = craft()->config->get('environmentVariables');
        return isset($config[$key]) ? $config[$key] : $default;
    }

    public function getJsDateFormat()
    {
        $dateFormat = $this->getSetting('themeDateFormat', 'd-m-Y');
        $p = ['d', 'm', 'Y'];
        $j = ['dd', 'mm', 'yyyy'];
        return str_replace($p, $j, $dateFormat);
    }
}