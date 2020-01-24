<?php

namespace Craft;

/**
 * Generated migration
 */
class m191006_184453_migration_add_result_cache extends BaseMigration
{
    private $json = <<<JSON
{
    "settings": {
        "dependencies": [],
        "elements": {
            "fields": [
                {
                    "group": "Structure",
                    "name": "Data Clean Result Cache",
                    "handle": "dataCleanResultCache",
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
	 *
     * @return bool
	 */
	public function safeUp()
	{
        // import fields
	    craft()->migrationManager_migrations->import($this->json);

        craft()->db->createCommand()->createTable('lantra_result_cache', array(
            'userId' => array('column' => 'pk', 'required' => true)
        ), null, false);

        craft()->db->createCommand()->addForeignKey('lantra_result_cache', 'userId', 'users', 'id', 'CASCADE', null);

        // create unit columns
        $criteria = craft()->elements->getCriteria(ElementType::Entry);
        $criteria->section = 'units';
        $criteria->limit = null;
        foreach($criteria->find() as $unit) {
            craft()->entries->saveEntry($unit);
        }

        return true;
	}
}
