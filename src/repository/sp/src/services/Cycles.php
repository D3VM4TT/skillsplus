<?php
/**
 * Lantra Skills Plus for Craft CMS 3.x
 *
 * @link      https://coffeebean.design
 * @copyright Copyright (c) 2020 Coffee Bean Design
 */

namespace lantra\sp\services;

use Craft;
use craft\elements\Entry;
use craft\base\Component;

use lantra\sp\Plugin as Lantra;
use lantra\sp\models\Cycle;
use lantra\sp\models\CyclePeriod;
use lantra\sp\helpers\LantraHelper;
use lantra\sp\helpers\CycleHelper;

class Cycles extends Component
{
    /**
     * Wrapper for Results::getModuleResult() with cycle
     *
     * @param null $userId
     * @param $moduleId
     * @param CyclePeriod $cycle
     * @param bool $create
     * @return array|\craft\base\ElementInterface|Entry|null
     */
    public function getCycleResult($userId = null, $moduleId, CyclePeriod $cycle, $create = false)
    {
        if ($cycle->finishDate) {
            $postDate= ['and', '>= '.$cycle->startDate->format('Y-m-d H:i'), '<= '.$cycle->finishDate->format('Y-m-d H:i')];
        } else {
            $postDate = '<= '.$cycle->startDate->format('Y-m-d H:i');
        }

        return Lantra::$app->results->getModuleResult($userId, $moduleId, $create, $postDate);
    }

    /**
     * @param $moduleId
     * @param null $userId
     * @return array|null
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     */
    public function getModuleCycleResults($moduleId, $userId = null)
    {
        if (false == $moduleEntry = Craft::$app->entries->getEntryById($moduleId)) {
            return null;
        }
        $cycles = CycleHelper::getModuleCycles($moduleEntry);
        $results = [];
        foreach ($cycles as $cycle) {
            if ($cycle->isFuture()) {
                continue;
            }
            $result = $this->getCycleResult($userId, $moduleId, $cycle);
            if (!$result && $cycle->isActive()) {
                $postDate = $cycle->startDate;
                $postDate->setTime(06, 00, 00);
                $result = Lantra::$app->results->createModuleResult($userId, $moduleId, $postDate);
            }
            if ($result) {
                $results[] = $result;
            }
        }
        return array_reverse($results);
    }

    /**
     * @param CyclePeriod $cycle
     * @return bool
     */
    public function hasCycleResult(CyclePeriod $cycle, $moduleId, $userId = null)
    {
        return $this->getCycleResult($userId, $moduleId, $cycle) ? true : false;
    }

    /**
     *
     */
    public function createCycleResults()
    {
        ## get cpd modules
        if (null == $modules = CycleHelper::getCpdModules()) {
            return;
        }

        $total = 0;
        ## loop modules and see which start today
        foreach($modules as $moduleEntry) {
            $cycle = CycleHelper::getModuleCurrentCycle($moduleEntry);
            if ($cycle->startsToday()) {
                ## get users
                $users = Lantra::$app->users->getModuleUsers($moduleEntry);
                foreach($users as $user) {
                    ## will create if it doesn't already exist
                    Lantra::$app->cycles->getCycleResult($user->id, $moduleEntry->id, $cycle, true);
                    $total++;
                }
            }
        }
        $message = 'CPD module results created for ' . $total . ' users.';
        Craft::info($message, __METHOD__);
    }

    /**
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\SyntaxError
     */
    public function sendCycleEnds()
    {
        ## get cpd modules
        if (null == $modules = CycleHelper::getCpdModules()) {
            return;
        }
        $total = 0;

        ## loop modules and see which end yesterday
        foreach($modules as $moduleEntry) {
            $cycle = CycleHelper::getModuleCurrentCycle($moduleEntry)->getPrev();
            if ($cycle->endsYesterday()) {
                ## get users
                $users = Lantra::$app->users->getModuleUsers($moduleEntry);
                foreach($users as $user) {
                    ## get result for this user for this cycle
                    $resultEntry = $this->getCycleResult($user->id, $moduleEntry->id, $cycle);
                    if ($resultEntry) {
                        Lantra::$app->notify->sendCycleEnd($resultEntry, $moduleEntry, $cycle);
                    }
                    $total++;
                }
            }
        }
        $message = 'CPD Cycle end sent to ' . $total . ' users.';
        Craft::info($message, __METHOD__);
    }

    /**
     *
     */
    public function sendCycleReminders()
    {
        ## get cpd modules
        if (null == $modules = CycleHelper::getCpdModules()) {
            return;
        }
        $total = 0;

        ## loop modules see which start today
        foreach($modules as $moduleEntry) {
            $cycle = CycleHelper::getModuleCycle($moduleEntry);
            if ($cycle->remindsToday()) {
                ## get users
                $users = Lantra::$app->users->getModuleUsers($moduleEntry);
                foreach ($users as $user) {
                    ## get result for this user for this cycle
                    $resultEntry = $this->getCycleResult($user->id, $moduleEntry->id, $cycle->getCurrent());
                    if ($resultEntry && $resultEntry->resultStatus != 'complete') {
                        Lantra::$app->notify->sendCycleReminder($resultEntry);
                    }
                    $total++;
                }
            }
        }
        $message = 'CPD Cycle reminders sent to ' . $total . ' users.';
        Craft::info($message, __METHOD__);
    }
}