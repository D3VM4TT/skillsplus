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
     * @param $key
     * @param null $default
     * @return null
     */
    public function getConfig($key, $default = null)
    {
        $config = Craft::$app->config->general['environmentVariables'];
        return isset($config[$key]) ? $config[$key] : $default;
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
                        Lantra::$app->settings->saveSetting($name, $global);
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
                Lantra::$app->settings->saveSetting('themeDateFormat', 'd-m-Y');
                Lantra::$app->settings->saveSetting('themeDefaultLimit', 10);
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
                        Lantra::$app->settings->saveSetting($name, $global);
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
                            Lantra::$app->settings->saveSetting($name, $global);
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
                            Lantra::$app->settings->saveSetting('schemeLogo', [$globalsTheme->schemeLogo->first()->id]);
                        } elseif ($name == 'themeNavigationPublic' && $globalsTheme->themeNavigationPublic) {
                            Lantra::$app->settings->saveSetting('themeNavigationPublic', $globalsTheme->themeNavigationPublic->ids());
                        } elseif ($name == 'themeNavigationPrivate' && $globalsTheme->themeNavigationPrivate) {
                            Lantra::$app->settings->saveSetting('themeNavigationPrivate', $globalsTheme->themeNavigationPrivate->ids());
                        } else {
                            Lantra::$app->settings->saveSetting($name, $global);
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