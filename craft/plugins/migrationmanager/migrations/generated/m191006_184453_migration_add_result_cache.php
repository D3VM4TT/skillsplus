<?php

namespace Craft;

/**
 * Generated migration
 */
class m191006_184453_migration_add_result_cache extends BaseMigration
{
	/**
	 *
     * @return bool
	 */
	public function safeUp()
	{
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
