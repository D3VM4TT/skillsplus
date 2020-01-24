<?php

namespace Craft;

/**
 * Generated migration
 */
class m200109_161240_migration_field_unitType extends BaseMigration
{

    /**
    Migration manifest:
    
    FIELD
        - unitType
            */

private $json = <<<JSON
{
    "settings": {
        "dependencies": [],
        "elements": {
            "fields": [
                {
                    "group": "Modules",
                    "name": "Unit Type",
                    "handle": "unitType",
                    "instructions": "",
                    "translatable": "0",
                    "required": false,
                    "type": "Dropdown",
                    "typesettings": {
                        "options": [
                            {
                                "label": "Evidence",
                                "value": "evidence",
                                "default": "1"
                            },
                            {
                                "label": "Assessment",
                                "value": "elearning",
                                "default": ""
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
