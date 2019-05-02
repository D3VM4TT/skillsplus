<?php

namespace Craft;

/**
 * Generated migration
 */
class m190502_131739_migration_column_layout_instructions extends BaseMigration
{

    /**
    Migration manifest:
    
    FIELD
        - columnLayout
            */

private $json = <<<JSON
{
    "settings": {
        "dependencies": [],
        "elements": {
            "fields": [
                {
                    "group": "Globals",
                    "name": "Column Layout",
                    "handle": "columnLayout",
                    "instructions": "In Globals this is the default layout as used on Result History.",
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
