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
        $this->name = $this->startDate->format('jS M Y') . ($duration ? ' - ' . $this->finishDate->format('jS M Y') : '');
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
     * @return CyclePeriod
     */
    private function _getCycle($startDate, $count)
    {
        return new CyclePeriod($startDate, $this->duration, $this->grace, $count);
    }
}

