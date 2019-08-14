<?php

namespace Craft;

/**
 * Generated migration
 */
class m190814_181612_migration_user_read_only extends BaseMigration
{

    /**
    Migration manifest:
    
    FIELD
        - userReadOnly
        
    USERGROUP
        - companyManagers
        - individuals
        - schemeManagers
        - teamManagers
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
                    "name": "User Read Only",
                    "handle": "userReadOnly",
                    "instructions": "",
                    "translatable": "0",
                    "required": false,
                    "type": "Lightswitch",
                    "typesettings": {
                        "default": ""
                    }
                }
            ],
            "userGroups": [
                {
                    "name": "Company Managers",
                    "handle": "companyManagers",
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
                            "userMembershipNumber"
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
                            "dataCleanPassword"
                        ]
                    },
                    "requiredFields": [],
                    "permissions": [
                        "registerusers",
                        "changeuseremails",
                        "administrateusers",
                        "assignuserpermissions",
                        "assignusergroup:2",
                        "assignusergroup:1",
                        "assignusergroup:3",
                        "assignusergroup:4",
                        "assignusergroups",
                        "editusers",
                        "deleteusers",
                        "createentries:reports",
                        "publishentries:reports",
                        "deleteentries:reports",
                        "publishpeerentries:reports",
                        "deletepeerentries:reports",
                        "editpeerentries:reports",
                        "publishpeerentrydrafts:reports",
                        "deletepeerentrydrafts:reports",
                        "editpeerentrydrafts:reports",
                        "editentries:reports",
                        "createentries:results",
                        "publishentries:results",
                        "publishpeerentries:results",
                        "editpeerentries:results",
                        "publishpeerentrydrafts:results",
                        "editpeerentrydrafts:results",
                        "editentries:results",
                        "createentries:teams",
                        "publishentries:teams",
                        "deleteentries:teams",
                        "publishpeerentries:teams",
                        "deletepeerentries:teams",
                        "editpeerentries:teams",
                        "publishpeerentrydrafts:teams",
                        "deletepeerentrydrafts:teams",
                        "editpeerentrydrafts:teams",
                        "editentries:teams",
                        "editcategories:roles",
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
                },
                {
                    "name": "Individuals",
                    "handle": "individuals",
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
                            "userMembershipNumber"
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
                            "dataCleanPassword"
                        ]
                    },
                    "requiredFields": [],
                    "permissions": [],
                    "settings": {
                        "requireEmailVerification": 0,
                        "allowPublicRegistration": 1,
                        "defaultGroup": "individuals"
                    }
                },
                {
                    "name": "Scheme Managers",
                    "handle": "schemeManagers",
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
                            "userMembershipNumber"
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
                            "dataCleanPassword"
                        ]
                    },
                    "requiredFields": [],
                    "permissions": [
                        "registerusers",
                        "changeuseremails",
                        "administrateusers",
                        "assignuserpermissions",
                        "assignusergroup:2",
                        "assignusergroup:1",
                        "assignusergroup:3",
                        "assignusergroup:4",
                        "assignusergroups",
                        "editusers",
                        "deleteusers",
                        "createentries:companies",
                        "publishentries:companies",
                        "deleteentries:companies",
                        "publishpeerentries:companies",
                        "deletepeerentries:companies",
                        "editpeerentries:companies",
                        "publishpeerentrydrafts:companies",
                        "deletepeerentrydrafts:companies",
                        "editpeerentrydrafts:companies",
                        "editentries:companies",
                        "createentries:modules",
                        "publishentries:modules",
                        "deleteentries:modules",
                        "publishpeerentries:modules",
                        "deletepeerentries:modules",
                        "editpeerentries:modules",
                        "publishpeerentrydrafts:modules",
                        "deletepeerentrydrafts:modules",
                        "editpeerentrydrafts:modules",
                        "editentries:modules",
                        "createentries:reports",
                        "publishentries:reports",
                        "deleteentries:reports",
                        "publishpeerentries:reports",
                        "deletepeerentries:reports",
                        "editpeerentries:reports",
                        "publishpeerentrydrafts:reports",
                        "deletepeerentrydrafts:reports",
                        "editpeerentrydrafts:reports",
                        "editentries:reports",
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
                        "createentries:teams",
                        "publishentries:teams",
                        "deleteentries:teams",
                        "publishpeerentries:teams",
                        "deletepeerentries:teams",
                        "editpeerentries:teams",
                        "publishpeerentrydrafts:teams",
                        "deletepeerentrydrafts:teams",
                        "editpeerentrydrafts:teams",
                        "editentries:teams",
                        "createentries:units",
                        "publishentries:units",
                        "deleteentries:units",
                        "publishpeerentries:units",
                        "deletepeerentries:units",
                        "editpeerentries:units",
                        "publishpeerentrydrafts:units",
                        "deletepeerentrydrafts:units",
                        "editpeerentrydrafts:units",
                        "editentries:units",
                        "editcategories:roles",
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
                },
                {
                    "name": "Team Managers",
                    "handle": "teamManagers",
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
                            "userMembershipNumber"
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
                            "dataCleanPassword"
                        ]
                    },
                    "requiredFields": [],
                    "permissions": [
                        "registerusers",
                        "changeuseremails",
                        "administrateusers",
                        "assignuserpermissions",
                        "assignusergroup:2",
                        "assignusergroup:1",
                        "assignusergroup:3",
                        "assignusergroup:4",
                        "assignusergroups",
                        "editusers",
                        "deleteusers",
                        "createentries:reports",
                        "publishentries:reports",
                        "deleteentries:reports",
                        "publishpeerentries:reports",
                        "deletepeerentries:reports",
                        "editpeerentries:reports",
                        "publishpeerentrydrafts:reports",
                        "deletepeerentrydrafts:reports",
                        "editpeerentrydrafts:reports",
                        "editentries:reports",
                        "editcategories:roles"
                    ],
                    "settings": {
                        "requireEmailVerification": 0,
                        "allowPublicRegistration": 1,
                        "defaultGroup": "individuals"
                    }
                },
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
                            "userMembershipNumber"
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
                            "dataCleanPassword"
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
