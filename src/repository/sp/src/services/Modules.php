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
use craft\elements\User;
use craft\elements\Category;
use craft\events\ModelEvent;

use lantra\sp\helpers\LantraHelper;
use lantra\sp\Plugin as Lantra;

class Modules extends Component
{
    /**
     * @param ModelEvent $event
     * @param Entry $entry
     */
    public function onBeforeSaveModule(ModelEvent $event, Entry $entry)
    {
        ## make sure recurring units are in cpd type modules
        if ($entry->type != 'cpd' && $entry->moduleUnitGroups) {
            $unitGroups = $entry->moduleUnitGroups->all();
            foreach ($unitGroups as $unitGroup) {
                if (!$unitGroup->unitEntries) {
                    continue;
                }
                $unitEntries = $unitGroup->unitEntries->all();
                foreach ($unitEntries as $unitEntry) {
                    if ($unitEntry->unitRecurring) {
                        $event->isValid = false;
                        $entry->addError('moduleUnitGroups', $unitEntry->title . ' is a recurring unit and can only be added to a CPD type module.');
                    }
                }
            }
        }
        ## make sure there is only one recurring unit per module
        if ($entry->type == 'cpd' && $this->countRecurringUnits($entry) > 1) {
            $event->isValid = false;
            $entry->addError('moduleUnitGroups', 'You can only add one recurring unit per module.');
        }
    }

    /**
     * @param $module
     * @param User $user
     * @return bool
     */
    public function isUserModule($module, User $user)
    {
        return !$module->moduleOptional || in_array($module->id, $user->userOptionalModules->ids());
    }

    /**
     * @param $module
     * @param string $minSkillLevel
     * @param string $maxSkillLevel
     * @return array[]|null
     */
    public function skillsMatrixIds($module, $unitGroupId = 'all', $unitId = 'all', $minSkillLevel = 'none', $maxSkillLevel = 'none', $status = 'all', $users = 'default')
    {
        if (!$module->isSkillsMatrix) {
            return null;
        }

        $return = [
            'userIds' => [],
            'unitIds' => [],
            'unitGroupIds' => [],
            'filterUnitIds' => []
        ];

        ## get all unit ids for module
        foreach ($module->moduleUnitGroups->all() as $unitGroup) {
            $return['unitGroupIds'][] = $unitGroup->id;
            $return['unitIds'] = array_merge($return['unitIds'], $unitGroup->unitEntries->ids());
        }

        ## filter by unit group
        if ($unitGroupId != 'all') {
            $unitGroup = $module->moduleUnitGroups->id($unitGroupId)->one();
            $return['filterUnitIds'] = $unitGroup ? $unitGroup->unitEntries->ids() : [];
        }
        else {
            $return['filterUnitIds'] = $unitId == 'all' ? $return['unitIds'] : [$unitId];
        }

        $skillLevelIds = $this->skillLevelIds($minSkillLevel, $maxSkillLevel);

        $criteria = Entry::find()
            ->section('results')
            ->relatedTo(['targetElement' => $return['filterUnitIds'], 'field' => 'resultUnit'])
            ->andRelatedTo(['targetElement' => $skillLevelIds, 'field' => 'skillLevel']);

        ## add result status
        if ($status != 'all') {
            $criteria->resultStatus = $status;
        }

        ## limit results to users according to config > setting
        $skillsMatrixUsersSetting = LantraHelper::setting('skillsMatrixUsers');
        ## users set by template
        $skillsMatrixUsers = $users == 'default' ? $skillsMatrixUsersSetting : $users;
        if ($skillsMatrixUsers != 'all') {
            $manager = Craft::$app->getUser()->getIdentity();
            $includeHierarchy = $skillsMatrixUsers != 'direct';
            $userIds = Lantra::$app->users->getManagerSubordinateIds($manager, $includeHierarchy);

            if (!count($userIds)) {
                return $return;
            }

            $criteria->authorId = $userIds;
        }

        $results = $criteria->all();

        foreach ($results as $result) {
            if (!in_array($result->authorId, $return['userIds'])) {
                $return['userIds'][] = $result->authorId;
            }
        }

        return $return;
    }

