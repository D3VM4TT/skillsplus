<?php

namespace Craft;

/**
 * Generated migration
 */
class m190509_143903_migration_report_fields extends BaseMigration
{

    /**
    Migration manifest:
    
    FIELD
        - reportIncludeRequired
        - reportResultExpiry
            */

private $json = <<<JSON
{
    "settings": {
        "dependencies": [],
        "elements": {
            "fields": [
                {
                    "group": "Reports",
                    "name": "Report Include Required",
                    "handle": "reportIncludeRequired",
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
                                "value": "365+",
                                "default": "1"
                            }
                        ]
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
