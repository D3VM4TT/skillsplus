<?php
/**
 * Lantra Skills Plus for Craft CMS 3.x
 *
 * @link      https://coffeebean.design
 * @copyright Copyright (c) 2020 Coffee Bean Design
 */

namespace lantra\sp\behaviors;

use yii\base\Behavior;

use lantra\sp\Plugin as Lantra;
use lantra\sp\helpers\LantraHelper;

class ModuleBehavior extends Behavior
{
    /**
     * @return bool
     */
    public function isCompany()
    {
        return $this->owner->moduleAllCompanies || $this->owner->moduleCompanies->count();
    }

    /**
     * @return bool
     */
    public function isUserCompany()
    {
        if (!$this->isCompany()) {
            return false;
        }

        if ($this->owner->moduleAllCompanies) {
            return true;
        }

        $user = LantraHelper::getUser();
        if (null == $userCompany = $user->userCompany->one()) {
            return false;
        }

        return in_array($userCompany->id, $this->owner->moduleCompanies->ids());
    }
}