<?php
/**
 * Lantra Skills Plus for Craft CMS 3.x
 *
 * @link      https://coffeebean.design
 * @copyright Copyright (c) 2020 Coffee Bean Design
 */

namespace lantra\sp\behaviors;

use lantra\sp\helpers\LantraHelper;
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
        if (!LantraHelper::enableBase()) {
            return false;
        }

        if ($this->owner->admin || $this->owner->isInGroup('editors')) {
            return false;
        }

        if (!in_array($this->owner->status, ['active', 'pending'])) {
            return false;
        }

        return !$this->owner->userNotLicenced;
    }

    /**
     * @return bool
     */
    public function getIsLinked()
    {
        return $this->owner->userLinkedUser->count() > 0;
    }

    /**
     * @return bool
     */
    public function getLinkedUser()
    {
        return $this->owner->userLinkedUser->one();
    }
}