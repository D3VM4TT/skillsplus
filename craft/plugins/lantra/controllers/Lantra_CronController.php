<?php

namespace Craft;

class Lantra_CronController extends Lantra_BaseController {

    public $allowAnonymous = array('actionRun');

    /**
     * Run all cron jobs
     *
     * @return null
     * @throws Exception
     */
    function actionRun() {
        $frequency = craft()->request->getParam('frequency');
        if ($frequency == 'daily') {
            Craft::log("Daily Cron",LogLevel::Info, true, 'cron', 'lantra');
            # stop all notifications but user generated automatic report
            # $this->notifyUserExpiry();
            # $this->expireIndividualUsers();
            craft()->lantra_reports->sendDailyReports();
        }
        if ($frequency == 'weekly') {
            Craft::log("Weekly Cron",LogLevel::Info, true, 'cron', 'lantra');
            # stop all notifications but user generated automatic report
            # $this->notifyManagerSummary();
            # $this->notifyLicencesRemaining();
            # $this->notifySchemeExpiry();
        }
        $this->returnJson(['cron'=> $frequency]);
    }

    /**
     * Loop though all the team and company managers and send notifications
     *
     * @throws Exception
     */
    function notifyManagerSummary() {
        $criteria = craft()->elements->getCriteria(ElementType::User);
        $criteria->limit = null;
        $criteria->groupId = array(2, 3);
        $managers = $criteria->find();
        // loop managers and send notifications
        foreach($managers as $manager) {
            craft()->lantra_notify->sendManagerSummary($manager);
        }
    }

    /**
     * Loop though all the scheme and company managers and send notifications about remaining licences
     *
     * @throws Exception
     */
    function notifyLicencesRemaining() {
        craft()->lantra_notify->sendLicencesRemaining();
    }

    /**
     * Notify scheme managers of scheme expiry
     *
     * @throws Exception
     */
    function notifySchemeExpiry() {
        $expiryDate = craft()->lantra_licence->getSchemeExpiryDate();
        $warningDate = strtotime("+4 weeks");
        if ($expiryDate && $expiryDate->getTimestamp() < $warningDate) {
            craft()->lantra_notify->sendSchemeExpiry($expiryDate);
        }
    }

    /**
     * Remove expired users from Users group
     *
     * @throws Exception
     */
    function expireIndividualUsers() {
        $users = craft()->lantra_users->getExpiredUsers();
        if ($users) {
            foreach($users as $user) {
                craft()->lantra_users->deactivateIndividualUser($user);
            }
        }
    }

    /**
     * Notify expiring users
     *
     */
    function notifyUserExpiry() {
        $warningDate = strtotime("+4 weeks");
        craft()->lantra_notify->sendUserExpiry($warningDate);
    }
}
