<?php
/**
 * Lantra Skills Plus for Craft CMS 3.x
 *
 * @link      https://coffeebean.design
 * @copyright Copyright (c) 2020 Coffee Bean Design
 */

namespace lantra\sp\controllers;

use Craft;

use lantra\sp\Plugin as Lantra;

class CronController extends BaseController {

    public $allowAnonymous = ['actionRun'];
    /**
     * Run all cron jobs
     *
     * @return null
     * @throws Exception
     */
    function actionRun() {
        $frequency = Craft::$app->request->getParam('frequency');
        if ($frequency == 'queue') {
            # run the next 2 jobs (reports) in the queue
            Lantra::$app->queue->next();
            Lantra::$app->queue->next();
        }
        if ($frequency == 'daily') {
            Craft::log("Daily Cron",LogLevel::Info, true, 'cron', 'lantra');
            # stop all notifications but user generated automatic report
            # $this->notifyUserExpiry();
            # $this->expireIndividualUsers();
            Lantra::$app->reports->sendDailyReports();
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
        $criteria = User::find();
        $criteria->limit = null;
        $criteria->groupId = array(2, 3);
        $managers = $criteria->all();
        // loop managers and send notifications
        foreach($managers as $manager) {
            Lantra::$app->notify->sendManagerSummary($manager);
        }
    }

    /**
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\SyntaxError
     */
    function notifyLicencesRemaining() {
        Lantra::$app->notify->sendLicencesRemaining();
    }

    /**
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\SyntaxError
     */
    function notifySchemeExpiry() {
        $expiryDate = Lantra::$app->licences->getSchemeExpiryDate();
        $warningDate = strtotime("+4 weeks");
        if ($expiryDate && $expiryDate->getTimestamp() < $warningDate) {
            Lantra::$app->notify->sendSchemeExpiry($expiryDate);
        }
    }

    /**
     * @throws \Exception
     */
    function expireIndividualUsers() {
        $users = Lantra::$app->users->getExpiredUsers();
        if ($users) {
            foreach($users as $user) {
                Lantra::$app->users->deactivateIndividualUser($user);
            }
        }
    }

    /**
     * Notify expiring users
     *
     */
    function notifyUserExpiry() {
        $warningDate = strtotime("+4 weeks");
        Lantra::$app->notify->sendUserExpiry($warningDate);
    }
}
