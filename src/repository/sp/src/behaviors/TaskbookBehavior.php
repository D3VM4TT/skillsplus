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
     * @param $categoryId
     * @return null
     */
    public function moduleGroupBlock($categoryId)
    {
        foreach($this->moduleGroups('all') as $moduleGroup) {
            if ($moduleGroup['category']->id == $categoryId) {
                return $moduleGroup['block'];
            }
        }
        return null;
    }

    /**
     * @return array
     */
    public function moduleGroups($type = 'all')
    {
        $modulesGroups = [];
        foreach ($this->owner->taskbookModuleGroups as $block) {
            if ($type == 'all' || ($type == 'mandatory' && $block->moduleGroupMandatory) || ($type == 'optional' && !$block->moduleGroupMandatory))  {
                $category = $block->moduleGroup->one();
                $modulesGroups[] = [
                    'category' => $category,
                    'mandatory' => $block->moduleGroupMandatory,
                    'credit' => $block->moduleGroupCredit,
                    'level' => $block->moduleGroupLevel,
                    'block' => $block
                ];
            }
        }
        return $modulesGroups;
    }

    /**
     * @param string $type
     * @return array
     */
    public function moduleGroupCategories($type = 'all')
    {
        $categories = [];
        foreach ($this->moduleGroups($type) as $moduleGroup) {
            $categories[] = $moduleGroup['category'];
        }
        return $categories;
    }

    /**
     * @param string $type
     * @return int
     */
    public function moduleGroupCredits($type = 'all')
    {
        $credits = 0;
        foreach ($this->moduleGroups($type) as $moduleGroup) {
            if ($type == 'all' || ($type == 'mandatory' && $moduleGroup['mandatory']) || ($type == 'optional' && !$moduleGroup['mandatory']))  {
                $credits = $credits + $moduleGroup['credit'];
            }
        }
        return $credits;
    }

    /**
     * @return int
     */
    public function countMandatory()
    {
        return count($this->moduleGroups('mandatory'));
    }

    /**
     * @return int
     */
    public function countOptional()
    {
        return count($this->moduleGroups('optional'));
    }

    /**
     * @return int
     */
    public function creditsMandatory()
    {
        return $this->moduleGroupCredits('mandatory');
    }

    /**
     * @return int
     */
    public function creditsOptional()
    {
        return $this->moduleGroupCredits('optional');
    }
}