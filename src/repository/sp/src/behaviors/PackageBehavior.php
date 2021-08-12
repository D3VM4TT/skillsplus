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
use lantra\sp\helpers\RecordHelper;

class PackageBehavior extends Behavior
{
    /**
     * @return null
     */
    public function getTaskbook()
    {
        return $this->owner->packageTaskbook ? $this->owner->packageTaskbook->last() : null;
    }

    /**
     * @param $categoryId
     * @return null
     */
    public function getTaskbookModuleGroupBlock($categoryId)
    {
        if (null == $taskbook = $this->getTaskbook()) {
            return null;
        }
        return $taskbook->moduleGroupBlock($categoryId);
    }

    /**
     * @param $categoryId
     * @return mixed|null
     */
    public function moduleGroup($categoryId)
    {
        foreach($this->moduleGroups() as $moduleGroup) {
            if ($moduleGroup['category']->id == $categoryId) {
                return $moduleGroup;
            }
        }
        return null;
    }

    /**
     * @param string $type
     * @return array
     */
    public function moduleGroups($type = 'all')
    {
        if ($type == 'available') {
            return $this->availableModuleGroups();
        }
        if (!$this->owner->packageModuleGroups) {
            return [];
        }
        $modulesGroups = [];
        foreach ($this->owner->packageModuleGroups->all() as $moduleGroupBlock) {
            if ($type == 'all' || ($type == 'unpaid' && !$moduleGroupBlock->moduleGroupPaid) || ($type == 'optional' && !$moduleGroupBlock->moduleGroupMandatory) || ($type == 'mandatory' && $moduleGroupBlock->moduleGroupMandatory)) {
                if (!$type == 'paid' && !$moduleGroupBlock->moduleGroupPaid) {
                    continue;
                }
                $category = $moduleGroupBlock->moduleGroup->one();
                if (null == $taskbookModuleGroupBlock = $this->getTaskbookModuleGroupBlock($category->id)) {
                    continue;
                }
                $modulesGroups[] = [
                    'category' => $category,
                    'mandatory' => $taskbookModuleGroupBlock->moduleGroupMandatory,
                    'level' => $moduleGroupBlock->moduleGroupLevel,
                    'credit' => $taskbookModuleGroupBlock->moduleGroupCredit,
                    'paid' => (bool) $moduleGroupBlock->moduleGroupPaid,
                    'cost' => (int) $moduleGroupBlock->moduleGroupCost
                ];
            }
        }
        return $modulesGroups;
    }

    /**
     * @return array
     */
    public function moduleGroupIds($type = 'all')
    {
        return array_keys($this->moduleGroupCategories($type));
    }

    /**
     * @return null
     */
    public function moduleGroupCategories($type = 'all')
    {
        $categories = [];
        foreach ($this->moduleGroups($type) as $moduleGroup) {
            $categories[$moduleGroup['category']->id] = $moduleGroup['category'];
        }
        return $categories;
    }

    /**
     * @param $categoryId
     * @return bool
     */
    public function hasModuleGroup($categoryId)
    {
        return array_key_exists($categoryId, $this->moduleGroupIds());
    }

    /**
     * @return array
     */
    public function getOptionalModuleGroups()
    {
        $categories = [];
        foreach ($this->moduleGroups('optional') as $moduleGroup) {
            $categories[] = $moduleGroup['category'];
        }
        return $categories;
    }

    /**
     * @param $categoryId
     * @return mixed|null
     */
    public function moduleGroupBlock($categoryId)
    {
        foreach ($this->owner->packageModuleGroups->all() as $moduleGroupBlock) {
            $category = $moduleGroupBlock->moduleGroup->one();
            if ($category->id == $categoryId) {
                return $moduleGroupBlock;
            }
        }
        return null;
    }

    /**
     * @return int
     */
    public function unpaidCost()
    {
        $cost = 0;
        foreach ($this->moduleGroups('unpaid') as $unpaid) {
            $cost = $cost + $unpaid['cost'];
        }
        return $cost;
    }

