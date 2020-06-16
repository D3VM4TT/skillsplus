<?php
/**
 * Lantra Skills Plus for Craft CMS 3.x
 *
 * @link      https://coffeebean.design
 * @copyright Copyright (c) 2020 Coffee Bean Design
 */

namespace lantra\sp\services;

use Craft;
use craft\base\Component;
use craft\base\ElementInterface;
use craft\elements\Entry;
use craft\elements\db\ElementQueryInterface;
use craft\elements\User;
use craft\elements\Category;

use lantra\sp\Plugin as Lantra;

class Records extends Component
{
    /**
     * @param array|Category $relatedTo
     * @param bool $direct
     * @return \craft\elements\db\ElementQueryInterface|\craft\elements\db\EntryQuery
     */
    public function getRelatedModules($relatedTo, $direct = true)
    {
        $criteria = Entry::find();
        $criteria->section = 'modules';
        $criteria->relatedTo($relatedTo);
        $criteria->orderBy('lft');
        $criteria->limit(null);
        ## get the child category ids and ignore
        if ($direct && isset($relatedTo->level) && $relatedTo->level == 1 && $relatedTo->children->count()) {
            $childModules = $this->getRelatedModules($relatedTo->children->ids(), false);
            if ($childModules->count()) {
                $criteria->id(['not', $childModules->ids()]);
            }
        }
        return $criteria;
    }

    /**
     * @param ElementQueryInterface $modules
     * @param bool $taskbooks
     * @return \craft\elements\db\CategoryQuery|ElementQueryInterface
     */
    public function getModuleGroups(ElementQueryInterface $modules, $taskbooks = false)
    {
        $criteria = Category::find();
        $criteria->group = 'moduleGroups';
        $criteria->relatedTo($modules->ids());
        $criteria->orderBy('title');
        $criteria->moduleGroupTaskbooks = $taskbooks;
        return $criteria;
    }
}