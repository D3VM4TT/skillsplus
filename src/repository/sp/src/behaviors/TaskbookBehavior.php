<?php
/**
 * Lantra Skills Plus for Craft CMS 3.x
 *
 * @link      https://coffeebean.design
 * @copyright Copyright (c) 2020 Coffee Bean Design
 */

namespace lantra\sp\behaviors;

use Craft;
use craft\elements\user;
use verbb\supertable\elements\SuperTableBlockElement;
use yii\base\Behavior;

use lantra\sp\Plugin as Lantra;
use lantra\sp\helpers\LantraHelper;

class TaskbookBehavior extends Behavior
{
    /**
     * @return array
     */
    public function optionalModuleGroupCategories()
    {
        $categories = [];
        foreach ($this->owner->taskbookModuleGroups as $block) {
            if (!$block->moduleGroupMandatory) {
                $categories[] = $block->moduleGroup->one();
            }
        }
        return $categories;
    }

}