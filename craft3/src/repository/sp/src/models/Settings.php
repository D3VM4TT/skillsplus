<?php
/**
 * Lantra Skills Plus for Craft CMS 3.x
 *
 * @link      https://coffeebean.design
 * @copyright Copyright (c) 2020 Coffee Bean Design
 */

namespace lantra\sp\models;

use Craft;
use craft\base\Model;

use lantra\sp\Plugin as Lantra;

class Settings extends Model
{
    public $schemeName                          = 'Skills Plus';
    public $schemeDescription                   = '';
    public $schemeLogo                          = null;
    public $schemeTeams                         = false;
    public $schemeUserReadOnly                  = false;
    public $schemeEmailDomain                   = 'skills-plus.co.uk';
    public $schemeTestEmailAddress              = 'robin@coffeebean.design';
    public $jobRoleEndorse                      = false;
    public $themeDateFormat                     = 'd-m-Y';
    public $themeDefaultLimit                   = 10;
    public $themeLoginMessage                   = '';
    public $themeDisableCertificates            = false;
    public $themeResultHistoryTitle             = 'Result History';
    public $themeResultHistoryLink              = false;
    public $themeDisableResultHistory           = false;
    public $themeColorPrimary                   = '#961e20';
    public $themeColorSecondary                 = '#2e338f';
    public $themeNavigationPublic               = ["1090","3618"];
    public $themeNavigationPrivate              = ["1090","3618"];
    public $lantraDisableLicences               = false;
    public $schemeRemainingLicences             = 1000;
    public $schemeExpiryDate                    = null;
    public $individualCompany                   = null;
    public $individualLicenceDays               = 365;
    public $individualLicencePaypalButton       = '';
    public $individualJobRole                   = null;
    public $notifyFooter                        = '';
    public $notifySubjectBlockedResult          = '';
    public $notifySubjectEndorsementResult      = '';
    public $notifySubjectLicencesRemaining      = '';
    public $notifySubjectManagerSummary         = '';
    public $notifySubjectModuleResult           = '';
    public $notifySubjectSchemeExpiry           = '';
    public $notifySubjectUserExpiry             = '';
    public $notifySubjectComment                = '';
    public $notifyBlockedResult                 = '';
    public $notifyEndorsementResult             = '';
    public $notifyLicencesRemaining             = '';
    public $notifyManagerSummary                = '';
    public $notifyModuleResult                  = '';
    public $notifySchemeExpiry                  = '';
    public $notifyUserExpiry                    = '';
    public $notifyComment                       = '';
    public $notifySubjectCustomReport           = '';
    public $notifyCustomReport                  = '';
    public $disableEndorsementNotify            = false;
    public $userEditName                        = true;
    public $userEditEmail                       = true;
    public $userEditAddress                     = true;
    public $userEditTelephone                   = true;
    public $userEditDob                         = true;
    public $userEditStartDate                   = true;
    public $userEditRole                        = true;
    public $userEditPhoto                       = true;
    public $userEditCustomFields                = true;
    public $userAccountInformation              = [
            'userId'            => '',
            'userStartDate'     => '',
            'userCompany'       => '',
            'managerCompanies'  => ''
    ];
    public $labelJobRole                        = '';
    public $queue                               = [];
    public $disableResultCache                  = false;

    /*
     * modified values
     */
    private $assetFields = [
        'schemeLogo'
    ];

    private $entryFields = [
        'themeNavigationPublic',
        'themeNavigationPrivate',
        'individualCompany'
    ];

    private $categoryFields = [
        'individualJobRole',
    ];

    /*
     * rules
     */
    private $requiredFields = [
        'schemeName',
        'schemeEmailDomain',
        'schemeTestEmailAddress'
    ];

    private $numberFields = [
        'themeDefaultLimit',
        'schemeRemainingLicences',
        'individualLicenceDays'
    ];

    /**
     *
     */
    public function init ()
    {
        parent::init();

        $this->_populateModel(Lantra::$app->settings->getDbSettings());

        foreach ($this->assetFields as $key) {
            if ($this->$key && is_array($this->$key)) {
                $files = [];
                foreach($this->$key as $fileId) {
                    if (false != $file = Craft::$app->assets->getAssetById($fileId)) {
                        $files[] = $file;
                    }
                }
                $this->$key = $files;
            }
        }

        foreach ($this->entryFields as $key) {
            if ($this->$key && is_array($this->$key)) {
                $entries = [];
                foreach($this->$key as $entryId) {
                    if (false != $entry = Craft::$app->entries->getEntryById($entryId)) {
                        $entries[] = $entry;
                    }
                }
                $this->$key = $entries;
            }
        }

        foreach ($this->categoryFields as $key) {
            if ($this->$key && is_array($this->$key)) {
                $categories = [];
                foreach($this->$key as $categoryId) {
                    if (false != $category = Craft::$app->categories->categories($categoryId)) {
                        $categories[] = $category;
                    }
                }
                $this->$key = $categories;
            }
        }
    }

    /**
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();

        $rules[] = [$this->requiredFields, 'required'];
        $rules[] = [$this->numberFields, 'number', 'integerOnly' => true];

        return $rules;
    }

    /**
     * @param $settings
     */
    private function _populateModel($settings)
    {
        foreach ($settings as $key => $value) {
            if (isset($this->{$key})) {
                $this->{$key} = $value;
            }
        }
    }
}