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

class ModuleGroupBehavior extends Behavior
{
    /**
     * Returns true if the module group contains units that should be saved for companies rather than users.
     *
     * @return bool
     */
    public function isCompany()
    {
        return $this->owner->moduleGroupAllCompanies || $this->owner->moduleGroupCompanies->count();
    }

    /**
     * Returns true if this module group is available for the current user’s company.
     *
     * @return bool
     */
    public function isUserCompany($userId = null)
    {
        if (!$this->isCompany()) {
            return false;
        }

        $user = LantraHelper::getUser($userId);
        if (null == $userCompany = $user->userCompany->one()) {
            return false;
        }

        if ($this->owner->moduleGroupAllCompanies) {
            return true;
        }

        return in_array($userCompany->id, $this->owner->moduleGroupCompanies->ids());
    }
}