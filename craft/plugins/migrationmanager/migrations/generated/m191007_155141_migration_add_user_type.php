<?php

namespace Craft;

/**
 * Generated migration
 */
class m191007_155141_migration_add_user_type extends BaseMigration
{

    /**
    Migration manifest:
    
    FIELD
        - userType
        
    USERGROUP
        - users
            */

private $json = <<<JSON
{
    "settings": {
        "dependencies": [],
        "elements": {
            "fields": [
                {
                    "group": "Users",
                    "name": "User Type",
                    "handle": "userType",
                    "instructions": "",
                    "translatable": "0",
                    "required": false,
                    "type": "Dropdown",
                    "typesettings": {
                        "options": [
                            {
                                "label": "Member",
                                "value": "member",
                                "default": "1"
                            },
                            {
                                "label": "Manager",
                                "value": "manager",
                                "default": ""
                            }
                        ]
                    }
                }
            ],
            "userGroups": [
                {
                    "name": "Users",
                    "handle": "users",
                    "fieldLayout": {
                        "Structure": [
                            "userCompany",
                            "userTeam",
                            "userRole",
                            "managerLevel",
                            "userReadOnly",
                            "managerReadOnly",
                            "userDummyEmail",
                            "userLicenceSource"
                        ],
                        "Profile": [
                            "userStartDate",
                            "userDateOfBirth",
                            "userAddress",
                            "userTelephone",
                            "userExpiryDate",
                            "userCompanyName",
                            "userMembershipNumber",
                            "userType"
                        ],
                        "Payments": [
                            "userPayments"
                        ],
                        "Custom Scheme User Fields": [
                            "userCustomFields"
                        ],
                        "Legacy": [
                            "legacyId",
                            "legacyCompanyId",
                            "legacyEmail",
                            "legacyGroup",
                            "legacyJobRoleId",
                            "dataCleanManagersChildren",
                            "dataCleanManagersUserCompany",
                            "dataCleanJobRole",
                            "dataCleanUsername",
                            "dataCleanPassword",
                            "dataCleanResultCache"
                        ]
                    },
                    "requiredFields": [],
                    "permissions": [
                        "createentries:attempts",
                        "publishentries:attempts",
                        "deleteentries:attempts",
                        "publishpeerentries:attempts",
                        "deletepeerentries:attempts",
                        "editpeerentries:attempts",
                        "publishpeerentrydrafts:attempts",
                        "deletepeerentrydrafts:attempts",
                        "editpeerentrydrafts:attempts",
                        "editentries:attempts",
                        "createentries:results",
                        "publishentries:results",
                        "deleteentries:results",
                        "publishpeerentries:results",
                        "deletepeerentries:results",
                        "editpeerentries:results",
                        "publishpeerentrydrafts:results",
                        "deletepeerentrydrafts:results",
                        "editpeerentrydrafts:results",
                        "editentries:results",
                        "uploadtoassetsource:evidence",
                        "createsubfoldersinassetsource:evidence",
                        "removefromassetsource:evidence",
                        "viewassetsource:evidence"
                    ],
                    "settings": {
                        "requireEmailVerification": 0,
                        "allowPublicRegistration": 1,
                        "defaultGroup": "individuals"
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
