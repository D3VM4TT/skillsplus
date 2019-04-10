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
            'dateFormat'                        => AttributeType::String,
            'defaultLimit'                      => AttributeType::Number,

            ## theme settings
            'themeLoginMessage'                 => AttributeType::String,
            'themeDisableCertificates'          => AttributeType::Bool,

            ## change to schemeDisableLicences?
            'lantraDisableLicences'             => AttributeType::Bool,

            ## scheme settings
            'schemeRemainingLicences'           => AttributeType::Number,
            'schemeExpiryDate'                  => AttributeType::DateTime,
            'schemeTeams'                       => AttributeType::Bool,
            'schemeUserReadOnly'                => AttributeType::Bool,
            'schemeEmailDomain'                 => AttributeType::String,
            'schemeTestEmailAddress'            => AttributeType::String,
            ## individual company relationship?
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