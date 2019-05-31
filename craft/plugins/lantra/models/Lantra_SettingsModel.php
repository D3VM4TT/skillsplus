<?php
namespace Craft;

class Lantra_SettingsModel extends BaseModel
{
    private $assetFields = [
        'schemeLogo'
    ];

    private $entryFields = [
        'themeNavigationPublic',
        'themeNavigationPrivate'
    ];

    /*
     * @note same MUST be copied to LantraPlugin::defineSettings() - dumb, I know.
     */
	protected function defineAttributes()
	{
		return array(
            ## config settings
            'settingsVersion'                   => AttributeType::Number,

            ## theme settings
            'themeDateFormat'                   => AttributeType::String,
            'themeDefaultLimit'                 => AttributeType::Number,
            'themeLoginMessage'                 => AttributeType::String,
            'themeDisableCertificates'          => AttributeType::Bool,
            'themeResultHistoryTitle'           => AttributeType::String,
            'themeResultHistoryLink'            => AttributeType::Bool,
            'themeDisableResultHistory'         => AttributeType::Bool,
            'themeColorPrimary'                 => AttributeType::String,
            'themeColorSecondary'               => AttributeType::String,
            'themeNavigationPublic'             => AttributeType::Mixed,
            'themeNavigationPrivate'            => AttributeType::Mixed,

            ## scheme settings
            'schemeName'                        => AttributeType::String,
            'schemeDescription'                 => AttributeType::String,
            'schemeLogo'                        => AttributeType::Number,
            'schemeTeams'                       => AttributeType::Bool,
            'schemeUserReadOnly'                => AttributeType::Bool,
            'schemeEmailDomain'                 => AttributeType::String,
            'schemeTestEmailAddress'            => AttributeType::String,

            ## licence settings
            'lantraDisableLicences'             => AttributeType::Bool,
            'schemeRemainingLicences'           => AttributeType::Number,
            'schemeExpiryDate'                  => AttributeType::DateTime,

            ## individual company settings
            'individualCompany'                 => AttributeType::Number,
            'individualLicenceDays'             => AttributeType::Number,
            'individualLicencePaypalButton'     => AttributeType::String,

            ## notifications
            'notifyFooter'                      => AttributeType::String,
            'notifySubjectBlockedResult'        => AttributeType::String,
            'notifySubjectEndorsementResult'    => AttributeType::String,
            'notifySubjectLicencesRemaining'    => AttributeType::String,
            'notifySubjectManagerSummary'       => AttributeType::String,
            'notifySubjectModuleResult'         => AttributeType::String,
            'notifySubjectSchemeExpiry'         => AttributeType::String,
            'notifySubjectUserExpiry'           => AttributeType::String,
            'notifySubjectComment'              => AttributeType::String,
            'notifyBlockedResult'               => AttributeType::String,
            'notifyEndorsementResult'           => AttributeType::String,
            'notifyLicencesRemaining'           => AttributeType::String,
            'notifyManagerSummary'              => AttributeType::String,
            'notifyModuleResult'                => AttributeType::String,
            'notifySchemeExpiry'                => AttributeType::String,
            'notifyUserExpiry'                  => AttributeType::String,
            'notifyComment'                     => AttributeType::String,

            ## user profile
            'userEditName'                      => AttributeType::Bool,
            'userEditEmail'                     => AttributeType::Bool,
            'userEditAddress'                   => AttributeType::Bool,
            'userEditTelephone'                 => AttributeType::Bool,
            'userEditDob'                       => AttributeType::Bool,
            'userEditRole'                      => AttributeType::Bool,
            'userEditPhoto'                     => AttributeType::Bool,
            'userEditCustomFields'              => AttributeType::Mixed,

            ## user profile
            'userAccountInformation'            => AttributeType::Mixed,
		);
	}

    /**
     * @return null|void
     */
    public function setAttributes($values) {
	    parent::setAttributes($values);

	    foreach ($this->assetFields as $key) {
            if ($this->$key && is_array($this->$key)) {
                $files = [];
                foreach($this->$key as $fileId) {
                    $files[] = craft()->assets->getFileById($fileId);
                }
                $this->$key = $files;
            }
        }

        foreach ($this->entryFields as $key) {
            if ($this->$key && is_array($this->$key)) {
                $entries = [];
                foreach($this->$key as $entryId) {
                    $entries[] = craft()->entries->getEntryById($entryId);
                }
                $this->$key = $entries;
            }
        }
	}
}