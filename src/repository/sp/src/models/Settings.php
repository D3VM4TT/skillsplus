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
use craft\helpers\DateTimeHelper;

use lantra\sp\Plugin as Lantra;

class Settings extends Model
{
    public $settingsVersion                     = '1.0.0';
    public $schemeName                          = 'Skills Plus';
    public $schemeDescription                   = '';
    public $schemeLogo                          = null;
    public $schemeTeams                         = false;
    public $schemeUserReadOnly                  = false;
    public $schemeEmailDomain                   = 'skills-plus.co.uk';
    public $schemeTestEmailAddress              = 'robin@coffeebean.design';
    public $jobRoleEndorse                      = false;
    public $enableCreateAccount                 = false;
    public $enableEditCredentials               = false;

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
    public $themeMyDashboard                    = true;
    public $themeMyTaskbooks                    = false;

    public $taskbooks                           = false;
    public $taskbookLabel                       = 'Taskbook';
    public $taskbookLevels                      = false;
    public $taskbookJobRole                     = [];
    public $taskbookNew                         = true;

    public $membershipEnable                    = false;
    public $membershipOptions                   = [];

    public $lantraDisableLicences               = false;
    public $schemeRemainingLicences             = 1000;
    public $schemeExpiryDate                    = null;
    public $individualCompany                   = null;
    public $individualLicenceDays               = 365;
    public $individualLicencePaypalButton       = '';
    public $individualJobRole                   = null;

    public $notifyFromEmail                    = 'No-Reply@skills-plus.net';
    public $notifyFromName                     = 'Skills+';
    public $notifyFooter                       = '';
    public $disableAllNotifications            = false;

    public $notifyEnableEndorsementResult       = true;
    public $notifySubjectEndorsementResult      = '';
    public $notifyEndorsementResult             = '';

    public $notifyEnableBlockedResult           = true;
    public $notifySubjectBlockedResult          = '';
    public $notifyBlockedResult                 = '';

    public $notifyEnableModuleResult            = true;
    public $notifySubjectModuleResult           = '';
    public $notifyModuleResult                  = '';

    public $notifyEnableCustomReport            = true;
    public $notifySubjectCustomReport           = '';
    public $notifyCustomReport                  = '';

    public $notifyEnableSchemeExpiry            = true;
    public $notifySubjectSchemeExpiry           = '';
    public $notifySchemeExpiry                  = '';

    public $notifyEnableUserExpiry              = true;
    public $notifySubjectUserExpiry             = '';
    public $notifyUserExpiry                    = '';

    public $notifyEnableLicencesRemaining       = true;
    public $notifySubjectLicencesRemaining      = '';
    public $notifyLicencesRemaining             = '';

    public $notifyEnableNewPackage              = true;
    public $notifySubjectNewPackage             = '';
    public $notifyNewPackage                    = '';

    public $notifyEnableCycleStart              = true;
    public $notifySubjectCycleStart             = '';
    public $notifyCycleStart                    = '';

    public $notifyEnableCycleEnd                = true;
    public $notifySubjectCycleEnd               = '';
    public $notifyCycleEnd                      = '';

    public $notifyEnableCycleComplete           = true;
    public $notifySubjectCycleComplete          = '';
    public $notifyCycleComplete                 = '';

    public $notifyEnableCycleReminder           = true;
    public $notifySubjectCycleReminder          = '';
    public $notifyCycleReminder                 = '';

    public $notifyEnableStepUnassigned          = true;
    public $notifySubjectStepUnassigned         = '';
    public $notifyStepUnassigned                = '';

    public $notifyEnableStepAssign              = true;
    public $notifySubjectStepAssign             = '';
    public $notifyStepAssign                    = '';

    public $notifyEnableStepUpdate              = true;
    public $notifySubjectStepUpdate             = '';
    public $notifyStepUpdate                    = '';

    public $notifyEnableStepRequest             = true;
    public $notifySubjectStepRequest            = '';
    public $notifyStepRequest                   = '';

    public $notifyEnableAssessment              = true;
    public $notifySubjectAssessment             = '';
    public $notifyAssessment                    = '';

    public $notifyEnableNewMembership           = true;
    public $notifySubjectNewMembership          = '';
    public $notifyNewMembership                 = '';

    public $notifyEnableComment                 = true;
    public $notifySubjectComment                = '';
    public $notifyComment                       = '';

    public $notifyEnableManagerSummary          = true;
    public $notifySubjectManagerSummary         = '';
    public $notifyManagerSummary                = '';

    public $enableTaskbooks                     = false;
    public $enableJobRoles                      = true;

    public $defaultWorkflow                     = null;

    public $userProfileFields                   = [];

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
    public $customReports                       = [
        'users'     => true,
        'results'   => true,
        'expired'   => true,
        'required'  => true,
        'cpd'       => false
    ];
    public $standardReports                     = false;

    public $payPalBusiness                      = '';
    public $payPalLantraCert                    = '';
    public $payPalLantraKey                     = '';
    public $payPalCertId                        = '';

    /*
     * modified values
     */
    private $assetFields = [
        'schemeLogo'
    ];

    private $entryFields = [
        'themeNavigationPublic',
        'themeNavigationPrivate',
        'individualCompany',
        'defaultWorkflow'
    ];

    private $categoryFields = [
        'individualJobRole',
        'taskbookJobRole'
    ];

    private $dateFields = [
        'schemeExpiryDate'
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
     * @throws \Exception
     */
    public function init ()
    {
        parent::init();

        $this->_populateModel(Lantra::$app->settings->getDbSettings());

        foreach ($this->dateFields as $key) {
            if (is_array($this->$key)) {
                $this->$key = DateTimeHelper::toDateTime($this->$key);
            }
        }

        foreach ($this->assetFields as $key) {
            if (is_array($this->$key)) {
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
            if (is_array($this->$key)) {
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
            if (is_array($this->$key)) {
                $categories = [];
                foreach($this->$key as $categoryId) {
                    if (false != $category = Craft::$app->categories->getCategoryById($categoryId)) {
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
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }
}