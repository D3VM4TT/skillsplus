<?php

namespace Craft;

/**
 * Generated migration
 */
class m190619_123431_migration_lantra_import extends BaseMigration
{
	/**
	 * Any migration code in here is wrapped inside of a transaction.
	 * Returning false will rollback the migration
	 *
	 * @return bool
	 */
	public function safeUp()
	{
        $mysql = "CREATE TABLE IF NOT EXISTS `craft_lantra_import` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `type` varchar(20) DEFAULT NULL,
            `data` text,
            `processed` char(1) DEFAULT '0',
            `dateCreated` text,
            `dateUpdated` text,
            `uid` varchar(45) DEFAULT NULL,
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=28465 DEFAULT CHARSET=latin1;";

        craft()->db->createCommand($mysql)->query();
	}
}
