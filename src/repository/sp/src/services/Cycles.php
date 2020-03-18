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

use lantra\sp\helpers\LantraHelper;
use lantra\sp\models\Cycle;
use lantra\sp\Plugin as Lantra;
use lantra\sp\models\CyclePeriod;

class Cycles extends Component
{
    /**
     * @param CyclePeriod $cycle
     * @param $moduleId
     * @param null $userId
     * @return array|\craft\base\ElementInterface|Entry|null
     */
    public function getCycleResult(CyclePeriod $cycle, $moduleId, $userId = null)
    {
        $criteria = Entry::find();
        $criteria->section = 'results';
        $criteria->type = 'moduleResult';
        $criteria->limit = 1;
        $criteria->authorId = $userId;
        $criteria->relatedTo = ['targetElement' => $moduleId, 'field' => 'resultModule'];
        if ($cycle->finishDate) {
            $criteria->postDate = ['and', '>= '.$cycle->startDate->format('Y-m-d H:i'), '<= '.$cycle->finishDate->format('Y-m-d H:i')];
        } else {
            $criteria->postDate = '<= '.$cycle->startDate->format('Y-m-d H:i');
        }
        return $criteria->one();
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
        $cycles = LantraHelper::getModuleCycles($moduleEntry);
        $results = [];
        foreach ($cycles as $cycle) {
            $result = $this->getCycleResult($cycle, $moduleId, $userId);
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
        return $this->getCycleResult($cycle, $moduleId, $userId) ? true : false;
    }

    /**
     *
     */
    public function createCycleResults()
    {
        ## get cpd modules
        if (null == $modules = LantraHelper::getCpdModules()) {
            return;
        }

        $total = 0;
        ## loop modules see which start today
        foreach($modules as $moduleEntry) {
            $cycle = new Cycle();
            $current = $cycle->setModule($moduleEntry)->setCycles()->getCurrent();
            if ($current->startsToday()) {
                ## get users
                $users = Lantra::$app->users->getModuleUsers($moduleEntry);
                foreach($users as $user) {
                    ## will create if it doesn't already exist
                    Lantra::$app->results->getModuleResult($user->id, $moduleEntry->id, true);
                    $total++;
                }
            }
        }
        $message = 'CPD module results created for ' . $total . ' users.';
        Craft::info($message, __METHOD__);
        Lantra::$app->notify->notifyAdmin('CPD Module Results', $message);
    }
}