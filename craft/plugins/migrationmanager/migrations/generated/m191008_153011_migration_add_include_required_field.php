<?php

namespace Craft;

/**
 * Generated migration
 */
class m191008_153011_migration_add_include_required_field extends BaseMigration
{

    /**
    Migration manifest:
    
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
                                    "reportLastSentDate",
                                    "reportAutomated"
                                ],
                                "Criteria": [
                                    "reportResultType",
                                    "reportCompanies",
                                    "reportAllCompanies",
                                    "reportIncludeHierarchy",
                                    "reportTeams",
                                    "reportAllTeams",
                                    "reportRoles",
                                    "reportAllRoles",
                                    "reportUnits",
                                    "reportAllUnits",
                                    "reportModules",
                                    "reportAllModules",
                                    "reportResultExpiry",
                                    "reportDisplayField",
                                    "reportIncludeExpired",
                                    "reportIncludeRequired"
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
                                    "reportLastSentDate",
                                    "reportAutomated"
                                ],
                                "Criteria": [
                                    "reportResultType",
                                    "reportCompanies",
                                    "reportAllCompanies",
                                    "reportIncludeHierarchy",
                                    "reportTeams",
                                    "reportAllTeams",
                                    "reportRoles",
                                    "reportAllRoles",
                                    "reportUnits",
                                    "reportAllUnits",
                                    "reportModules",
                                    "reportAllModules",
                                    "reportResultExpiry",
                                    "reportDisplayField",
                                    "reportIncludeExpired",
                                    "reportIncludeRequired"
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
