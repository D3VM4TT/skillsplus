<?php

namespace Craft;

/**
 * Generated migration
 */
class m191002_141503_migration_add_report_automated extends BaseMigration
{

    /**
    Migration manifest:
    
    FIELD
        - reportAutomated
        
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
                                    "reportIncludeExpired",
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
                    "name": "Report Automated",
                    "handle": "reportAutomated",
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
                                    "reportIncludeExpired",
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
	    craft()->migrationManager_migrations->import($this->json);

	    // update existing reports
        $criteria = craft()->elements->getCriteria(ElementType::Entry);
        $criteria->section = 'reports';
        $criteria->limit = null;
        foreach($criteria->find() as $report) {
            $report->setContentFromPost(['reportAutomated' => 1]);
            craft()->elements->saveElement($report, false);
        };

        return true;
	}
}
