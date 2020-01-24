<?php

namespace Craft;

/**
 * Generated migration
 */
class m190509_133121_migration_reports_add_expired extends BaseMigration
{

    /**
    Migration manifest:
    
    FIELD
        - reportIncludeExpired
        
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
                                    "reportIncludeExpired",
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
                    "name": "Report Include Expired",
                    "handle": "reportIncludeExpired",
                    "instructions": "",
                    "translatable": "0",
                    "required": false,
                    "type": "Lightswitch",
                    "typesettings": {
                        "default": ""
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
                                    "reportIncludeExpired",
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
