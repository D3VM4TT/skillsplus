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
     * Returns true if parent module group isCompany (i.e. unit results belong to companies rather than users).
     *
     * @return bool
     */
    public function isCompany()
    {
        $moduleGroup = $this->owner->moduleGroup->one();
        return $moduleGroup ? $moduleGroup->isCompany() : false;
    }

    /**
     * Returns true if the parent module group isUserCompany (i.e. is available for the current user’s company).
     *
     * @return bool
     */
    public function isUserCompany($userId = null)
    {
        return $this->owner->moduleGroup->one()->isUserCompany($userId);
    }
}