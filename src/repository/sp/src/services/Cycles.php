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
     * @param CyclePeriod $cycle
     * @return bool
     */
    public function hasCycleResult(CyclePeriod $cycle, $moduleId, $userId = null)
    {
        return $this->getCycleResult($cycle, $moduleId, $userId) ? true : false;
    }
}