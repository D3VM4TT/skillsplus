<?php

namespace Craft;

class Lantra_CronController extends Lantra_BaseController {

    public $allowAnonymous = array('actionRunCron');

    /**
     * Run all cron jobs
     *
     * @return null
     * @throws Exception
     */
    function actionRunCron(array $variables = array()) {
        if ($variables['frequency'] == 'weekly') {
            Craft::log("Weekly Cron",LogLevel::Info, true, 'cron', 'lantra');
            $this->notifyManagerSummary();
            $this->notifyLicencesRemaining();
        }
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
}
