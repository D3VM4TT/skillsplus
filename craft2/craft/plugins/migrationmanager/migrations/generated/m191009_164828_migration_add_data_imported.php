<?php

namespace Craft;

/**
 * Generated migration
 */
class m191009_164828_migration_add_data_imported extends BaseMigration
{

    /**
    Migration manifest:
    
    FIELD
        - dataImported
        
    SECTION
        - companies
        - results
        
    CATEGORY
        - roles
        
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
        "dependencies": {
            "sections": [
                {
                    "name": "Companies",
                    "handle": "companies",
                    "type": "channel",
                    "enableVersioning": "0",
                    "hasUrls": "0",
                    "template": null,
                    "maxLevels": null,
                    "locales": {
                        "en_gb": "1"
                    },
                    "entrytypes": [
                        {
                            "sectionHandle": "companies",
                            "hasTitleField": "1",
                            "titleLabel": "Title",
                            "name": "Company",
                            "handle": "company",
                            "fieldLayout": {
                                "Company": [
                                    "companyParent",
                                    "companyPrimaryManagers",
                                    "companySecondaryManagers",
                                    "companyRemainingLicences"
                                ],
                                "Legacy": [
                                    "legacyId",
                                    "legacyParentId",
                                    "dataImported"
                                ],
                                "Hierarchy": [
                                    "companyLabel",
                                    "dataCleanCompanyParent"
                                ]
                            },
                            "requiredFields": []
                        }
                    ]
                },
                {
                    "name": "Results",
                    "handle": "results",
                    "type": "channel",
                    "enableVersioning": "1",
                    "hasUrls": "0",
                    "template": null,
                    "maxLevels": null,
                    "locales": {
                        "en_gb": "1"
                    },
                    "entrytypes": [
                        {
                            "sectionHandle": "results",
                            "hasTitleField": "0",
                            "titleLabel": null,
                            "titleFormat": "[unit {resultUnit.first.id}] {author.firstName} {author.lastName}",
                            "name": "Unit Result",
                            "handle": "unitResult",
                            "fieldLayout": {
                                "Result": [
                                    "resultOwner",
                                    "resultStatus",
                                    "resultUnit",
                                    "resultEndorsedDate",
                                    "resultEndorsedUser",
                                    "unitEndorsementManagerLevel"
                                ],
                                "Evidence": [
                                    "resultEvidence",
                                    "resultStartDate",
                                    "resultFinishDate",
                                    "resultLocation",
                                    "resultHours",
                                    "resultNotes",
                                    "resultValue"
                                ],
                                "Test": [
                                    "resultAttempts",
                                    "resultScore"
                                ],
                                "Comments": [
                                    "resultComments"
                                ],
                                "Custom Values": [
                                    "resultCustom"
                                ],
                                "Legacy": [
                                    "legacyResultFiles",
                                    "dataImported"
                                ]
                            },
                            "requiredFields": []
                        },
                        {
                            "sectionHandle": "results",
                            "hasTitleField": "0",
                            "titleLabel": null,
                            "titleFormat": "[module {resultModule.first().id}] {author.firstName} {author.lastName} ",
                            "name": "Module Result",
                            "handle": "moduleResult",
                            "fieldLayout": {
                                "Result": [
                                    "resultModule",
                                    "resultStatus"
                                ],
                                "Comments": [
                                    "resultComments"
                                ]
                            },
                            "requiredFields": []
                        },
                        {
                            "sectionHandle": "results",
                            "hasTitleField": "1",
                            "titleLabel": "",
                            "name": "User Result",
                            "handle": "userResult",
                            "fieldLayout": {
                                "Result": [
                                    "resultOwner",
                                    "resultStatus",
                                    "resultEndorsedDate",
                                    "resultEndorsedUser"
                                ],
                                "Evidence": [
                                    "resultEvidence",
                                    "resultStartDate",
                                    "resultFinishDate",
                                    "resultLocation",
                                    "resultHours",
                                    "resultNotes",
                                    "unitEndorsementManagerLevel",
                                    "resultModule",
                                    "resultUnit",
                                    "resultValue"
                                ],
                                "Comments": [
                                    "resultComments"
                                ],
                                "Custom Values": [
                                    "resultCustom"
                                ],
                                "Legacy": [
                                    "legacyResultFiles",
                                    "dataImported"
                                ]
                            },
                            "requiredFields": []
                        }
                    ]
                }
            ],
            "categories": [
                {
                    "name": "Job Roles",
                    "handle": "roles",
                    "hasUrls": "0",
                    "template": null,
                    "maxLevels": "1",
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
            "fields": [
                {
                    "group": "Legacy",
                    "name": "Data Imported",
                    "handle": "dataImported",
                    "instructions": "",
                    "translatable": "0",
                    "required": false,
                    "type": "Lightswitch",
                    "typesettings": {
                        "default": ""
                    }
                }
            ],
            "sections": [
                {
                    "name": "Companies",
                    "handle": "companies",
                    "type": "channel",
                    "enableVersioning": "0",
                    "hasUrls": "0",
                    "template": null,
                    "maxLevels": null,
                    "locales": {
                        "en_gb": "1"
                    },
                    "entrytypes": [
                        {
                            "sectionHandle": "companies",
                            "hasTitleField": "1",
                            "titleLabel": "Title",
                            "name": "Company",
                            "handle": "company",
                            "fieldLayout": {
                                "Company": [
                                    "companyParent",
                                    "companyPrimaryManagers",
                                    "companySecondaryManagers",
                                    "companyRemainingLicences"
                                ],
                                "Legacy": [
                                    "legacyId",
                                    "legacyParentId",
                                    "dataImported"
                                ],
                                "Hierarchy": [
                                    "companyLabel",
                                    "dataCleanCompanyParent"
                                ]
                            },
                            "requiredFields": []
                        }
                    ]
                },
                {
                    "name": "Results",
                    "handle": "results",
                    "type": "channel",
                    "enableVersioning": "1",
                    "hasUrls": "0",
                    "template": null,
                    "maxLevels": null,
                    "locales": {
                        "en_gb": "1"
                    },
                    "entrytypes": [
                        {
                            "sectionHandle": "results",
                            "hasTitleField": "0",
                            "titleLabel": null,
                            "titleFormat": "[unit {resultUnit.first.id}] {author.firstName} {author.lastName}",
                            "name": "Unit Result",
                            "handle": "unitResult",
                            "fieldLayout": {
                                "Result": [
                                    "resultOwner",
                                    "resultStatus",
                                    "resultUnit",
                                    "resultEndorsedDate",
                                    "resultEndorsedUser",
                                    "unitEndorsementManagerLevel"
                                ],
                                "Evidence": [
                                    "resultEvidence",
                                    "resultStartDate",
                                    "resultFinishDate",
                                    "resultLocation",
                                    "resultHours",
                                    "resultNotes",
                                    "resultValue"
                                ],
                                "Test": [
                                    "resultAttempts",
                                    "resultScore"
                                ],
                                "Comments": [
                                    "resultComments"
                                ],
                                "Custom Values": [
                                    "resultCustom"
                                ],
                                "Legacy": [
                                    "legacyResultFiles",
                                    "dataImported"
                                ]
                            },
                            "requiredFields": []
                        },
                        {
                            "sectionHandle": "results",
                            "hasTitleField": "0",
                            "titleLabel": null,
                            "titleFormat": "[module {resultModule.first().id}] {author.firstName} {author.lastName} ",
                            "name": "Module Result",
                            "handle": "moduleResult",
                            "fieldLayout": {
                                "Result": [
                                    "resultModule",
                                    "resultStatus"
                                ],
                                "Comments": [
                                    "resultComments"
                                ]
                            },
                            "requiredFields": []
                        },
                        {
                            "sectionHandle": "results",
                            "hasTitleField": "1",
                            "titleLabel": "",
                            "name": "User Result",
                            "handle": "userResult",
                            "fieldLayout": {
                                "Result": [
                                    "resultOwner",
                                    "resultStatus",
                                    "resultEndorsedDate",
                                    "resultEndorsedUser"
                                ],
                                "Evidence": [
                                    "resultEvidence",
                                    "resultStartDate",
                                    "resultFinishDate",
                                    "resultLocation",
                                    "resultHours",
                                    "resultNotes",
                                    "unitEndorsementManagerLevel",
                                    "resultModule",
                                    "resultUnit",
                                    "resultValue"
                                ],
                                "Comments": [
                                    "resultComments"
                                ],
                                "Custom Values": [
                                    "resultCustom"
                                ],
                                "Legacy": [
                                    "legacyResultFiles",
                                    "dataImported"
                                ]
                            },
                            "requiredFields": []
                        }
                    ]
                }
            ],
            "categories": [
                {
                    "name": "Job Roles",
                    "handle": "roles",
                    "hasUrls": "0",
                    "template": null,
                    "maxLevels": "1",
                    "locales": {
                        "en_gb": {
                            "locale": "en_gb",
                            "urlFormat": null,
                            "nestedUrlFormat": null
                        }
                    },
                    "fieldLayout": {
                        "Fields": [
                            "legacyId",
                            "dataImported"
                        ]
                    },
                    "requiredFields": []
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
                            "dataCleanResultCache",
                            "dataImported"
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
                            "dataCleanResultCache",
                            "dataImported"
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
                            "dataCleanResultCache",
                            "dataImported"
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
                            "dataCleanResultCache",
                            "dataImported"
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
                            "dataCleanResultCache",
                            "dataImported"
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
