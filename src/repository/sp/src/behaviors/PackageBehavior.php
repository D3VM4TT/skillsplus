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

class PackageBehavior extends Behavior
{
    /**
     * @return null
     */
    public function getModuleGroup()
    {
        return $this->owner->packageModuleGroup ? $this->owner->packageModuleGroup->last() : null;
    }

    /**
     * @return null
     */
    public function moduleGroups()
    {
        if (!$this->owner->packageModuleGroup) {
            return [];
        }
        $modulesGroups = [
            [
                'category' => $this->owner->packageModuleGroup->last(),
                'level'    => $this->owner->packageLevel
            ]
        ];
        foreach($this->owner->packageOptionalModuleGroups->all() as $optionalModuleGroupBlock) {
            $modulesGroups[] = [
                'category' => $optionalModuleGroupBlock->optionalModuleGroup->leaves()->one(),
                'level'    => $optionalModuleGroupBlock->optionalLevel
            ];
        }
        return $modulesGroups;
    }

    /**
     * @return null
     */
    public function moduleGroupCategories()
    {
        $categories = [];
        foreach($this->moduleGroups() as $moduleGroup) {
            $categories[$moduleGroup['category']->id] = $moduleGroup['category'];
        }
        return $categories;
    }

    /**
     * @return null
     */
    public function availableModuleGroupCategories()
    {
        $moduleGroup = $this->getModuleGroup();
        $optionalModules = $moduleGroup->children->all();
        $existingIds = array_keys(Lantra::$app->packages->getAllModuleGroups($this->owner->author));
        $available = [];
        foreach($optionalModules as $category) {
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
            if ($assessment->assessmentDate && ! $assessment->assessmentPassed) {
                $category = $assessment->assessmentModuleGroup->last();
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
            if ($assessment->assessmentModuleGroup->last()->id == $moduleGroupId) {
                return $assessment;
            }
        }
        return null;
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
        foreach($this->owner->packageReviews as $step) {
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
        foreach($this->owner->packageReviews as $step) {
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
        foreach($packagesWorkflow as $step) {
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
            'col1'       => time(),
            'col2'       => $userMessage,
            'col3'       => $adminMessage,
            'col4'       => $user->id,
            'col5'       => $user->fullName
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
    public function isComplete()
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
        foreach($this->owner->packageReviews as $step) {
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
        foreach($this->owner->packageReviews as $step) {
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
        foreach($this->owner->packageReviews as $step) {
            if ($step->reviewStepType == $type && $this->isReviewUser($manager, $step, $includeAdmin)) {
                return true;
            }
        }
        return false;
    }
}