    /**
     * @param string $minSkillLevel
     * @param string $maxSkillLevel
     */
    public function skillLevelIds($minSkillLevel = 'none', $maxSkillLevel = 'none')
    {
        ## build list of all skill level ids
        $skillLevelIds = Category::find()
            ->group('skillLevels')
            ->ids();

        if ($minSkillLevel != 'none') {
            $beforeIds = Category::find()
                ->group('skillLevels')
                ->positionedBefore($minSkillLevel)
                ->ids();
            ## filter out before ids
            $skillLevelIds = array_diff($skillLevelIds, $beforeIds);
        }

        if ($maxSkillLevel != 'none') {
            $afterIds = Category::find()
                ->group('skillLevels')
                ->positionedAfter($maxSkillLevel)
                ->ids();
            ## filter out after ids
            $skillLevelIds = array_diff($skillLevelIds, $afterIds);
        }
        return $skillLevelIds;
    }

    /**
     * @param ModelEvent $event
     * @param Entry $entry
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     */
    public function onSaveModule(ModelEvent $event, Entry $entry)
    {
        ## copy the module count and unit count to the user role
        $categories = Category::find()->group('roles')->anyStatus()->all();
        foreach ($categories as $role) {
            $role->setFieldValue('roleModuleCount', $this->roleCount($role->id, 'modules'));
            $role->setFieldValue('roleUnitCount', $this->roleCount($role->id, 'units'));
            ## save the role units
            $roleUnits = Lantra::$app->results->roleUnits($role->id);
            $role->setFieldValue('linkedData', json_encode(array_keys($roleUnits)));
            Craft::$app->elements->saveElement($role);
        }
    }

    /**
     * @param $roleId
     * @param string $count
     * @return int|string
     */
    private function roleCount($roleId, $count = 'modules') {

        $criteria = Entry::find()
            ->section('modules')
            ->relatedTo(['targetElement' => [$roleId], 'field' => 'moduleRoles']);

        ## just return the module count
        if ($count == 'modules') {
            return $criteria->count();
        }

        $return = 0;
        foreach ($criteria->all() as $module) {
            foreach ($module->moduleUnitGroups as $unitGroup) {
                $return += $unitGroup->unitEntries->count();
            }
        }
        return $return;
    }

    /**
     * @param $moduleEntries
     * @return array
     */
    public function moduleUnits($moduleEntries)
    {
        if (!$moduleEntries || !count($moduleEntries)) {
            return [];
        }
        $return = [];
        foreach ($moduleEntries as $moduleEntry) {
            if ($moduleEntry->moduleUnitGroups) {
                foreach ($moduleEntry->moduleUnitGroups->all() as $unitGroup) {
                    if (!$unitGroup->unitEntries) {
                        continue;
                    }
                    foreach ($unitGroup->unitEntries->all() as $unitEntry) {
                        $return[] = $unitEntry;
                    }
                }
            }
        }
        return $return;
    }

    public function totalUnits($moduleEntries)
    {
        if (!$moduleEntries) {
            return 0;
        }
        $return = 0;
        foreach ($moduleEntries as $moduleEntry) {
            if ($moduleEntry->moduleUnitGroups) {
                foreach ($moduleEntry->moduleUnitGroups->all() as $unitGroup) {
                    $return += $unitGroup->unitEntries->count();
                }
            }
        }
        return $return;
    }


    public function unitsComplete($moduleEntries, $user)
    {
        if (!$moduleEntries) {
            return 0;
        }
        $return = 0;
        foreach ($moduleEntries as $moduleEntry) {
            if ($moduleEntry->moduleUnitGroups) {
                foreach ($moduleEntry->moduleUnitGroups->all() as $unitGroup) {
                    foreach ($unitGroup->unitEntries->all() as $unitEntry) {
                        if (Lantra::$app->results->unitResultExists($user->id, $unitEntry->id)) {
                            $return++;
                        }
                    }
                }
            }
        }
        return $return;
    }

    /**
     * @param $moduleEntry
     * @return bool
     */
    private function countRecurringUnits($entry)
    {
        $total = 0;
        if ($entry->moduleUnitGroups) {
            $unitGroups = $entry->moduleUnitGroups->all();
            foreach ($unitGroups as $unitGroup) {
                if (!$unitGroup->unitEntries) {
                    continue;
                }
                $unitEntries = $unitGroup->unitEntries->all();
                foreach ($unitEntries as $unitEntry) {
                    if ($unitEntry->unitRecurring) {
                        $total++;
                    }
                }
            }
        }
        return $total;
    }
}