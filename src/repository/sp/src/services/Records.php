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
use craft\elements\Entry;
use craft\elements\db\ElementQueryInterface;
use craft\elements\User;
use craft\elements\Category;

use lantra\sp\Plugin as Lantra;

class Records extends Component
{
    /**
     * @param Category $jobRole
     * @return \craft\elements\db\ElementQueryInterface|\craft\elements\db\EntryQuery
     */
    public function getJobRoleModules(Category $jobRole)
    {
        $criteria = Entry::find();
        $criteria->section = 'modules';
        $criteria->relatedTo($jobRole->id);
        $criteria->orderBy('title');
        $criteria->limit(null);
        return $criteria;
    }

    /**
     * @param ElementQueryInterface $modules
     * @return \craft\elements\db\CategoryQuery|ElementQueryInterface
     */
    public function getModuleGroups(ElementQueryInterface $modules)
    {
        $criteria = Category::find();
        $criteria->group = 'moduleGroups';
        $criteria->relatedTo($modules->ids());
        $criteria->orderBy('title');
        return $criteria;
    }
}