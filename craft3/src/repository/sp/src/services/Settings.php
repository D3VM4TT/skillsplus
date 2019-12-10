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


class Settings extends Component
{
    public function saveSettings($settings)
    {
        $settings = JsonHelper::encode($settings);
        $affectedRows = craft()->db->createCommand()->update('plugins', array('settings' => $settings), array('class' => 'Lantra'));
        return (bool)$affectedRows;
    }

    public function getDbSettings()
    {
        $result = craft()->db->createCommand()
            ->select('settings')
            ->from('plugins')
            ->where(['class' => 'Lantra'])
            ->queryRow();
        return $result ? JsonHelper::decode($result['settings']) : [];
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

    /**
     * Custom script to keep settings synced up outside of migration manager or plugin updates.
     *
     * @param $currentVersion
     */
    public function updateSettings($currentVersion)
    {
        // direct from db so that it always loads correctly
        $dbSettings = $this->getDbSettings();
        $dbVersion = isset($dbSettings['settingsVersion']) ? $dbSettings['settingsVersion'] : 0;

        if ($dbVersion >= $currentVersion) {
            return;
        }

        ## VERSION 1 - if globals scheme exists, migrate to settings 08/05/19
        if ($dbVersion < 1) {
            $globalsScheme = craft()->globals->getSetByHandle('globalsScheme');
            if ($globalsScheme) {
                $globals = [
                    'schemeTeams',
                    'schemeUserReadOnly',
                    'schemeEmailDomain',
                    'schemeTestEmailAddress',
                ];
                foreach ($globals as $name) {
                    if (isset($globalsScheme->$name)) {
                        ## copy value from globals to settings
                        $global = $globalsScheme->$name;
                        Lantra::$app->setting->saveSetting($name, $global);
                        ## delete field
                        $field = craft()->fields->getFieldByHandle($name);
                        if ($field) {
                            craft()->fields->deleteFieldById($field->id);
                        }
                    }
                }
            }
            $dbVersion = 1;
        }

        ## VERSION 2 - migrate system globals 08/05/19
        if ($dbVersion < 2) {
            // make sure fields still exist as globals
            $field = craft()->fields->getFieldByHandle('dateFormat');
            if ($field) {
                ## set defaults
                Lantra::$app->setting->saveSetting('themeDateFormat', 'd-m-Y');
                Lantra::$app->setting->saveSetting('themeDefaultLimit', 10);
                $fields = [
                    'dateFormat',
                    'defaultLimit'
                ];
                foreach ($fields as $name) {
                    ## delete fields
                    $field = craft()->fields->getFieldByHandle($name);
                    if ($field) {
                        craft()->fields->deleteFieldById($field->id);
                    }
                }
                ## delete global set
                craft()->globals->deleteSetById(489);
            }
            $dbVersion = 2;
        }

        ## VERSION 3 - move over user profile fields & theme fields
        if ($dbVersion < 3) {
            $globalsUserProfile = craft()->globals->getSetByHandle('userProfile');
            if ($globalsUserProfile) {
                $globals = [
                    'userEditName',
                    'userEditEmail',
                    'userEditAddress',
                    'userEditTelephone',
                    'userEditDob',
                    'userEditStartDate',
                    'userEditRole',
                    'userEditPhoto',
                    'userEditCustomFields'
                ];
                foreach ($globals as $name) {
                    if (isset($globalsUserProfile->$name)) {
                        ## copy value from globals to settings
                        $global = $globalsUserProfile->$name;
                        Lantra::$app->setting->saveSetting($name, $global);
                        ## delete field
                        $field = craft()->fields->getFieldByHandle($name);
                        if ($field) {
                            craft()->fields->deleteFieldById($field->id);
                        }
                    }
                }
                ## delete global set
                craft()->globals->deleteSetById($globalsUserProfile->id);
            }
            $dbVersion = 3;
        }

        ## VERSION 4 - move over licence globals
        if ($dbVersion < 4) {
            $globalsScheme = craft()->globals->getSetByHandle('globalsScheme');
            if ($globalsScheme) {
                $globals = [
                    'schemeRemainingLicences',
                    'schemeExpiryDate',
                    'individualCompany',
                    'individualLicenceDays',
                    'individualLicencePaypalButton'
                ];
                foreach ($globals as $name) {
                    if (isset($globalsScheme->$name)) {
                        if ($name != 'individualCompany') {
                            ## copy value from globals to settings
                            $global = $globalsScheme->$name;
                            Lantra::$app->setting->saveSetting($name, $global);
                        }
                        ## delete field
                        $field = craft()->fields->getFieldByHandle($name);
                        if ($field) {
                            craft()->fields->deleteFieldById($field->id);
                        }
                    }
                }
                ## delete global set
                craft()->globals->deleteSetById($globalsScheme->id);
            }
            $dbVersion = 4;
        }

        ## VERSION 5 - move over theme settings
        if ($dbVersion < 5) {
            $globalsTheme = craft()->globals->getSetByHandle('globalsTheme');
            if ($globalsTheme) {
                $globals = [
                    'schemeName',
                    'schemeDescription',
                    'themeColorPrimary',
                    'themeColorSecondary',
                    'schemeLogo',
                    'themeNavigationPublic',
                    'themeNavigationPrivate'
                ];
                foreach ($globals as $name) {
                    if (isset($globalsTheme->$name)) {
                        ## copy value from globals to settings
                        $global = $globalsTheme->$name;
                        if ($name == 'schemeLogo' && $globalsTheme->schemeLogo) {
                            Lantra::$app->setting->saveSetting('schemeLogo', [$globalsTheme->schemeLogo->first()->id]);
                        } elseif ($name == 'themeNavigationPublic' && $globalsTheme->themeNavigationPublic) {
                            Lantra::$app->setting->saveSetting('themeNavigationPublic', $globalsTheme->themeNavigationPublic->ids());
                        } elseif ($name == 'themeNavigationPrivate' && $globalsTheme->themeNavigationPrivate) {
                            Lantra::$app->setting->saveSetting('themeNavigationPrivate', $globalsTheme->themeNavigationPrivate->ids());
                        } else {
                            Lantra::$app->setting->saveSetting($name, $global);
                        }
                        ## delete field
                        $field = craft()->fields->getFieldByHandle($name);
                        if ($field) {
                            craft()->fields->deleteFieldById($field->id);
                        }
                    }
                }
            }

            $dbVersion = 5;
        }

        $this->saveSetting('settingsVersion' , $dbVersion);
    }

    /**
     * @param $key
     * @throws \CException
     */
    public function resetDataClean($key)
    {
        $key = 'field_dataClean' . $key;
        $mysql = "UPDATE craft_content SET `" . $key . "` = '0' WHERE `" . $key . "` = '1'";
        craft()->db->createCommand($mysql)->query();
    }
}