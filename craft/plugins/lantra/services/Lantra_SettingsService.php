<?php
namespace Craft;

class Lantra_SettingsService extends BaseApplicationComponent
{
	public function saveSettings($settings)
	{
		$settings = JsonHelper::encode($settings);
		$affectedRows = craft()->db->createCommand()->update('plugins', array('settings' => $settings), array('class' => 'Lantra'));
		return (bool) $affectedRows;
	}

    public function getSetting($key, $default = null) {
        $plugin = craft()->plugins->getPlugin('lantra');
        $settings = $plugin->getSettings();
        $setting = $settings->getAttribute($key);
        return $setting ? $setting : $default;
    }

    public function getConfig($key, $default = null) {
	    $config = craft()->config->get('environmentVariables');
        return isset($config[$key]) ? $config[$key] : $default;
    }
}