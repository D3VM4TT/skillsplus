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

use lantra\spbase\Module;

class ResaveUsersJob extends BaseJob
{
    /**
     * @var bool
     */
    public $hasLicence = true;

    /**
     * @inheritdoc
     */
    public function execute($queue): void
    {
        $criteria = User::find();
        $criteria->group = ['users', 'companyManagers', 'teamManagers'];
        $criteria->admin(0);

        if ($this->hasLicence) {
            $criteria->userLicenceId(':notempty:');
        }

        $total = $criteria->count();

        foreach($criteria->all() as $i => $user) {

            $label = $i + 1 . ' of ' . $total;
            $this->setProgress($queue, $i / $total, $label);

            try {
                Craft::$app->elements->saveElement($user);
            } catch (\Throwable $e) {
                Module::warning("Could not save user {$user->id}: {$e->getMessage()}");
            }
        }
    }
}