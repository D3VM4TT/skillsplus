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
use \DateTime;

use lantra\sp\Plugin as Lantra;

class CyclePeriod extends Model
{
    public $name = '';
    public $startDate;
    public $finishDate;
    public $graceDate;
    public $count;
    public $duration;
    public $grace;
    public $label;
    private $_recurring;

    /**
     * CyclePeriod constructor.
     * @param $startDate
     * @param $duration
     * @param $grace
     * @param $count
     */
    public function __construct(\DateTime $startDate, int $duration, int $grace, int $count)
    {
        parent::__construct();
        $startDate->setTime(00, 00, 00);
        $this->startDate = $startDate;
        $this->count = (int) $count;
        $this->duration = (int) $duration;
        $this->grace = (int) $grace;
        if ($duration) {
            $this->finishDate = $this->_getFinishDate();
            $this->graceDate = $this->_getGraceDate();
        }
        $this->_setName();
        $this->_setLabel();
    }

    /**
     * @return CyclePeriod
     */
    public function getNext()
    {
        if (!$this->finishDate) {
            return;
        }
        $startDate = new DateTime($this->finishDate->format('Y-m-d h:i'));
        $startDate->modify('+1 day');
        $startDate->setTime(00, 00, 00);
        return $this->_getCycle($startDate, $this->count + 1);
    }

    /**
     * @return CyclePeriod
     */
    public function getPrev()
    {
        if (!$this->finishDate) {
            return;
        }
        $startDate = new DateTime($this->startDate->format('Y-m-d h:i'));
        $startDate->modify('-' . $this->duration . ' months');
        return $this->_getCycle($startDate, $this->count - 1);
    }

    /**
     *
     */
    public function isGrace()
    {
        if (!$this->graceDate) {
            return false;
        }
        return $this->isPast() && $this->isActive();
    }

    /**
     *
     */
    public function isActive()
    {
        if (!$this->graceDate) {
            return true;
        }
        $now = new DateTime();
        return ($now < $this->graceDate);
    }

    /**
     *
     */
    public function isLocked()
    {
        return !$this->isActive();
    }

    /**
     *
     */
    public function isCurrent()
    {
        $now = new DateTime();
        if ($this->finishDate) {
            return ($now > $this->startDate && $now < $this->finishDate);
        }
        return $now > $this->startDate;
    }

    /**
     *
     */
    public function isFuture()
    {
        $now = new DateTime();
        return ($now < $this->startDate);
    }

    /**
     *
     */
    public function isPast()
    {
        $now = new DateTime();
        return ($this->finishDate && $now > $this->finishDate);
    }

    /**
     *
     */
    public function startsToday()
    {
        $now = new DateTime();
        return $now->format('dmy') == $this->startDate->format('dmy');
    }

    /**
     * @return bool
     */
    public function endsToday()
    {
        $now = new DateTime();
        ## graceDate will be finishDate of no grace period
        return $this->graceDate && $now->format('dmy') == $this->graceDate->format('dmy');
    }

    /**
     * @param string $type
     * @return mixed
     */
    public function getRecurring($type = 'monthly')
    {
        if (is_null($this->_recurring)) {
            $this->setRecurring($type);
        }
        return $this->_recurring;
    }

    /**
     * @param string $type
     */
    public function setRecurring($type = 'monthly')
    {
        if (!$this->finishDate) {
            return;
        }
        if ($type == 'monthly') {
            $duration = 1;
        } elseif ($type == 'quarterly') {
            $duration = 3;
        } else {
            $duration = 12;
        }
        $count = 1;
        ## create sub cycles for recurring periods
        $cycle = $this->_getCycle($this->startDate, $count, $duration, 0);
        ## work out how many cycles in this cycle (matrix...!)
        $total = ceil($this->duration / $cycle->duration);
        $this->_recurring[$count] = $cycle;
        for ($count = 2; $count < ($total + 1); $count++) {
            $cycle = $cycle->getNext();
            $this->_recurring[$count] = $cycle;
        }
    }

    /**
     * @return DateTime
     */
    private function _getFinishDate()
    {
        $finishDate = new DateTime($this->startDate->format('Y-m-d'));
        $finishDate->modify('+' . $this->duration . ' months');
        $finishDate->modify('-1 day');
        $finishDate->setTime(23, 59, 59);
        return $finishDate;
    }

    /**
     * @return DateTime
     */
    private function _getGraceDate()
    {
        if (!$this->grace) {
            return $this->finishDate;
        }
        $graceDate = new DateTime($this->finishDate->format('Y-m-d h:i'));
        $graceDate->modify('+' . $this->grace . ' days');
        return $graceDate;
    }

    /**
     * @param $startDate
     * @param $count
     * @param $duration
     * @param $grace
     * @return CyclePeriod
     */
    private function _getCycle($startDate, $count, $duration = null, $grace = null)
    {
        $_grace = $grace ? $grace : $this->grace;
        $_duration = $duration ? $duration : $this->duration;

        return new CyclePeriod($startDate, $_duration, $_grace, $count);
    }

    /**
     *
     */
    private function _setName()
    {
        $this->name = $this->startDate->format('jS M Y') . ($this->finishDate ? ' - ' . $this->finishDate->format('jS M Y') : '');
    }

    /**
     *
     */
    private function _setLabel()
    {
        ## year starting on first
        if ($this->duration == 12 && $this->startDate->format('dm') == '0101') {
            $this->label = $this->startDate->format('Y');
        }
        ## month or quarter starting on first
        elseif ($this->startDate->format('d') == '01') {
            if ($this->duration == 1) {
                $this->label = $this->startDate->format('F Y');
            }
            elseif ($this->duration == 3) {
                $m = $this->startDate->format('n');
                $this->label = 'Q' . ceil($m/3) . ' ' . $this->startDate->format('Y');
            }
        }
        else {
            $this->label = $this->startDate->format('d/m/y') . ($this->finishDate ? ' - ' . $this->finishDate->format('d/m/y') : '');
        }
    }
}