    /**
     * @return array
     */
    public function unpaidPayPalParams()
    {
        $params = [
            'packageId' => $this->owner->id,
            'moduleGroupIds' => []
        ];
        foreach ($this->moduleGroups('unpaid') as $unpaid) {
            $params['moduleGroupIds'][] = $unpaid['category']->id;
        }
        return $params;
    }

    /**
     * @param $moduleGroupIds
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     */
    public function payModuleGroups($moduleGroupIds)
    {
        foreach ($this->owner->packageModuleGroups as $moduleGroupBlock) {
            $category = $moduleGroupBlock->moduleGroup->one();
            if (in_array($category->id, $moduleGroupIds)) {
                $moduleGroupBlock->setFieldValue('moduleGroupPaid', true);
                Craft::$app->getElements()->saveElement($moduleGroupBlock, false);
            }
        }
    }

    /**
     * @return bool
     */
    public function hasAvailable()
    {
        return (bool)count($this->availableModuleGroups());
    }

    /**
     * @return array
     */
    public function availableModuleGroups()
    {
        $taskbook = $this->getTaskbook();
        $taskBookModuleGroups = $taskbook->moduleGroups('optional');
        $modulesGroups = [];
        foreach ($taskBookModuleGroups as $moduleGroup) {
            $category = $moduleGroup['category'];
            if (!in_array($category->id, $this->moduleGroupIds())) {
                $modulesGroups[] = [
                    'category' => $category,
                    'level' => '',
                    'credit' => $moduleGroup['credit']
               ];
            }
        }
        return $modulesGroups;
    }

    /**
     * @return null
     */
    public function availableModuleGroupCategories()
    {
        $taskbook = $this->getTaskbook();
        $optionalModuleGroups = $taskbook->moduleGroupCategories('optional');
        $existingIds = array_keys(Lantra::$app->packages->getAllModuleGroups($this->owner->author));
        $available = [];
        foreach ($optionalModuleGroups as $category) {
            if (!in_array($category->id, $existingIds)) {
                $available[$category->id] = $category;
            }
        }
        return $available;
    }

    /**
     * @return null
     */
    public function resitModuleGroupCategories()
    {
        $resits = [];
        foreach ($this->owner->packageAssessment as $assessment) {
            if ($assessment->assessmentDate && !$assessment->assessmentPassed) {
                $category = $assessment->assessmentModuleGroup->one();
                $resits[$category->id] = $category;
            }
        }
        return $resits;
    }

    /**
     * @param int $moduleGroupId
     * @return null
     */
    public function moduleGroupAssessment($moduleGroupId = null)
    {
        foreach ($this->owner->packageAssessment as $assessment) {
            $moduleGroup = $assessment->assessmentModuleGroup->one();
            if ($moduleGroup && $moduleGroup->id == $moduleGroupId) {
                return $assessment;
            }
        }
        return null;
    }

    /**
     * @return bool
     */
    public function isComplete()
    {
        $required = $this->required();
        return $required['complete'];
    }

    /**
     * @return array
     */
    public function required()
    {
        $user = $this->owner->author;
        $taskbook = $this->getTaskbook();
        $mandatory = 0;
        $optional = 0;
        $credits = 0;
        $levelCredits = 0;
        foreach ($this->moduleGroups() as $moduleGroup) {
            ## get the results from the user record
            $categoryId = $moduleGroup['category']->id;
            $moduleGroupItem = $user->record->getItem($categoryId);
            if (RecordHelper::isComplete($moduleGroupItem, $this->owner->author, $taskbook)) {
                if ($moduleGroup['mandatory']) {
                    $mandatory++;
                }
                else{
                    $optional++;
                }
                $credits += $moduleGroup['credit'];
                if ($moduleGroup['level'] <= $taskbook->moduleGroupMinimumLevel) {
                    $levelCredits += $moduleGroup['credit'];
                }
            }
        }
        $totalMandatory = count($this->moduleGroupIds('mandatory'));
        $required = [
            'mandatory'     => max($totalMandatory - $mandatory, 0),
            'optional'      => max($taskbook->moduleMinimumOptional - $optional, 0),
            'credits'       => max($taskbook->moduleMinimumCredits - $credits,0),
            'levelCredits'  => max($taskbook->moduleGroupMinimumLevelCredits - $levelCredits, 0)
        ];
        $required['complete'] = $required['mandatory'] == 0 && $required['optional'] == 0 && $required['credits'] == 0 && $required['levelCredits'] == 0;
        return $required;
    }

