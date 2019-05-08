<?php
namespace Craft;

class Lantra_SettingsModel extends BaseModel
{
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

            ## scheme settings
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
            'notifySubjectUserExpiry'           => AttributeType::String
		);
	}
}