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
    private $_moduleGroups = [];

    /**
     * Return module groups by type.
     *
     * @param string $type
     * @return array
     */
    public function moduleGroups($type = 'all')
    {
        if (!isset($this->_moduleGroups[$type])) {
            $this->_moduleGroups[$type] = $this->_moduleGroups($type);
        }
        return $this->_moduleGroups[$type];
    }

    /**
     * Return the module group matrix block by id.
     *
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
     * Return array of module group categories.
     *
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
     * Return count of module group credits.
     *
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
     * Return count of mandatory module groups.
     *
     * @return int
     */
    public function countMandatory()
    {
        return count($this->moduleGroups('mandatory'));
    }

    /**
     * Return count of optional module groups.
     *
     * @return int
     */
    public function countOptional()
    {
        return count($this->moduleGroups('optional'));
    }

    /**
     * Return count of mandatory credits.
     *
     * @return int
     */
    public function creditsMandatory()
    {
        return $this->moduleGroupCredits('mandatory');
    }

    /**
     * Return count of optional credits.
     *
     * @return int
     */
    public function creditsOptional()
    {
        return $this->moduleGroupCredits('optional');
    }

    /**
     * Cache the module groups.
     *
     * @param string $type
     * @return array
     */
    private function _moduleGroups($type = 'all')
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
}