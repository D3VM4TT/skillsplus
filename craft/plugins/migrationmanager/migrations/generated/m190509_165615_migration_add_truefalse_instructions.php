<?php

namespace Craft;

/**
 * Generated migration
 */
class m190509_165615_migration_add_truefalse_instructions extends BaseMigration
{

    /**
    Migration manifest:
    
    FIELD
        - testQuestions
            */

private $json = <<<JSON
{
    "settings": {
        "dependencies": [],
        "elements": {
            "fields": [
                {
                    "group": "Tests",
                    "name": "Test Questions",
                    "handle": "testQuestions",
                    "instructions": "",
                    "translatable": "0",
                    "required": false,
                    "type": "Matrix",
                    "typesettings": {
                        "maxBlocks": null,
                        "blockTypes": {
                            "new1": {
                                "name": "True False",
                                "handle": "trueFalse",
                                "fields": {
                                    "new1": {
                                        "name": "Question",
                                        "handle": "question",
                                        "instructions": "",
                                        "required": "0",
                                        "type": "PlainText",
                                        "typesettings": {
                                            "placeholder": "",
                                            "maxLength": "",
                                            "multiline": "",
                                            "initialRows": "4"
                                        }
                                    },
                                    "new2": {
                                        "name": "Answer",
                                        "handle": "answer",
                                        "instructions": "Lightswitch off (grey) is false, on (green) is true.",
                                        "required": "0",
                                        "type": "Lightswitch",
                                        "typesettings": {
                                            "default": ""
                                        }
                                    },
                                    "new3": {
                                        "name": "Asset",
                                        "handle": "asset",
                                        "instructions": "",
                                        "required": "0",
                                        "type": "Assets",
                                        "typesettings": {
                                            "useSingleFolder": "",
                                            "sources": [
                                                "uploads"
                                            ],
                                            "defaultUploadLocationSource": "uploads",
                                            "defaultUploadLocationSubpath": "",
                                            "singleUploadLocationSource": "evidence",
                                            "singleUploadLocationSubpath": "",
                                            "restrictFiles": "",
                                            "limit": "1",
                                            "viewMode": "list",
                                            "selectionLabel": ""
                                        }
                                    }
                                }
                            },
                            "new2": {
                                "name": "Choices",
                                "handle": "choices",
                                "fields": {
                                    "new1": {
                                        "name": "Question",
                                        "handle": "question",
                                        "instructions": "",
                                        "required": "0",
                                        "type": "PlainText",
                                        "typesettings": {
                                            "placeholder": "",
                                            "maxLength": "",
                                            "multiline": "",
                                            "initialRows": "4"
                                        }
                                    },
                                    "new2": {
                                        "name": "Answers",
                                        "handle": "answers",
                                        "instructions": "",
                                        "required": "0",
                                        "type": "Table",
                                        "typesettings": {
                                            "columns": {
                                                "col1": {
                                                    "heading": "Answer",
                                                    "handle": "answer",
                                                    "width": "",
                                                    "type": "singleline"
                                                },
                                                "col2": {
                                                    "heading": "Correct",
                                                    "handle": "correct",
                                                    "width": "",
                                                    "type": "checkbox"
                                                }
                                            },
                                            "defaults": {
                                                "row1": {
                                                    "col1": "",
                                                    "col2": ""
                                                }
                                            }
                                        }
                                    },
                                    "new3": {
                                        "name": "Asset",
                                        "handle": "asset",
                                        "instructions": "",
                                        "required": "0",
                                        "type": "Assets",
                                        "typesettings": {
                                            "useSingleFolder": "",
                                            "sources": [
                                                "uploads"
                                            ],
                                            "defaultUploadLocationSource": "uploads",
                                            "defaultUploadLocationSubpath": "",
                                            "singleUploadLocationSource": "evidence",
                                            "singleUploadLocationSubpath": "",
                                            "restrictFiles": "",
                                            "limit": "",
                                            "viewMode": "list",
                                            "selectionLabel": ""
                                        }
                                    }
                                }
                            },
                            "new3": {
                                "name": "Text",
                                "handle": "text",
                                "fields": {
                                    "new1": {
                                        "name": "Question",
                                        "handle": "question",
                                        "instructions": "",
                                        "required": "0",
                                        "type": "PlainText",
                                        "typesettings": {
                                            "placeholder": "",
                                            "maxLength": "",
                                            "multiline": "",
                                            "initialRows": "4"
                                        }
                                    },
                                    "new2": {
                                        "name": "Asset",
                                        "handle": "asset",
                                        "instructions": "",
                                        "required": "0",
                                        "type": "Assets",
                                        "typesettings": {
                                            "useSingleFolder": "",
                                            "sources": [
                                                "uploads"
                                            ],
                                            "defaultUploadLocationSource": "uploads",
                                            "defaultUploadLocationSubpath": "",
                                            "singleUploadLocationSource": "evidence",
                                            "singleUploadLocationSubpath": "",
                                            "restrictFiles": "",
                                            "limit": "",
                                            "viewMode": "list",
                                            "selectionLabel": ""
                                        }
                                    }
                                }
                            }
                        }
                    }
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
