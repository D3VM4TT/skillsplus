<?php

namespace Craft;

/**
 * Generated migration
 */
class m190517_155529_migration_assets_azureshare_folders extends BaseMigration
{

    /**
    Migration manifest:
    
    ASSETSOURCE
        - evidence
        - data
            */

private $json = <<<JSON
{
    "settings": {
        "dependencies": {
            "assetSources": [
                {
                    "name": "Evidence",
                    "handle": "evidence",
                    "type": "Local",
                    "sortOrder": "1",
                    "typesettings": {
                        "path": "{assetsPath}evidence/",
                        "publicURLs": "",
                        "url": ""
                    }
                },
                {
                    "name": "Data",
                    "handle": "data",
                    "type": "Local",
                    "sortOrder": "3",
                    "typesettings": {
                        "path": "{assetsPath}data/",
                        "publicURLs": "",
                        "url": ""
                    }
                }
            ]
        },
        "elements": {
            "assetSources": [
                {
                    "name": "Evidence",
                    "handle": "evidence",
                    "type": "Local",
                    "sortOrder": "1",
                    "typesettings": {
                        "path": "{assetsPath}evidence/",
                        "publicURLs": "",
                        "url": ""
                    },
                    "fieldLayout": []
                },
                {
                    "name": "Data",
                    "handle": "data",
                    "type": "Local",
                    "sortOrder": "3",
                    "typesettings": {
                        "path": "{assetsPath}data/",
                        "publicURLs": "",
                        "url": ""
                    },
                    "fieldLayout": []
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
