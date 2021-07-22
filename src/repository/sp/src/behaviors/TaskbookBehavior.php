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
    public function moduleGroups($type = 'all')
    {
        $blocks = [];
        foreach ($this->owner->taskbookModuleGroups as $block) {
            if ($type == 'all' || ($type == 'mandatory' && $block->moduleGroupMandatory) || ($type == 'optional' && !$block->moduleGroupMandatory))  {
                $blocks[] = $block;
            }
        }
        return $blocks;
    }

    /**
     * @param string $type
     * @return array
     */
    public function moduleGroupCategories($type = 'all')
    {
        $categories = [];
        foreach ($this->moduleGroups($type) as $block) {
            $categories[] = $block->moduleGroup->one();
        }
        return $categories;
    }

}