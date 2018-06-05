<?php

namespace Craft;

class Lantra_DbController extends Lantra_BaseController
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
    function actionCleanse() {

        $server = craft()->config->get('cleansedServer', ConfigFile::Db);
        $user = craft()->config->get('cleansedUser', ConfigFile::Db);
        $password = craft()->config->get('cleansedPassword', ConfigFile::Db);
        $database = craft()->config->get('cleansedDatabase', ConfigFile::Db);

        // create current backup
        $backup = new DbBackup();
        if (($backupFile = $backup->run()) == false) {
            die('Could not backup current database.');
        }

        // prepend sql to run on cleansed db
        $sql = "USE " . $database . ";\n\n" . file_get_contents($backupFile);

        // import backup
        craft()->db->createCommand()->setText($sql)->execute();

        // create connection to cleansed db
        $cleansedDb =  Craft::createComponent(array(
            'emulatePrepare'    => true,
            'charset'           => 'utf8',
            'tablePrefix'       => 'craft_',
            'class'             => 'Craft\DbConnection',
            'autoConnect'       => true,
        ));

        $cleansedDb->connectionString = 'mysql:host=' . $server .';dbname='. $database . ';port=3306';
        $cleansedDb->username = $user;
        $cleansedDb->password = $password;

        // craft()->setComponent('dbCleansed', $cleansedDb);

        // get all users
        $query = $cleansedDb->createCommand();
        $users = $query->from('users')->queryAll();

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

            // cleanse user record
            $query = $cleansedDb->createCommand();
            $query->update('users', $cleansedUser, 'id=:id', array(':id'=> $id));

            $cleansedProfile = [
                'field_userTelephone' => ''
            ];

            // cleanse profile data
            $query = $cleansedDb->createCommand();
            $query->update('content', $cleansedProfile, 'elementId=:id', array(':id'=> $id));
        }

        // cleanse all paypal payments
        $cleansedPayment = [
            'field_paypal_payer_email'  => '',
        ];
        $query = $cleansedDb->createCommand();
        $query->update('matrixcontent_userpayments', $cleansedPayment, '1=1');

        $total = count($users);
        die('Cleansed database updated with data, ' . $total . ' users updated.');
    }
}