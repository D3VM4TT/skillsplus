<?php

namespace Craft;

/**
 * Generated migration
 */
class m190508_175157_migration_update_reports extends BaseMigration
{

    /**
    Migration manifest:
    
    FIELD
        - reportAllCompanies
        - reportAllModules
        - reportAllRoles
        - reportAllTeams
        - reportAllUnits
        - reportCompanies
        - reportCount
        - reportData
        - reportDescription
        - reportDisplayField
        - reportLastSentDate
        - reportModules
        - reportRecipients
        - reportResultExpiry
        - reportResultType
        - reportRoles
        - reportSendFrequency
        - reportSendValue
        - reportTeams
        - reportType
        - reportUnits
        
    SECTION
        - reports
        
    ROUTE
        - 24
        - 25
        - 26
        - 27
        - 28
        - 29
        - 30
        - 31
        - 32
        - 33
        - 34
        - 37
        - 38
        - 39
        - 40
        - 41
        - 42
            */

private $json = <<<JSON
{
    "settings": {
        "dependencies": {
            "sections": [
                {
                    "name": "Reports",
                    "handle": "reports",
                    "type": "channel",
                    "enableVersioning": "0",
                    "hasUrls": "1",
                    "template": "reporting/custom/_entry",
                    "maxLevels": null,
                    "locales": {
                        "en_gb": {
                            "locale": "en_gb",
                            "urlFormat": "reporting/custom/{id}",
                            "nestedUrlFormat": null,
                            "enabledByDefault": "1"
                        }
                    },
                    "entrytypes": [
                        {
                            "sectionHandle": "reports",
                            "hasTitleField": "1",
                            "titleLabel": "Title",
                            "name": "Reports",
                            "handle": "reports",
                            "fieldLayout": {
                                "Reports": [
                                    "reportDescription",
                                    "reportType",
                                    "reportCount",
                                    "reportLastSentDate"
                                ],
                                "Criteria": [
                                    "reportCompanies",
                                    "reportAllCompanies",
                                    "reportTeams",
                                    "reportAllTeams",
                                    "reportRoles",
                                    "reportAllRoles",
                                    "reportUnits",
                                    "reportAllUnits",
                                    "reportModules",
                                    "reportAllModules",
                                    "reportResultExpiry",
                                    "reportDisplayField",
                                    "reportResultType"
                                ],
                                "Recipients": [
                                    "reportRecipients",
                                    "reportSendFrequency",
                                    "reportSendValue"
                                ],
                                "Data": [
                                    "reportData"
                                ]
                            },
                            "requiredFields": []
                        }
                    ]
                }
            ]
        },
        "elements": {
            "fields": [
                {
                    "group": "Reports",
                    "name": "Report All Companies",
                    "handle": "reportAllCompanies",
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
                    "name": "Report All Modules",
                    "handle": "reportAllModules",
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
                    "name": "Report All Roles",
                    "handle": "reportAllRoles",
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
                    "name": "Report All Teams",
                    "handle": "reportAllTeams",
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
                    "name": "Report All Units",
                    "handle": "reportAllUnits",
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
                    "name": "Report Companies",
                    "handle": "reportCompanies",
                    "instructions": "",
                    "translatable": "0",
                    "required": false,
                    "type": "Entries",
                    "typesettings": {
                        "sources": [
                            "companies"
                        ],
                        "limit": "",
                        "selectionLabel": ""
                    }
                },
                {
                    "group": "Reports",
                    "name": "Report Count",
                    "handle": "reportCount",
                    "instructions": "",
                    "translatable": "0",
                    "required": false,
                    "type": "Number",
                    "typesettings": {
                        "min": "0",
                        "max": "",
                        "decimals": "0"
                    }
                },
                {
                    "group": "Reports",
                    "name": "Report Data",
                    "handle": "reportData",
                    "instructions": "",
                    "translatable": "0",
                    "required": false,
                    "type": "Assets",
                    "typesettings": {
                        "useSingleFolder": "",
                        "sources": [
                            "data"
                        ],
                        "defaultUploadLocationSource": "data",
                        "defaultUploadLocationSubpath": "",
                        "singleUploadLocationSource": "evidence",
                        "singleUploadLocationSubpath": "",
                        "restrictFiles": "",
                        "allowedKinds": [
                            "text"
                        ],
                        "limit": "",
                        "viewMode": "list",
                        "selectionLabel": ""
                    }
                },
                {
                    "group": "Reports",
                    "name": "Report Description",
                    "handle": "reportDescription",
                    "instructions": "",
                    "translatable": "0",
                    "required": false,
                    "type": "PlainText",
                    "typesettings": {
                        "placeholder": "",
                        "maxLength": "",
                        "multiline": "1",
                        "initialRows": "2"
                    }
                },
                {
                    "group": "Reports",
                    "name": "Report Display Field",
                    "handle": "reportDisplayField",
                    "instructions": "",
                    "translatable": "0",
                    "required": false,
                    "type": "Dropdown",
                    "typesettings": {
                        "options": [
                            {
                                "label": "Expiry Date",
                                "value": "expiryDate",
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
                            }
                        ]
                    }
                },
                {
                    "group": "Reports",
                    "name": "Report Last Sent Date",
                    "handle": "reportLastSentDate",
                    "instructions": "",
                    "translatable": "0",
                    "required": false,
                    "type": "Date",
                    "typesettings": {
                        "minuteIncrement": "30",
                        "showDate": 1,
                        "showTime": 0
                    }
                },
                {
                    "group": "Reports",
                    "name": "Report Modules",
                    "handle": "reportModules",
                    "instructions": "",
                    "translatable": "0",
                    "required": false,
                    "type": "Entries",
                    "typesettings": {
                        "sources": [
                            "modules"
                        ],
                        "limit": "",
                        "selectionLabel": ""
                    }
                },
                {
                    "group": "Reports",
                    "name": "Report Recipients",
                    "handle": "reportRecipients",
                    "instructions": "",
                    "translatable": "0",
                    "required": false,
                    "type": "Users",
                    "typesettings": {
                        "sources": [
                            "companyManagers",
                            "schemeManagers",
                            "teamManagers"
                        ],
                        "limit": "",
                        "selectionLabel": ""
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
                                "label": "0",
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
                                "value": "",
                                "default": ""
                            }
                        ]
                    }
                },
                {
                    "group": "Reports",
                    "name": "Report Result Type",
                    "handle": "reportResultType",
                    "instructions": "",
                    "translatable": "0",
                    "required": false,
                    "type": "Dropdown",
                    "typesettings": {
                        "options": [
                            {
                                "label": "All",
                                "value": "all",
                                "default": ""
                            },
                            {
                                "label": "Unit Result",
                                "value": "unitResult",
                                "default": ""
                            },
                            {
                                "label": "User Result",
                                "value": "userResult",
                                "default": ""
                            }
                        ]
                    }
                },
                {
                    "group": "Reports",
                    "name": "Report Roles",
                    "handle": "reportRoles",
                    "instructions": "",
                    "translatable": "0",
                    "required": false,
                    "type": "Categories",
                    "typesettings": {
                        "source": "roles",
                        "limit": "",
                        "selectionLabel": ""
                    }
                },
                {
                    "group": "Reports",
                    "name": "Report Send Frequency",
                    "handle": "reportSendFrequency",
                    "instructions": "",
                    "translatable": "0",
                    "required": false,
                    "type": "Dropdown",
                    "typesettings": {
                        "options": [
                            {
                                "label": "Never",
                                "value": "never",
                                "default": ""
                            },
                            {
                                "label": "Weekly",
                                "value": "weekly",
                                "default": ""
                            },
                            {
                                "label": "Monthly",
                                "value": "monthly",
                                "default": ""
                            }
                        ]
                    }
                },
                {
                    "group": "Reports",
                    "name": "Report Send Value",
                    "handle": "reportSendValue",
                    "instructions": "",
                    "translatable": "0",
                    "required": false,
                    "type": "Number",
                    "typesettings": {
                        "min": "0",
                        "max": "",
                        "decimals": "0"
                    }
                },
                {
                    "group": "Reports",
                    "name": "Report Teams",
                    "handle": "reportTeams",
                    "instructions": "",
                    "translatable": "0",
                    "required": false,
                    "type": "Entries",
                    "typesettings": {
                        "sources": [
                            "teams"
                        ],
                        "limit": "",
                        "selectionLabel": ""
                    }
                },
                {
                    "group": "Reports",
                    "name": "Report Type",
                    "handle": "reportType",
                    "instructions": "",
                    "translatable": "0",
                    "required": false,
                    "type": "Dropdown",
                    "typesettings": {
                        "options": [
                            {
                                "label": "Users",
                                "value": "users",
                                "default": ""
                            },
                            {
                                "label": "Required",
                                "value": "required",
                                "default": ""
                            },
                            {
                                "label": "Results",
                                "value": "results",
                                "default": ""
                            }
                        ]
                    }
                },
                {
                    "group": "Reports",
                    "name": "Report Units",
                    "handle": "reportUnits",
                    "instructions": "",
                    "translatable": "0",
                    "required": false,
                    "type": "Entries",
                    "typesettings": {
                        "sources": [
                            "units"
                        ],
                        "limit": "",
                        "selectionLabel": ""
                    }
                }
            ],
            "sections": [
                {
                    "name": "Reports",
                    "handle": "reports",
                    "type": "channel",
                    "enableVersioning": "0",
                    "hasUrls": "1",
                    "template": "reporting/custom/_entry",
                    "maxLevels": null,
                    "locales": {
                        "en_gb": {
                            "locale": "en_gb",
                            "urlFormat": "reporting/custom/{id}",
                            "nestedUrlFormat": null,
                            "enabledByDefault": "1"
                        }
                    },
                    "entrytypes": [
                        {
                            "sectionHandle": "reports",
                            "hasTitleField": "1",
                            "titleLabel": "Title",
                            "name": "Reports",
                            "handle": "reports",
                            "fieldLayout": {
                                "Reports": [
                                    "reportDescription",
                                    "reportType",
                                    "reportCount",
                                    "reportLastSentDate"
                                ],
                                "Criteria": [
                                    "reportCompanies",
                                    "reportAllCompanies",
                                    "reportTeams",
                                    "reportAllTeams",
                                    "reportRoles",
                                    "reportAllRoles",
                                    "reportUnits",
                                    "reportAllUnits",
                                    "reportModules",
                                    "reportAllModules",
                                    "reportResultExpiry",
                                    "reportDisplayField",
                                    "reportResultType"
                                ],
                                "Recipients": [
                                    "reportRecipients",
                                    "reportSendFrequency",
                                    "reportSendValue"
                                ],
                                "Data": [
                                    "reportData"
                                ]
                            },
                            "requiredFields": []
                        }
                    ]
                }
            ],
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
                    "urlParts": "%5B%22reporting%5C%2Fcustom%5C%2Fedit%5C%2F%22%2C%5B%22number%22%2C%22%5C%5Cd%2B%22%5D%5D",
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
