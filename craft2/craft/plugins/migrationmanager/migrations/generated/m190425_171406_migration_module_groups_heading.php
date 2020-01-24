<?php

namespace Craft;

/**
 * Generated migration
 */
class m190425_171406_migration_module_groups_heading extends BaseMigration
{

    /**
    Migration manifest:
    
    CATEGORY
        - moduleGroups
            */

private $json = <<<JSON
{
    "settings": {
        "dependencies": {
            "categories": [
                {
                    "name": "Module Groups",
                    "handle": "moduleGroups",
                    "hasUrls": "0",
                    "template": null,
                    "maxLevels": null,
                    "locales": {
                        "en_gb": {
                            "locale": "en_gb",
                            "urlFormat": null,
                            "nestedUrlFormat": null
                        }
                    }
                }
            ]
        },
        "elements": {
            "categories": [
                {
                    "name": "Module Groups",
                    "handle": "moduleGroups",
                    "hasUrls": "0",
                    "template": null,
                    "maxLevels": null,
                    "locales": {
                        "en_gb": {
                            "locale": "en_gb",
                            "urlFormat": null,
                            "nestedUrlFormat": null
                        }
                    },
                    "fieldLayout": {
                        "Settings": [
                            "columnLayout",
                            "pageHeading",
                            "addAchievement"
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