    /**
     * @param user|null $user
     * @param bool $includeAdmin
     * @return bool
     */
    public function isAssessor(User $user = null, $includeAdmin = false)
    {
        return $this->isPackageManager($user, $includeAdmin, 'assessment');
    }

    /**
     * @param user|null $user
     * @param bool $includeAdmin
     * @return bool
     */
    public function isReviewer(User $user = null, $includeAdmin = false)
    {
        return $this->isPackageManager($user, $includeAdmin, 'review');
    }

    /**
     * @param user|null $user
     * @param bool $includeAdmin
     * @return bool
     */
    public function isCompleter(User $user = null, $includeAdmin = false)
    {
        return $this->isPackageManager($user, $includeAdmin, 'complete');
    }

    /**
     * @param user|null $user
     * @param bool $includeAdmin
     * @return bool
     */
    public function isManager(User $user = null, $includeAdmin = false)
    {
        return $this->isAssessor($user, $includeAdmin) || $this->isReviewer($user, $includeAdmin) || $this->isCompleter($user, $includeAdmin);
    }

    /**
     * @param user|null $user
     * @return array
     */
    public function managerSteps(User $user = null)
    {
        $manager = LantraHelper::getUser($user);
        $steps = [];
        foreach ($this->owner->packageReviews as $step) {
            if ($step->reviewUser->count() && $step->reviewUser->one()->id == $manager->id) {
                $steps[] = $step;
            }
        }
        return $steps;
    }

