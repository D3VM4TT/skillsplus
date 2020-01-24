<?php

namespace Craft;

/**
 * Generated migration
 */
class m191003_120000_add_lantra_queue extends BaseMigration
{
    /**
     * Any migration code in here is wrapped inside of a transaction.
     * Returning false will rollback the migration
     *
     * @return bool
     */
    public function safeUp()
    {
        $mysql = "CREATE TABLE IF NOT EXISTS `craft_lantra_queue` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `elementId` int(11) DEFAULT NULL,
            `status` text,
            `priority` int(1),
            `dateCreated` datetime,
            `dateUpdated` datetime,
            `uid` varchar(45) DEFAULT NULL,
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;";

        craft()->db->createCommand($mysql)->query();
    }
}