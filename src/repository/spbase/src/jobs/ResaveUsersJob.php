<?php
/**
 * Lantra Skills+ Base for Craft CMS 3.x
 *
 * @link      https://coffeebean.design
 * @copyright Copyright (c) 2022 Coffee Bean Design
 */

namespace lantra\spbase\jobs;

use Craft;
use craft\elements\User;
use craft\queue\BaseJob;

use lantra\sp\Plugin as Lantra;

use lantra\spbase\Module;

class ResaveUsersJob extends BaseJob
{
    /**
     * @var bool
     */
    public $hasLicence = true;

    /**
     * @var bool
     */
    public $userId;

    /**
     * @inheritdoc
     */
    public function execute($queue): void
    {
        $criteria = User::find();

        if ($this->userId) {
            $criteria->id($this->userId);
        }
        else {
            $criteria->group = ['users', 'companyManagers', 'teamManagers'];
            $criteria->admin(0);
        }

        if ($this->hasLicence) {
            $criteria->userLicenceId(':notempty:');
        }

        $total = $criteria->count();

        foreach($criteria->all() as $i => $user) {

            $label = $i + 1 . ' of ' . $total;
            $this->setProgress($queue, $i / $total, $label);

            try {
                ## Craft::$app->elements->saveElement($user);
                Lantra::$app->users->syncUserLicence($user);
            } catch (\Throwable $e) {
                Module::warning("Could not save user {$user->id}: {$e->getMessage()}");
            }
        }
    }
}