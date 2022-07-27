<?php
/**
 * Lantra Skills Plus for Craft CMS 3.x
 *
 * @link      https:##coffeebean.design
 * @copyright Copyright (c) 2020 Coffee Bean Design
 */

namespace lantra\sp\controllers;

use Craft;

use lantra\sp\Plugin as Lantra;

class DbController extends BaseController
{

    public $allowAnonymous = array(
        'actionCleanse',
    );

    /**
     * Cleanse the db
     *
     * @return null
     * @throws \Exception
     */
    function actionCleanse()
    {
        $dbConfig = Craft::$app->getConfig()->getDb();
        $database = $dbConfig->cleansedDatabase;

        ## create current backup
        $db = Craft::$app->getDb();
        $backupPath = $db->getBackupFilePath();
        if (!$db->backupTo($backupPath)) {
            die('Could not backup current database.');
        }

        ## prepend sql to run on cleansed db
        $mysql = "USE " . $database . ";\n\n" . file_get_contents($backupPath);

        ## import backup
        Craft::$app->db->createCommand($mysql)->execute();

        ## get all users
        $mysql = 'SELECT * FROM {{%users}}';
        $users = Craft::$app->dbCleansed->createCommand($mysql)->excecute();

        foreach($users as $user) {
            $id = $user['id'];
            $uid = $user['uid'];
            $email = $id . '@lantra.co.uk';
            $cleansedUser = [
                'username'  => $email,
                'email'     => $email,
                'firstName' => 'Lantra',
                'lastName'  => $uid,
                'photo'     => null
            ];

            ## cleanse user record
            $query = Craft::$app->cleansedDb->createCommand();
            $query->update('users', $cleansedUser, 'id=:id', [':id'=> $id]);

            $cleansedProfile = [
                'field_userTelephone' => '',
                'field_userStartDate' => '',
                'field_userDateOfBirth' => '',
                'field_userAddress' => '',
                'field_customFields' => '',
            ];

            ## cleanse profile data
            $query = Craft::$app->cleansedDb->createCommand();
            $query->update('content', $cleansedProfile, 'elementId=:id', array(':id'=> $id));
        }

        ## cleanse all paypal payments
        $cleansedPayment = [
            'field_paypal_payer_email'  => '',
        ];
        $query = Craft::$app->cleansedDb->createCommand();
        $query->update('matrixcontent_userpayments', $cleansedPayment, '1=1');

        $total = count($users);
        die('Cleansed database updated. ' . $total . ' users updated.');
    }
}