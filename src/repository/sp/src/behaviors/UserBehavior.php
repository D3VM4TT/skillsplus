<?php
/**
 * Lantra Skills Plus for Craft CMS 3.x
 *
 * @link      https://coffeebean.design
 * @copyright Copyright (c) 2020 Coffee Bean Design
 */

namespace lantra\sp\behaviors;

use lantra\sp\Plugin as Lantra;
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

    private $_linkedUsers;

    /**
     * @return bool
     */
    public function getLinkedUser()
    {
        return $this->owner->userLinkedUser->one();
    }

    /**
     * @return bool
     */
    public function getPrimaryUser()
    {
        return $this->getIsLinked() ? $this->getLinkedUser() : $this->owner;
    }

    /**
     * @return bool
     */
    public function getIsLinked()
    {
        return (bool) $this->getLinkedUser();
    }

    /**
     * @return mixed
     */
    public function getLinkedUsers()
    {
        if ($this->_linkedUsers == null) {
            $this->_linkedUsers = Lantra::$app->users->getLinkedUsers($this->owner);
        }
        return $this->_linkedUsers;
    }

    /**
     * @return bool
     */
    public function getHasLinkedUsers()
    {
        return count($this->getLinkedUsers()) > 0;
    }

    /**
     * @return array|null[]|\yii\base\Component[]
     */
    public function getAllLinkedUsers()
    {
        $user = $this->getPrimaryUser();
        return array_merge([$user], $user->getLinkedUsers());
    }

    /**
     * @param string $name
     * @return mixed|null
     * @throws \yii\base\UnknownPropertyException
     */
    public function __get($name)
    {
        $linkedFields = [
            'userAddress',
            'userTelephone',
            'userDateOfBirth'
        ];

        if ($this->getIsLinked() && in_array($name, $linkedFields)) {
            $linkedUser = $this->getLinkedUser();
            return $linkedUser ? $linkedUser->$name : null;
        }

        return parent::__get($name);
    }
}