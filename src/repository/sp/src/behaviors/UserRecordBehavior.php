<?php
/**
 * Lantra Skills Plus for Craft CMS 3.x
 *
 * @link      https://coffeebean.design
 * @copyright Copyright (c) 2020 Coffee Bean Design
 */

namespace lantra\sp\behaviors;

use lantra\sp\models\Record;
use yii\base\Behavior;

class UserRecordBehavior extends Behavior
{
    private $_record;
    /**
     * @return null
     */
    public function getRecord()
    {
        if ($this->_record == null) {
            $this->_record = new Record($this->owner);
        }
        return $this->_record;
    }
}