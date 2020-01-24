<?php

namespace Craft;

/**
 * Generated migration
 */
class m190530_114246_migration_add_result_endorsed_user extends BaseMigration
{

    /**
    Migration manifest:
    
    FIELD
        - resultEndorsedUser
        - resultOwner
        
    SECTION
        - results
            */

private $json = <<<JSON
{
    "settings": {
        "dependencies": {
            "sections": [
                {
                    "name": "Results",
                    "handle": "results",
                    "type": "channel",
                    "enableVersioning": "1",
                    "hasUrls": "0",
                    "template": null,
                    "maxLevels": null,
                    "locales": {
                        "en_gb": "1"
                    },
                    "entrytypes": [
                        {
                            "sectionHandle": "results",
                            "hasTitleField": "0",
                            "titleLabel": null,
                            "titleFormat": "[unit {resultUnit.first.id}] {author.firstName} {author.lastName}",
                            "name": "Unit Result",
                            "handle": "unitResult",
                            "fieldLayout": {
                                "Result": [
                                    "resultOwner",
                                    "resultStatus",
                                    "resultUnit",
                                    "resultEndorsedDate",
                                    "resultEndorsedUser",
                                    "unitEndorsementManagerLevel"
                                ],
                                "Evidence": [
                                    "resultEvidence",
                                    "resultNotes",
                                    "resultStartDate",
                                    "resultFinishDate",
                                    "resultLocation"
                                ],
                                "Test": [
                                    "resultAttempts",
                                    "resultScore"
                                ],
                                "Comments": [
                                    "resultComments"
                                ],
                                "Custom Values": [
                                    "resultCustom"
                                ]
                            },
                            "requiredFields": []
                        },
                        {
                            "sectionHandle": "results",
                            "hasTitleField": "0",
                            "titleLabel": null,
                            "titleFormat": "[module {resultModule.first().id}] {author.firstName} {author.lastName} ",
                            "name": "Module Result",
                            "handle": "moduleResult",
                            "fieldLayout": {
                                "Result": [
                                    "resultModule",
                                    "resultStatus"
                                ],
                                "Comments": [
                                    "resultComments"
                                ]
                            },
                            "requiredFields": []
                        },
                        {
                            "sectionHandle": "results",
                            "hasTitleField": "1",
                            "titleLabel": "",
                            "name": "User Result",
                            "handle": "userResult",
                            "fieldLayout": {
                                "Result": [
                                    "resultOwner",
                                    "resultStatus",
                                    "resultEndorsedDate",
                                    "resultEndorsedUser"
                                ],
                                "Evidence": [
                                    "resultEvidence",
                                    "resultStartDate",
                                    "resultFinishDate",
                                    "resultLocation",
                                    "resultHours",
                                    "resultNotes",
                                    "unitEndorsementManagerLevel",
                                    "resultModule",
                                    "resultUnit",
                                    "resultValue"
                                ],
                                "Comments": [
                                    "resultComments"
                                ],
                                "Custom Values": [
                                    "resultCustom"
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
                    "group": "Results",
                    "name": "Result Endorsed User",
                    "handle": "resultEndorsedUser",
                    "instructions": "",
                    "translatable": "0",
                    "required": false,
                    "type": "Users",
                    "typesettings": {
                        "sources": [],
                        "limit": "1",
                        "selectionLabel": ""
                    }
                },
                {
                    "group": "Results",
                    "name": "Result Owner",
                    "handle": "resultOwner",
                    "instructions": "",
                    "translatable": "0",
                    "required": false,
                    "type": "Users",
                    "typesettings": {
                        "sources": [],
                        "limit": "1",
                        "selectionLabel": ""
                    }
                }
            ],
            "sections": [
                {
                    "name": "Results",
                    "handle": "results",
                    "type": "channel",
                    "enableVersioning": "1",
                    "hasUrls": "0",
                    "template": null,
                    "maxLevels": null,
                    "locales": {
                        "en_gb": "1"
                    },
                    "entrytypes": [
                        {
                            "sectionHandle": "results",
                            "hasTitleField": "0",
                            "titleLabel": null,
                            "titleFormat": "[unit {resultUnit.first.id}] {author.firstName} {author.lastName}",
                            "name": "Unit Result",
                            "handle": "unitResult",
                            "fieldLayout": {
                                "Result": [
                                    "resultOwner",
                                    "resultStatus",
                                    "resultUnit",
                                    "resultEndorsedDate",
                                    "resultEndorsedUser",
                                    "unitEndorsementManagerLevel"
                                ],
                                "Evidence": [
                                    "resultEvidence",
                                    "resultNotes",
                                    "resultStartDate",
                                    "resultFinishDate",
                                    "resultLocation"
                                ],
                                "Test": [
                                    "resultAttempts",
                                    "resultScore"
                                ],
                                "Comments": [
                                    "resultComments"
                                ],
                                "Custom Values": [
                                    "resultCustom"
                                ]
                            },
                            "requiredFields": []
                        },
                        {
                            "sectionHandle": "results",
                            "hasTitleField": "0",
                            "titleLabel": null,
                            "titleFormat": "[module {resultModule.first().id}] {author.firstName} {author.lastName} ",
                            "name": "Module Result",
                            "handle": "moduleResult",
                            "fieldLayout": {
                                "Result": [
                                    "resultModule",
                                    "resultStatus"
                                ],
                                "Comments": [
                                    "resultComments"
                                ]
                            },
                            "requiredFields": []
                        },
                        {
                            "sectionHandle": "results",
                            "hasTitleField": "1",
                            "titleLabel": "",
                            "name": "User Result",
                            "handle": "userResult",
                            "fieldLayout": {
                                "Result": [
                                    "resultOwner",
                                    "resultStatus",
                                    "resultEndorsedDate",
                                    "resultEndorsedUser"
                                ],
                                "Evidence": [
                                    "resultEvidence",
                                    "resultStartDate",
                                    "resultFinishDate",
                                    "resultLocation",
                                    "resultHours",
                                    "resultNotes",
                                    "unitEndorsementManagerLevel",
                                    "resultModule",
                                    "resultUnit",
                                    "resultValue"
                                ],
                                "Comments": [
                                    "resultComments"
                                ],
                                "Custom Values": [
                                    "resultCustom"
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
