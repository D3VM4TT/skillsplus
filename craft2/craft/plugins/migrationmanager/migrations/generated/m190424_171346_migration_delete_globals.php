<?php

namespace Craft;

/**
 * Generated migration
 */
class m190424_171346_migration_delete_globals extends BaseMigration
{


	/**
	 * Any migration code in here is wrapped inside of a transaction.
	 * Returning false will rollback the migration
	 *
	 * @return bool
	 */
	public function safeUp()
	{
	    $notifyGlobals = [
            'notifyFooter',
            'notifySubjectBlockedResult',
            'notifySubjectEndorsementResult',
            'notifySubjectLicencesRemaining',
            'notifySubjectManagerSummary',
            'notifySubjectModuleResult',
            'notifySubjectSchemeExpiry',
            'notifySubjectUserExpiry'
        ];
	    foreach ($notifyGlobals as $name) {
            $field = craft()->fields->getFieldByHandle($name);
            if ($field) {
                craft()->fields->deleteFieldById($field->id);
            }
        }
        craft()->globals->deleteSetById(1138);
        return true;
	}
}
