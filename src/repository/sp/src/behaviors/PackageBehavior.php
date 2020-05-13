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
    public function coreModule()
    {
        return $this->owner->packageCoreModule ? $this->owner->packageCoreModule->one() : null;
    }

    /**
     * @return null
     */
    public function modules()
    {
        if (!$this->owner->packageCoreModule) {
            return [];
        }
        $modules = [
            [
                'entry'    => $this->owner->packageCoreModule->one(),
                'level'    => $this->owner->packageCoreLevel
            ]
        ];
        foreach($this->owner->packageOptionalModules->all() as $optionalModuleBlock) {
            $modules[] = [
                'entry'    => $optionalModuleBlock->optionalModule->one(),
                'level'    => $optionalModuleBlock->optionalLevel
            ];
        }
        return $modules;
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
        ## run through job roles
        $packageWorkflowStep = $this->getPackageWorkflowStep($step->reviewStepId);
        if ($packageWorkflowStep->stepAssignUserGroup == 'jobRole') {
            foreach ($packageWorkflowStep->stepAssignJobRole as $role) {
                if (in_array($role->id, $user->userRole->ids())) {
                    return true;
                }
            }
            return false;
        }
        ## check user group
        return $user->isInGroup($packageWorkflowStep->stepAssignUserGroup);
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
            $criteria->relatedTo = ['targetElement' => $packageWorkflowStep->stepJobRole->one()->id, 'field' => 'userRole'];
        } else {
            $criteria->group = $packageWorkflowStep->stepUserGroup;
        }
        $criteria->limit = null;
        return $criteria;
    }

    /**
     *
     */
    public function getNextStep()
    {
        foreach($this->owner->packageReviews as $step) {
            if ($step->reviewUser->count() && !$step->reviewDate) {
                return $step;
            }
        }
        return null;
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