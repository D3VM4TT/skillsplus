<?php

namespace Craft;

/**
 * Generated migration
 */
class m190517_132651_migration_fix_user_edit_start_date extends BaseMigration
{

    /**
    Migration manifest:
    
    FIELD
        - userEditStartDate
        
    GLOBAL
        - userProfile
            */

private $json = <<<JSON
{
    "settings": {
        "dependencies": [],
        "elements": {
            "fields": [
                {
                    "group": "Globals",
                    "name": "User Edit Start Date",
                    "handle": "userEditStartDate",
                    "instructions": "",
                    "translatable": "0",
                    "required": false,
                    "type": "Lightswitch",
                    "typesettings": {
                        "default": ""
                    }
                }
            ],
            "globals": [
                {
                    "name": "User Profile",
                    "handle": "userProfile",
                    "fieldLayout": {
                        "Content": [
                            "userEditName",
                            "userEditEmail",
                            "userEditAddress",
                            "userEditTelephone",
                            "userEditDob",
                            "userEditStartDate",
                            "userEditRole",
                            "userEditPhoto",
                            "userEditCustomFields"
                        ]
                    },
                    "requiredFields": []
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
