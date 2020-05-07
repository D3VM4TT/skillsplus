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
        return $this->isStepManager($user, $includeAdmin, 'assessment');
    }

    /**
     * @param user|null $user
     * @param bool $includeAdmin
     * @return bool
     */
    public function isReviewer(User $user = null, $includeAdmin = false)
    {
        return $this->isStepManager($user, $includeAdmin, 'review');
    }

    /**
     * @param user|null $user
     * @param bool $includeAdmin
     * @return bool
     */
    public function isCompleter(User $user = null, $includeAdmin = false)
    {
        return $this->isStepManager($user, $includeAdmin, 'complete');
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
     * @param bool $includeAdmin
     * @param string $type assessment|review|complete
     * @return bool
     */
    private function isStepManager(User $user = null, $includeAdmin = false, $type = 'assessment')
    {
        $manager = LantraHelper::getUser($user);

        ## admins and scheme managers can manage everyone
        if ($includeAdmin && ($manager->admin || $manager->isInGroup('schemeManagers'))) {
            return true;
        }
        foreach($this->owner->packageReviews as $step) {
            if ($step->stepType == $type && $step->stepUser->count() && $step->stepUser->one()->id == $manager->id) {
                return true;
            }
        }
        return false;
    }
}