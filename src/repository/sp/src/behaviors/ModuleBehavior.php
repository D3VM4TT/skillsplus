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

class ModuleBehavior extends Behavior
{
    /**
     * @return bool
     */
    public function isCompany()
    {
        return $this->owner->moduleGroup->one()->isCompany();
    }

    /**
     * @return bool
     */
    public function isUserCompany($userId = null)
    {
        return $this->owner->moduleGroup->one()->isUserCompany($userId);
    }
}