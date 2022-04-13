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
    public $maintenanceMode                     = false;

    public $certificateLogo                     = null;
    public $certificateHeader                   = '';
    public $certificateSubheader                = '';
    public $certificateTextOne                  = '';
    public $certificateTextTwo                  = '';
    public $certificateFooter                   = '';

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
    public $idleMinutes                         = 60;

    public $taskbooks                           = false;
    public $taskbookLabel                       = 'Taskbook';
    public $taskbookLevels                      = false;
    public $taskbookLevelLabels                 = [];
    public $taskbookJobRole                     = [];
    public $taskbookNew                         = true;
    public $taskbookReset                       = true;

    public $products                            = false;
    public $productCustomFields                 = true;

    public $labelResultOutcome0                 = 'Failed';
    public $labelResultOutcome1                 = 'Endorsed';

    public $managerConfirmSubmit                = false;
    public $managerConfirmText                  = 'I confirm all information submitted was completed by the user.';

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
    public $notifyAdminEmail                   = 'No-Reply@skills-plus.net';
    public $notifyFromName                     = 'Skills+';
    public $notifyFooter                       = '';
    public $disableAllNotifications            = false;

    public $notifyEnableEndorsementResult       = true;
    public $notifySubjectEndorsementResult      = '';
    public $notifyCcEndorsementResult           = '';
    public $notifyEndorsementResult             = '';

    public $notifyEnableBlockedResult           = true;
    public $notifySubjectBlockedResult          = '';
    public $notifyCcBlockedResult               = '';
    public $notifyBlockedResult                 = '';

    public $notifyEnableModuleResult            = true;
    public $notifySubjectModuleResult           = '';
    public $notifyCcModuleResult                = '';
    public $notifyModuleResult                  = '';

    public $notifyEnableCustomReport            = true;
    public $notifySubjectCustomReport           = '';
    public $notifyCcCustomReport                = '';
    public $notifyCustomReport                  = '';

    public $notifyEnableSchemeExpiry            = true;
    public $notifySubjectSchemeExpiry           = '';
    public $notifyCcSchemeExpiry                = '';
    public $notifySchemeExpiry                  = '';

    public $notifyEnableUserExpiry              = true;
    public $notifySubjectUserExpiry             = '';
    public $notifyCcUserExpiry                  = '';
    public $notifyUserExpiry                    = '';

    public $notifyEnableLicencesRemaining       = true;
    public $notifySubjectLicencesRemaining      = '';
    public $notifyCcLicencesRemaining           = '';
    public $notifyLicencesRemaining             = '';

    public $notifyEnableNewPackage              = true;
    public $notifySubjectNewPackage             = '';
    public $notifyCcNewPackage                  = '';
    public $notifyNewPackage                    = '';

    public $notifyEnableCycleStart              = true;
    public $notifySubjectCycleStart             = '';
    public $notifyCcCycleStart                  = '';
    public $notifyCycleStart                    = '';

    public $notifyEnableCycleEnd                = true;
    public $notifySubjectCycleEnd               = '';
    public $notifyCcCycleEnd                    = '';
    public $notifyCycleEnd                      = '';

    public $notifyEnableCycleComplete           = true;
    public $notifySubjectCycleComplete          = '';
    public $notifyCcCycleComplete               = '';
    public $notifyCycleComplete                 = '';

    public $notifyEnableCycleReminder           = true;
    public $notifySubjectCycleReminder          = '';
    public $notifyCcCycleReminder               = '';
    public $notifyCycleReminder                 = '';

    public $notifyEnableStepUnassigned          = true;
    public $notifySubjectStepUnassigned         = '';
    public $notifyCcStepUnassigned              = '';
    public $notifyStepUnassigned                = '';

    public $notifyEnableStepAssign              = true;
    public $notifySubjectStepAssign             = '';
    public $notifyCcStepAssign                  = '';
    public $notifyStepAssign                    = '';

    public $notifyEnableStepUpdate              = true;
    public $notifySubjectStepUpdate             = '';
    public $notifyCcStepUpdate                  = '';
    public $notifyStepUpdate                    = '';

    public $notifyEnableStepRequest             = true;
    public $notifySubjectStepRequest            = '';
    public $notifyCcStepRequest                 = '';
    public $notifyStepRequest                   = '';

    public $notifyEnableAssessment              = true;
    public $notifySubjectAssessment             = '';
    public $notifyCcAssessment                  = '';
    public $notifyAssessment                    = '';

    public $notifyEnableNewMembership           = true;
    public $notifySubjectNewMembership          = '';
    public $notifyCcNewMembership               = '';
    public $notifyNewMembership                 = '';

    public $notifyEnableComment                 = true;
    public $notifySubjectComment                = '';
    public $notifyCcComment                     = '';
    public $notifyComment                       = '';

    public $notifyEnableManagerSummary          = true;
    public $notifySubjectManagerSummary         = '';
    public $notifyCcManagerSummary              = '';
    public $notifyManagerSummary                = '';

    public $notifyEnableResultExpiryOne         = false;
    public $notifyResultExpiryWhenOne           = '-';
    public $notifyResultExpiryDaysOne           = '';
    public $notifySubjectResultExpiryOne        = '';
    public $notifyCcResultExpiryOne             = '';
    public $notifyResultExpiryOne               = '';

    public $notifyEnableResultExpiryTwo         = false;
    public $notifyResultExpiryWhenTwo           = '-';
    public $notifyResultExpiryDaysTwo           = '';
    public $notifySubjectResultExpiryTwo        = '';
    public $notifyCcResultExpiryTwo             = '';
    public $notifyResultExpiryTwo               = '';

    public $notifyEnableResultExpiryThree       = false;
    public $notifyResultExpiryWhenThree         = '-';
    public $notifyResultExpiryDaysThree         = '';
    public $notifySubjectResultExpiryThree      = '';
    public $notifyCcResultExpiryThree           = '';
    public $notifyResultExpiryThree             = '';

    public $enableTaskbooks                     = false;
    public $enableJobRoles                      = true;

    public $defaultWorkflow                     = null;

    public $userProfileFields                   = [];

    public $userPrivacyConfirm                  = false;
    public $userPrivacyMessage                  = 'I have read and understood the privacy policy.';

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

    public $reports                             = [
        'standardUsers'     => [
            'active' => true,
            'title' => 'Users',
            'group'  => 'schemeManagers',
            'roles'  => [],
            'description'  => '',
            'pageLimit' => ''
        ],
        'standardResults'     => [
            'active' => true,
            'title' => 'Results',
            'group'  => 'schemeManagers',
            'roles'  => [],
            'description'  => '',
            'pageLimit' => ''
        ],
        'standardAnnualResults'     => [
            'active' => false,
            'title' => 'Custom Annual Results',
            'group'  => 'schemeManagers',
            'roles'  => [],
            'description'  => '',
            'pageLimit' => ''
        ],
        'standardCpd'       => [
            'active' => true,
            'title' => 'CPD',
            'group'  => 'schemeManagers',
            'roles'  => [],
            'description'  => '',
            'pageLimit' => ''
        ],
        'standardPayments'  => [
            'active' => true,
            'title' => 'Payments',
            'group'  => 'schemeManagers',
            'roles'  => [],
            'description'  => '',
            'pageLimit' => ''
        ],
        'standardSm'       => [
            'active' => true,
            'title' => 'Scheme Manager',
            'group'  => 'schemeManagers',
            'roles'  => [],
            'description'  => '',
            'pageLimit' => ''
        ],
        'users'     => [
            'active' => false,
            'title' => 'Custom User (hierarchy)',
            'group'  => 'schemeManagers',
            'roles'  => [],
            'description'  => '',
            'pageLimit' => ''
        ],
        'results'   => [
            'active' => false,
            'title' => 'Custom Results (qual user)',
            'group'  => 'schemeManagers',
            'roles'  => [],
            'description'  => '',
            'pageLimit' => ''
        ],
        'expired'     => [
            'active' => false,
            'title' => 'Custom Expired (required training)',
            'group'  => 'schemeManagers',
            'roles'  => [],
            'description'  => '',
            'pageLimit' => ''
        ]
    ];

    public $reportNotes                         = '';

    /*
     * modified values
     */
    private $assetFields = [
        'schemeLogo',
        'certificateLogo'
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

        foreach ($this->reports as $key => $report) {
            if (isset($report['roles']) && is_array($report['roles'])) {
                $categories = [];
                foreach($report['roles'] as $categoryId) {
                    if (false != $category = Craft::$app->categories->getCategoryById($categoryId)) {
                        $categories[] = $category;
                    }
                }
                $this->reports[$key]['roles'] = $categories;
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