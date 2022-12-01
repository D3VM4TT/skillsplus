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
            $recordUser = $this->getIsInactive() ? $this->getActiveUser() : $this->owner;
            $this->_record = new Record($recordUser);
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

    private $_allCompanies;
    private $_linkedUsers;
    private $_activeUser;

    /**
     * @return array
     */
    public function getAllCompanies()
    {
        if ($this->_allCompanies == null) {
            $this->_allCompanies = array_merge([$this->owner->userCompany], $this->owner->userSecondaryCompanies);
        }
        return $this->_allCompanies;
    }

    /**
     * @return bool
     */
    public function getHasLinkedUsers()
    {
        return $this->getIsInactive() || count($this->getLinkedUsers()) > 0;
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
     * @return array|null[]|\yii\base\Component[]
     */
    public function getAllUsers()
    {
        return array_merge([$this->owner], $this->getLinkedUsers());
    }

    /**
     * @return bool
     */
    public function getIsInactive()
    {
        return (bool) $this->getActiveUser();
    }

    /**
     * @return bool
     */
    public function getActiveUser()
    {
        if ($this->_activeUser == null) {
            $this->_activeUser = $this->owner->userActiveUser->one();
        }
        return $this->_activeUser;
    }

    /**
     * @param string $name
     * @return mixed|null
     * @throws \yii\base\UnknownPropertyException
     */
    public function __get($name)
    {
        $ignoreNames = [
            'isInactive',
            'userCompany'
        ];

        if (!in_array($name, $ignoreNames) && $this->getIsInactive()) {
            $activeUser = $this->getActiveUser();
            return $activeUser ? $activeUser->$name : null;
        }

        return parent::__get($name);
    }
}