<?php

namespace Craft;

/**
 * Generated migration
 */
class m190424_142144_migration_result_custom extends BaseMigration
{

    /**
    Migration manifest:
    
    FIELD
        - columnLayout
        - resultCustom
        
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
                                    "resultStatus",
                                    "resultUnit",
                                    "resultEndorsedDate",
                                    "unitEndorsementManagerLevel"
                                ],
                                "Evidence": [
                                    "resultEvidence",
                                    "resultNotes",
                                    "resultStartDate",
                                    "resultFinishDate",
                                    "resultLocation",
                                    "legacyResultFiles"
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
                                    "resultStatus",
                                    "resultEndorsedDate"
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
                                    "resultValue",
                                    "legacyResultFiles"
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
                    "group": "Globals",
                    "name": "Column Layout",
                    "handle": "columnLayout",
                    "instructions": "",
                    "translatable": "0",
                    "required": false,
                    "type": "SuperTable",
                    "typesettings": {
                        "fieldLayout": "table",
                        "staticField": null,
                        "selectionLabel": "Add a row",
                        "maxRows": null,
                        "minRows": null,
                        "blockTypes": {
                            "new": {
                                "fields": {
                                    "new1": {
                                        "name": "Field Type",
                                        "handle": "fieldType",
                                        "instructions": "",
                                        "required": "0",
                                        "type": "Dropdown",
                                        "width": "",
                                        "typesettings": {
                                            "options": [
                                                {
                                                    "label": "Type",
                                                    "value": "unitType",
                                                    "default": ""
                                                },
                                                {
                                                    "label": "Value",
                                                    "value": "unitValue",
                                                    "default": ""
                                                },
                                                {
                                                    "label": "Status",
                                                    "value": "resultStatus",
                                                    "default": ""
                                                },
                                                {
                                                    "label": "Endorsed Date",
                                                    "value": "resultEndorsedDate",
                                                    "default": ""
                                                },
                                                {
                                                    "label": "Expiry Date",
                                                    "value": "resultExpiryDate",
                                                    "default": ""
                                                },
                                                {
                                                    "label": "Start Date",
                                                    "value": "resultStartDate",
                                                    "default": ""
                                                },
                                                {
                                                    "label": "Finish Date",
                                                    "value": "resultFinishDate",
                                                    "default": ""
                                                },
                                                {
                                                    "label": "Location",
                                                    "value": "resultLocation",
                                                    "default": ""
                                                },
                                                {
                                                    "label": "Hours",
                                                    "value": "resultHours",
                                                    "default": ""
                                                },
                                                {
                                                    "label": "Evidence",
                                                    "value": "resultEvidence",
                                                    "default": ""
                                                },
                                                {
                                                    "label": "Custom",
                                                    "value": "resultCustom",
                                                    "default": ""
                                                }
                                            ]
                                        }
                                    },
                                    "new2": {
                                        "name": "Field Label",
                                        "handle": "fieldLabel",
                                        "instructions": "",
                                        "required": "0",
                                        "type": "PlainText",
                                        "width": "",
                                        "typesettings": {
                                            "placeholder": "",
                                            "maxLength": "",
                                            "multiline": "",
                                            "initialRows": "4"
                                        }
                                    },
                                    "new3": {
                                        "name": "Field Manager Only",
                                        "handle": "fieldManagerOnly",
                                        "instructions": "",
                                        "required": "0",
                                        "type": "Lightswitch",
                                        "width": "",
                                        "typesettings": {
                                            "default": ""
                                        }
                                    },
                                    "new4": {
                                        "name": "Field Custom Key",
                                        "handle": "fieldCustomKey",
                                        "instructions": "",
                                        "required": "0",
                                        "type": "PlainText",
                                        "width": "",
                                        "typesettings": {
                                            "placeholder": "",
                                            "maxLength": "",
                                            "multiline": "",
                                            "initialRows": "4"
                                        }
                                    },
                                    "new5": {
                                        "name": "Field Custom Type",
                                        "handle": "fieldCustomType",
                                        "instructions": "",
                                        "required": "0",
                                        "type": "Dropdown",
                                        "width": "",
                                        "typesettings": {
                                            "options": [
                                                {
                                                    "label": "Text",
                                                    "value": "text",
                                                    "default": "1"
                                                },
                                                {
                                                    "label": "Dropdown",
                                                    "value": "dropdown",
                                                    "default": ""
                                                }
                                            ]
                                        }
                                    },
                                    "new6": {
                                        "name": "Field Custom Options",
                                        "handle": "fieldCustomOptions",
                                        "instructions": "",
                                        "required": "0",
                                        "type": "PlainText",
                                        "width": "",
                                        "typesettings": {
                                            "placeholder": "",
                                            "maxLength": "",
                                            "multiline": "",
                                            "initialRows": "4"
                                        }
                                    }
                                }
                            }
                        }
                    }
                },
                {
                    "group": "Results",
                    "name": "Result Custom",
                    "handle": "resultCustom",
                    "instructions": "",
                    "translatable": "0",
                    "required": false,
                    "type": "SuperTable",
                    "typesettings": {
                        "fieldLayout": "table",
                        "staticField": null,
                        "selectionLabel": "Add a row",
                        "maxRows": null,
                        "minRows": null,
                        "blockTypes": {
                            "new": {
                                "fields": {
                                    "new1": {
                                        "name": "Custom Key",
                                        "handle": "customKey",
                                        "instructions": "",
                                        "required": "0",
                                        "type": "PlainText",
                                        "width": "",
                                        "typesettings": {
                                            "placeholder": "",
                                            "maxLength": "",
                                            "multiline": "",
                                            "initialRows": "4"
                                        }
                                    },
                                    "new2": {
                                        "name": "Custom Value",
                                        "handle": "customValue",
                                        "instructions": "",
                                        "required": "0",
                                        "type": "PlainText",
                                        "width": "",
                                        "typesettings": {
                                            "placeholder": "",
                                            "maxLength": "",
                                            "multiline": "",
                                            "initialRows": "4"
                                        }
                                    }
                                }
                            }
                        }
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
                                    "resultStatus",
                                    "resultUnit",
                                    "resultEndorsedDate",
                                    "unitEndorsementManagerLevel"
                                ],
                                "Evidence": [
                                    "resultEvidence",
                                    "resultNotes",
                                    "resultStartDate",
                                    "resultFinishDate",
                                    "resultLocation",
                                    "legacyResultFiles"
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
                                    "resultStatus",
                                    "resultEndorsedDate"
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
                                    "resultValue",
                                    "legacyResultFiles"
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
