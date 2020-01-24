<?php

namespace Craft;

/**
 * Generated migration
 */
class m190603_115251_migration_add_company_label extends BaseMigration
{

    /**
    Migration manifest:
    
    FIELD
        - companyLabel
        
    SECTION
        - companies
            */

private $json = <<<JSON
{
    "settings": {
        "dependencies": {
            "sections": [
                {
                    "name": "Companies",
                    "handle": "companies",
                    "type": "channel",
                    "enableVersioning": "0",
                    "hasUrls": "0",
                    "template": null,
                    "maxLevels": null,
                    "locales": {
                        "en_gb": "1"
                    },
                    "entrytypes": [
                        {
                            "sectionHandle": "companies",
                            "hasTitleField": "1",
                            "titleLabel": "Title",
                            "name": "Company",
                            "handle": "company",
                            "fieldLayout": {
                                "Company": [
                                    "companyParent",
                                    "companyPrimaryManagers",
                                    "companySecondaryManagers",
                                    "companyRemainingLicences"
                                ],
                                "Legacy": [
                                    "legacyId",
                                    "legacyParentId"
                                ],
                                "Hierarchy": [
                                    "companyLabel"
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
                    "group": "Structure",
                    "name": "Company Label",
                    "handle": "companyLabel",
                    "instructions": "Automatically set according to company hierarchy.",
                    "translatable": "0",
                    "required": false,
                    "type": "PlainText",
                    "typesettings": {
                        "placeholder": "",
                        "maxLength": "",
                        "multiline": "",
                        "initialRows": "4"
                    }
                }
            ],
            "sections": [
                {
                    "name": "Companies",
                    "handle": "companies",
                    "type": "channel",
                    "enableVersioning": "0",
                    "hasUrls": "0",
                    "template": null,
                    "maxLevels": null,
                    "locales": {
                        "en_gb": "1"
                    },
                    "entrytypes": [
                        {
                            "sectionHandle": "companies",
                            "hasTitleField": "1",
                            "titleLabel": "Title",
                            "name": "Company",
                            "handle": "company",
                            "fieldLayout": {
                                "Company": [
                                    "companyParent",
                                    "companyPrimaryManagers",
                                    "companySecondaryManagers",
                                    "companyRemainingLicences"
                                ],
                                "Legacy": [
                                    "legacyId",
                                    "legacyParentId"
                                ],
                                "Hierarchy": [
                                    "companyLabel"
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