    /**
     * @param user|null $user
     * @return bool
     */
    public function canAssign(User $user = null)
    {
        $manager = LantraHelper::getUser($user);
        foreach ($this->owner->packageReviews as $step) {
            if ($this->canAssignStep($step, $manager)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param $step
     * @param user|null $user
     * @return bool
     */
    public function canAssignStep($step, User $user = null)
    {
        $manager = LantraHelper::getUser($user);
        if ($manager->admin || $manager->isInGroup('schemeManagers')) {
            return true;
        }
        ## make sure workflow step exists
        if (null == $packageWorkflowStep = $this->getPackageWorkflowStep($step->reviewStepId)) {
            return false;
        }
        ## run through job roles
        if ($packageWorkflowStep->stepAssignUserGroup == 'jobRole') {
            foreach ($packageWorkflowStep->stepAssignJobRole as $role) {
                if (in_array($role->id, $user->userRole->ids())) {
                    return true;
                }
            }
            return false;
        }
        ## check user group
        return $manager->isInGroup($packageWorkflowStep->stepAssignUserGroup->value);
    }

    /**
     * @param $stepId
     * @return mixed|null
     */
    public function getPackageWorkflowStep($stepId)
    {
        $packagesWorkflow = $this->getWorkflow();
        foreach ($packagesWorkflow as $step) {
            if ($step->stepId == $stepId) {
                return $step;
            }
        }
        return null;
    }

    /**
     *
     */
    public function getWorkflow()
    {
        return $this->owner->packageWorkflow->one()->workflow;
    }

    /**
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     */
    public function save()
    {
        Craft::$app->getElements()->saveElement($this->owner);
    }

    /**
     * @param $comment
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     */
    public function comment($comment)
    {
        $this->log('Comment: ' . $comment);
        Lantra::$app->notify->sendPackageComment($this->owner, $comment);
    }

    /**
     * @param $userMessage
     * @param string $adminMessage
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     */
    public function log($userMessage, $adminMessage = '')
    {
        if (!$userMessage || null == $user = LantraHelper::getUser()) {
            return;
        }
        $new = [
            'col1' => time(),
            'col2' => $userMessage,
            'col3' => $adminMessage,
            'col4' => $user->id,
            'col5' => $user->fullName
        ];
        $packageLog = $this->owner->packageLog;
        $packageLog['new1'] = $new;
        $this->owner->setFieldValue('packageLog', $packageLog);
        $this->save();
    }

    /**
     * @return bool
     */
    public function isLocked()
    {
        return $this->owner->packageStatus != 'active';
    }

    /**
     * @return bool
     */
    public function isStatusComplete()
    {
        return $this->owner->packageStatus == 'complete';
    }

    /**
     * @param User $user
     * @param SuperTableBlockElement $step
     * @param bool $includeAdmin
     * @return bool
     */
    public function isReviewUser(User $user = null, SuperTableBlockElement $step, $includeAdmin = false)
    {
        $manager = LantraHelper::getUser($user);

        ## admins and scheme managers can manage everyone
        if ($includeAdmin && ($manager->admin || $manager->isInGroup('schemeManagers'))) {
            return true;
        }
        return $step->reviewUser->count() && $step->reviewUser->one()->id == $manager->id;
    }

    /**
     * @param SuperTableBlockElement $step
     * @return \craft\elements\db\ElementQueryInterface|\craft\elements\db\UserQuery|null
     */
    public function getStepManagers(SuperTableBlockElement $step)
    {
        $packageWorkflowStep = $this->getPackageWorkflowStep($step->reviewStepId);
        if (!$packageWorkflowStep) {
            return null;
        }

        $criteria = User::find();
        if ($packageWorkflowStep->stepUserGroup == 'jobRole') {
            $criteria->relatedTo = ['targetElement' => $packageWorkflowStep->stepJobRole->ids(), 'field' => 'userRole'];
        } else {
            $criteria->group = $packageWorkflowStep->stepUserGroup;
        }
        $criteria->limit = null;
        return $criteria;
    }

    /**
     * Get the complete date
     *
     * @return null
     */
    public function getCompleteDate()
    {
        foreach ($this->owner->packageReviews as $step) {
            if ($step->stepType == 'complete' && $step->reviewDate) {
                return $step->reviewDate;
            }
        }
        return null;
    }

    /**
     * Get the next step that can be reviewed (has user)
     *
     * @return null
     */
    public function getNextStep()
    {
        foreach ($this->owner->packageReviews as $step) {
            if (!$step->reviewDate) {
                return $step;
            }
        }
        return null;
    }

    /**
     * Get the previous step that can be reviewed (has date)
     *
     * @return null
     */
    public function getPreviousStep()
    {
        $previousStep = null;
        foreach ($this->owner->packageReviews as $step) {
            if (!$step->reviewDate) {
                break;
            }
            $previousStep = $step;
        }
        return $previousStep;
    }

    /**
     * @return bool
     */
    public function hasNextStep()
    {
        return $this->getNextStep() ? true : false;
    }

    /**
     *
     */
    public function getTotalSteps()
    {
        return $this->owner->packageReviews->count();
    }

    /**
     * @return bool
     */
    public function isNextStepAssigned()
    {
        if (null == $step = $this->getNextStep()) {
            return null;
        }
        return $step->reviewUser->count() ? true : false;
    }

    /**
     * @return null
     */
    public function getNextStepReviewer()
    {
        if (null == $step = $this->getNextStep()) {
            return null;
        }
        return $step->reviewUser->one();
    }

    /**
     * @param User $user
     * @param bool $includeAdmin
     * @param string $type assessment|review|complete
     * @return bool
     */
    private function isPackageManager(User $user = null, $includeAdmin = false, $type = 'assessment')
    {
        $manager = LantraHelper::getUser($user);

        ## admins and scheme managers can manage everyone
        if ($includeAdmin && ($manager->admin || $manager->isInGroup('schemeManagers'))) {
            return true;
        }
        foreach ($this->owner->packageReviews as $step) {
            if ($step->reviewStepType == $type && $this->isReviewUser($manager, $step, $includeAdmin)) {
                return true;
            }
        }
        return false;
    }
}