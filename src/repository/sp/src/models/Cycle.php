<?php
/**
 * Lantra Skills Plus for Craft CMS 3.x
 *
 * @link      https://coffeebean.design
 * @copyright Copyright (c) 2020 Coffee Bean Design
 */

namespace lantra\sp\models;

use Craft;
use craft\base\Model;
use craft\elements\Entry;
use craft\web\User;

use lantra\sp\Plugin as Lantra;
use lantra\sp\models\CyclePeriod;

class Cycle extends Model
{
    private $_moduleEntry;
    private $_resultEntry;
    # the reference date for an existing module result
    private $_cycleDate;

    # the original start date
    private $_cycleStartDate;
    # the duration (in months)
    private $_cycleDuration = 12;
    # how long to allow submissions after finish date
    private $_cycleGrace = 0;
    # the current cycle period
    private $_current;
    # all cycle periods up to current
    private $_cycles = [];

    /**
     * Cycle constructor.
     * @param Entry $resultEntry
     */
    public function __construct($resultEntry = null)
    {
        parent::__construct();

        if ($resultEntry) {
            $this->setResult($resultEntry);
            if ($resultEntry->resultModule->count()) {
                $this->setModule($resultEntry->resultModule->one());
            }
        }
        else {
            $this->_cycleDate = new \DateTime();
            $this->_cycleStartDate = new \DateTime();
        }
        $this->setCycles();
    }

    /**
     * @param $resultEntry
     * @return $this
     */
    public function setResult($resultEntry)
    {
        $this->_resultEntry = $resultEntry;
        $this->_cycleDate = $resultEntry->postDate;
        return $this;
    }

    /**
     * @param $moduleEntry
     * @return $this
     */
    public function setModule($moduleEntry)
    {
        $this->_moduleEntry = $moduleEntry;
        if ($moduleEntry->cycleUserStartDate) {
            # cycles start from user start date (or date created)
            $user = $this->_getUser();
            $this->_cycleStartDate = $user->userStartDate ? $user->userStartDate : $user->dateCreated;
        }
        else {
            # cycles start from setting in module (or today)
            $this->_cycleStartDate = $this->_moduleEntry->cycleStartDate ? $this->_moduleEntry->cycleStartDate : new \DateTime();
        }
        ## convert duration to months
        $months = $moduleEntry->cycleDurationLength ?? 1;
        ## cycle lasts forever
        if ($moduleEntry->cycleDurationType == 'open') {
            $this->_cycleDuration = 0;
        }
        elseif ($moduleEntry->cycleDurationType == 'year') {
            $this->_cycleDuration = $months * 12;
        }
        elseif ($moduleEntry->cycleDurationType == 'quarter') {
            $this->_cycleDuration = $months * 3;
        }
        else {
            $this->_cycleDuration = $months;
        }
        $this->_cycleGrace = $moduleEntry->cycleGrace;
        $this->_cycleStartDate->setTime(00, 00, 00);
        return $this;
    }

    /**
     * @return CyclePeriod
     */
    public function getCycle()
    {
        # loop through cycles and find matching cycle
        $date = $this->_cycleDate;
        foreach ($this->_cycles as $count => $cycle) {
            if ($cycle->startDate < $date && (!$cycle->finishDate || $cycle->finishDate > $date)) {
                return $cycle;
            }
        }
        # @todo what if historic result has no matching cycle...?
        return $this->getCurrent();
    }

    /**
     * @return array
     */
    public function getCycles()
    {
        return $this->_cycles;
    }


    /**
     * @return CyclePeriod
     */
    public function getCurrent()
    {
        return $this->_current;
    }

    /**
     * @return CyclePeriod
     */
    public function getNext()
    {
        return $this->_current->getNext();
    }

    /**
     * @return CyclePeriod
     */
    public function getPrev()
    {
        return $this->_current->getPrev();
    }

    /**
     *
     */
    public function setCycles()
    {
        ## ignore future start dates
        $now = new \DateTime();
        if ($this->_cycleStartDate > $now) {
            return $this;
        }
        $count = 1;
        $cycle = new CyclePeriod($this->_cycleStartDate, $this->_cycleDuration, $this->_cycleGrace, $count);
        $this->_cycles[$count] = $cycle;
        while (!$cycle->isCurrent()) {
            # emergency break?
            if ($count == 20) {
                break;
            }
            $count++;
            $cycle = $cycle->getNext();
            $this->_cycles[$count] = $cycle;
        }
        $this->_current = $cycle;
        return $this;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function remindsToday()
    {
        if (!$this->_moduleEntry || !$this->_moduleEntry->cycleNotifyReminder) {
            return false;
        }
        $current = $this->getCurrent();
        ## skip in case today is the cycle starts today
        if ($current->startsToday()) {
            return false;
        }
        $now = new \DateTime();
        $startDate = $current->startDate;
        $frequency = $this->_moduleEntry->cycleNotifyReminderFrequency;
        ## weekly sends on Mondays
        if ($frequency == 'weekly' && $now->format('w') == '1') {
            return true;
        }
        ## monthly sends on first of month
        elseif ($frequency == 'monthly' && $now->format('d') == '01') {
            return true;
        }
        elseif ($frequency == 'quarterly') {
            ## quarterly sends plus three, six and nine months
            $quarterlyDate = clone $startDate;
            $quarterly = [
                $quarterlyDate->modify('+3 months')->format('dm'),
                $quarterlyDate->modify('+3 months')->format('dm'),
                $quarterlyDate->modify('+3 months')->format('dm')
            ];
            return in_array($now->format('dm'), $quarterly);
        }
        ## yearly send one year from start date
        elseif ($frequency == 'yearly' && $now->format('dm') == $startDate->format('dm')) {
            return true;
        }
        return false;
    }

    /**
     * @return User
     */
    private function _getUser()
    {
        if ($this->_resultEntry) {
            return $this->_resultEntry->getAuthor();
        }
        return Craft::$app->getUser()->getIdentity();
    }
}