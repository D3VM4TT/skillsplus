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
    function actionRunCron()
    {
        $this->notifyManagersExpiringResults();
    }

    /**
     * Loop though all the team and company managers and send notifications
     *
     * @throws Exception
     */
    function notifyManagersExpiringResults()
    {
        $criteria = craft()->elements->getCriteria(ElementType::User);
        $criteria->limit = null;
        $criteria->groupId = array(2, 3);
        $managers = $criteria->find();

        foreach($managers as $manager) {
            craft()->lantra_notify->notifyExpiringResults($manager);
        }
    }
}
