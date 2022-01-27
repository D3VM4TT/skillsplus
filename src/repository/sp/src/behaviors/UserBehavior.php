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

class UserBehavior extends Behavior
{
    private $_record;

    /**
     * Return user record.
     *
     * @return null
     */
    public function getRecord()
    {
        if ($this->_record == null) {
            $this->_record = new Record($this->owner);
        }
        return $this->_record;
    }

    /**
     * @param $elementId
     * @param string $type
     */
    public function getResult($elementId, $type = 'unit')
    {

    }

    /**
     * @return false|mixed
     */
    public function getIsLicenced()
    {
        if ($this->owner->admin) {
            return false;
        }

        if ($this->owner->isInGroup('schemeManagers') || $this->owner->isInGroup('editors')) {
            return false;
        }

        return !$this->owner->userNotLicenced;
    }
}