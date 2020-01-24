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
    public $count;
    public $duration;

    /**
     * CyclePeriod constructor.
     * @param $startDate
     * @param $duration
     * @param $count
     */
    public function __construct(\DateTime $startDate, int $duration, int $count)
    {
        parent::__construct();
        $startDate->setTime(00, 00, 00);
        $this->startDate = $startDate;
        if ($duration) {
            $this->finishDate = $this->_getFinishDate($this->startDate->format('Y-m-d'), $duration);
        }
        $this->name = $this->startDate->format('jS M Y') . ($duration ? ' - ' . $this->finishDate->format('jS M Y') : '');
        $this->count = $count;
        $this->duration = $duration;
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
        return new CyclePeriod($startDate, $this->duration, $this->count + 1);
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
        return new CyclePeriod($startDate, $this->duration,$this->count - 1);
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
     * @param $startDate
     * @param $months
     * @return DateTime
     */
    private function _getFinishDate($startDate, $months)
    {
        $finishDate = new DateTime($startDate);
        $finishDate->modify('+' . $months . ' months');
        $finishDate->modify('-1 day');
        $finishDate->setTime(23, 59, 59);
        return $finishDate;
    }
}

