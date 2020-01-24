<?php

namespace Craft;

/**
 * Generated migration
 */
class m190531_130432_migration_add_print_archive_routes extends BaseMigration
{

    /**
    Migration manifest:
    
    ROUTE
        - 78
        - 79
        - 80
        - 81
        - 82
        - 97
        - 98
        - 83
        - 84
        - 85
        - 86
        - 87
        - 88
        - 89
        - 90
        - 91
        - 92
        - 93
        - 94
        - 95
        - 96
            */

private $json = <<<JSON
{
    "settings": {
        "dependencies": [],
        "elements": {
            "routes": [
                {
                    "urlParts": "%5B%22public%5C%2Fcertificate%5C%2F%22%2C%5B%22number%22%2C%22%5C%5Cd%2B%22%5D%2C%22%5C%2F%22%2C%5B%22number%22%2C%22%5C%5Cd%2B%22%5D%5D",
                    "urlPattern": "",
                    "template": "public/certificate",
                    "locale": ""
                },
                {
                    "urlParts": "%5B%22public%5C%2Fpassport%5C%2F%22%2C%5B%22%2A%22%2C%22%5B%5E%5C%5C%5C%2F%5D%2B%22%5D%5D",
                    "urlPattern": "",
                    "template": "public/passport",
                    "locale": ""
                },
                {
                    "urlParts": "%7B%220%22%3A%22management%5C%2F%22%2C%221%22%3A%5B%22%2A%22%2C%22%5B%5E%5C%5C%5C%2F%5D%2B%22%5D%2C%223%22%3A%22%5C%2Fedit%5C%2F%22%2C%224%22%3A%5B%22%2A%22%2C%22%5B%5E%5C%5C%5C%2F%5D%2B%22%5D%7D",
                    "urlPattern": "",
                    "template": "management/index",
                    "locale": ""
                },
                {
                    "urlParts": "%7B%220%22%3A%22management%5C%2F%22%2C%221%22%3A%5B%22%2A%22%2C%22%5B%5E%5C%5C%5C%2F%5D%2B%22%5D%2C%223%22%3A%22%5C%2Fnew%22%7D",
                    "urlPattern": "",
                    "template": "management/index",
                    "locale": ""
                },
                {
                    "urlParts": "%5B%22cpd%5C%2F%22%2C%5B%22number%22%2C%22%5C%5Cd%2B%22%5D%5D",
                    "urlPattern": "",
                    "template": "cpd/index",
                    "locale": ""
                },
                {
                    "urlParts": "%5B%22cpd%5C%2F%22%2C%5B%22number%22%2C%22%5C%5Cd%2B%22%5D%2C%22%5C%2Farchive%22%5D",
                    "urlPattern": "",
                    "template": "cpd/index",
                    "locale": ""
                },
                {
                    "urlParts": "%5B%22cpd%5C%2F%22%2C%5B%22number%22%2C%22%5C%5Cd%2B%22%5D%2C%22%5C%2Fprint%22%5D",
                    "urlPattern": "",
                    "template": "cpd/index",
                    "locale": ""
                },
                {
                    "urlParts": "%5B%22cpd%5C%2F%22%2C%5B%22number%22%2C%22%5C%5Cd%2B%22%5D%2C%22%5C%2F%22%2C%5B%22number%22%2C%22%5C%5Cd%2B%22%5D%2C%22%5C%2F%22%2C%5B%22number%22%2C%22%5C%5Cd%2B%22%5D%5D",
                    "urlPattern": "",
                    "template": "cpd/unit",
                    "locale": ""
                },
                {
                    "urlParts": "%7B%220%22%3A%22cpd%5C%2F%22%2C%221%22%3A%5B%22number%22%2C%22%5C%5Cd%2B%22%5D%2C%223%22%3A%22%5C%2F%22%2C%224%22%3A%5B%22number%22%2C%22%5C%5Cd%2B%22%5D%2C%226%22%3A%22%5C%2F%22%2C%227%22%3A%5B%22number%22%2C%22%5C%5Cd%2B%22%5D%2C%229%22%3A%22%5C%2Ftest%22%7D",
                    "urlPattern": "",
                    "template": "cpd/unit",
                    "locale": ""
                },
                {
                    "urlParts": "%5B%22cpd%5C%2F%22%2C%5B%22number%22%2C%22%5C%5Cd%2B%22%5D%2C%22%5C%2Fachievement%22%5D",
                    "urlPattern": "",
                    "template": "cpd/achievement",
                    "locale": ""
                },
                {
                    "urlParts": "%5B%22cpd%5C%2F%22%2C%5B%22number%22%2C%22%5C%5Cd%2B%22%5D%2C%22%5C%2Fachievement%5C%2F%22%2C%5B%22number%22%2C%22%5C%5Cd%2B%22%5D%5D",
                    "urlPattern": "",
                    "template": "cpd/achievement",
                    "locale": ""
                },
                {
                    "urlParts": "%5B%22cpd%5C%2F%22%2C%5B%22number%22%2C%22%5C%5Cd%2B%22%5D%2C%22%5C%2Fresult%5C%2F%22%2C%5B%22number%22%2C%22%5C%5Cd%2B%22%5D%5D",
                    "urlPattern": "",
                    "template": "cpd/achievement",
                    "locale": ""
                },
                {
                    "urlParts": "%5B%22reporting%5C%2Fcustom%5C%2Fedit%5C%2F%22%2C%5B%22%2A%22%2C%22%5B%5E%5C%5C%5C%2F%5D%2B%22%5D%5D",
                    "urlPattern": "",
                    "template": "reporting/custom/_form",
                    "locale": ""
                },
                {
                    "urlParts": "%5B%22reporting%5C%2Fcustom%5C%2Fnew%22%5D",
                    "urlPattern": "",
                    "template": "reporting/custom/_form",
                    "locale": ""
                },
                {
                    "urlParts": "%5B%22reporting%5C%2Fuser%5C%2F%22%2C%5B%22number%22%2C%22%5C%5Cd%2B%22%5D%5D",
                    "urlPattern": "",
                    "template": "reporting/user",
                    "locale": ""
                },
                {
                    "urlParts": "%7B%220%22%3A%22reporting%5C%2Fstandard%5C%2F%22%2C%221%22%3A%5B%22slug%22%2C%22%5B%5E%5C%5C%5C%2F%5D%2B%22%5D%2C%223%22%3A%22%5C%2Fcsv%22%7D",
                    "urlPattern": "",
                    "template": "reporting/standard",
                    "locale": ""
                },
                {
                    "urlParts": "%5B%22reporting%5C%2Fstandard%5C%2F%22%2C%5B%22slug%22%2C%22%5B%5E%5C%5C%5C%2F%5D%2B%22%5D%5D",
                    "urlPattern": "",
                    "template": "reporting/standard",
                    "locale": ""
                },
                {
                    "urlParts": "%5B%22profile%5C%2F%22%5D",
                    "urlPattern": "",
                    "template": "profile/index",
                    "locale": ""
                },
                {
                    "urlParts": "%5B%22public%5C%2Flicence%5C%2Fthanks%22%5D",
                    "urlPattern": "",
                    "template": "public/licence",
                    "locale": ""
                },
                {
                    "urlParts": "%5B%22management%5C%2Fusers%5C%2Fcompany%5C%2F%22%2C%5B%22number%22%2C%22%5C%5Cd%2B%22%5D%5D",
                    "urlPattern": "",
                    "template": "management/users",
                    "locale": ""
                },
                {
                    "urlParts": "%5B%22result%5C%2F%22%2C%5B%22number%22%2C%22%5C%5Cd%2B%22%5D%5D",
                    "urlPattern": "",
                    "template": "result/_form",
                    "locale": ""
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
