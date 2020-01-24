<?php

namespace Craft;

/**
 * Generated migration
 */
class m190509_131157_migration_reports_updated extends BaseMigration
{

    /**
    Migration manifest:
    
    FIELD
        - reportEmails
        - reportNoDates
        - reportResultExpiry
        - reportType
        
    SECTION
        - reports
            */

private $json = <<<JSON
{
    "settings": {
        "dependencies": {
            "sections": [
                {
                    "name": "Reports",
                    "handle": "reports",
                    "type": "channel",
                    "enableVersioning": "0",
                    "hasUrls": "1",
                    "template": "reporting/custom/_entry",
                    "maxLevels": null,
                    "locales": {
                        "en_gb": {
                            "locale": "en_gb",
                            "urlFormat": "reporting/custom/{id}",
                            "nestedUrlFormat": null,
                            "enabledByDefault": "1"
                        }
                    },
                    "entrytypes": [
                        {
                            "sectionHandle": "reports",
                            "hasTitleField": "1",
                            "titleLabel": "Title",
                            "name": "Reports",
                            "handle": "reports",
                            "fieldLayout": {
                                "Reports": [
                                    "reportDescription",
                                    "reportType",
                                    "reportCount",
                                    "reportLastSentDate"
                                ],
                                "Criteria": [
                                    "reportResultType",
                                    "reportCompanies",
                                    "reportAllCompanies",
                                    "reportTeams",
                                    "reportAllTeams",
                                    "reportRoles",
                                    "reportAllRoles",
                                    "reportUnits",
                                    "reportAllUnits",
                                    "reportModules",
                                    "reportAllModules",
                                    "reportResultExpiry",
                                    "reportNoDates",
                                    "reportDisplayField"
                                ],
                                "Recipients": [
                                    "reportRecipients",
                                    "reportEmails",
                                    "reportSendFrequency",
                                    "reportSendValue"
                                ],
                                "Data": [
                                    "reportData"
                                ]
                            },
                            "requiredFields": []
                        }
                    ]
                }
            ]
        },
        "elements": {
            "fields": [
                {
                    "group": "Reports",
                    "name": "Report Emails",
                    "handle": "reportEmails",
                    "instructions": "",
                    "translatable": "0",
                    "required": false,
                    "type": "PlainText",
                    "typesettings": {
                        "placeholder": "",
                        "maxLength": "",
                        "multiline": "",
                        "initialRows": "4"
                    }
                },
                {
                    "group": "Reports",
                    "name": "Report No Dates",
                    "handle": "reportNoDates",
                    "instructions": "",
                    "translatable": "0",
                    "required": false,
                    "type": "Lightswitch",
                    "typesettings": {
                        "default": ""
                    }
                },
                {
                    "group": "Reports",
                    "name": "Report Result Expiry",
                    "handle": "reportResultExpiry",
                    "instructions": "",
                    "translatable": "0",
                    "required": false,
                    "type": "Dropdown",
                    "typesettings": {
                        "options": [
                            {
                                "label": "Expired",
                                "value": "0",
                                "default": ""
                            },
                            {
                                "label": "30",
                                "value": "30",
                                "default": ""
                            },
                            {
                                "label": "90",
                                "value": "90",
                                "default": ""
                            },
                            {
                                "label": "180",
                                "value": "180",
                                "default": ""
                            },
                            {
                                "label": "365",
                                "value": "365",
                                "default": ""
                            },
                            {
                                "label": "365+",
                                "value": "none",
                                "default": "1"
                            }
                        ]
                    }
                },
                {
                    "group": "Reports",
                    "name": "Report Type",
                    "handle": "reportType",
                    "instructions": "",
                    "translatable": "0",
                    "required": false,
                    "type": "Dropdown",
                    "typesettings": {
                        "options": [
                            {
                                "label": "Users",
                                "value": "users",
                                "default": ""
                            },
                            {
                                "label": "Results",
                                "value": "results",
                                "default": ""
                            },
                            {
                                "label": "Required",
                                "value": "required",
                                "default": ""
                            },
                            {
                                "label": "Expired",
                                "value": "expired",
                                "default": ""
                            }
                        ]
                    }
                }
            ],
            "sections": [
                {
                    "name": "Reports",
                    "handle": "reports",
                    "type": "channel",
                    "enableVersioning": "0",
                    "hasUrls": "1",
                    "template": "reporting/custom/_entry",
                    "maxLevels": null,
                    "locales": {
                        "en_gb": {
                            "locale": "en_gb",
                            "urlFormat": "reporting/custom/{id}",
                            "nestedUrlFormat": null,
                            "enabledByDefault": "1"
                        }
                    },
                    "entrytypes": [
                        {
                            "sectionHandle": "reports",
                            "hasTitleField": "1",
                            "titleLabel": "Title",
                            "name": "Reports",
                            "handle": "reports",
                            "fieldLayout": {
                                "Reports": [
                                    "reportDescription",
                                    "reportType",
                                    "reportCount",
                                    "reportLastSentDate"
                                ],
                                "Criteria": [
                                    "reportResultType",
                                    "reportCompanies",
                                    "reportAllCompanies",
                                    "reportTeams",
                                    "reportAllTeams",
                                    "reportRoles",
                                    "reportAllRoles",
                                    "reportUnits",
                                    "reportAllUnits",
                                    "reportModules",
                                    "reportAllModules",
                                    "reportResultExpiry",
                                    "reportNoDates",
                                    "reportDisplayField"
                                ],
                                "Recipients": [
                                    "reportRecipients",
                                    "reportEmails",
                                    "reportSendFrequency",
                                    "reportSendValue"
                                ],
                                "Data": [
                                    "reportData"
                                ]
                            },
                            "requiredFields": []
                        }
                    ]
                }
            ]
        }
    }
}
JSON;

	/**
	 * Any migration code in here is wrapped inside of a transaction.
	 * Returning false will rollback the migration
	 *
	 * @return bool
	 */
	public function safeUp()
	{
	    return craft()->migrationManager_migrations->import($this->json);    }
}
