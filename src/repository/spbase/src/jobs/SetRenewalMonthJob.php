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

class SetRenewalMonthJob extends BaseJob
{
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
        $criteria->userStartDate(':notempty:');

        if ($this->userId) {
            $criteria->id($this->userId);
        }

        $total = $criteria->count();

        foreach($criteria->all() as $i => $user) {

            $label = $i + 1 . ' of ' . $total;
            $this->setProgress($queue, $i / $total, $label);

            try {
                $month = $user->userStartDate->format('m');
                $user->setFieldValue('userLicenceMonth', $month);
                Craft::$app->elements->saveElement($user);
            } catch (\Throwable $e) {
                Module::warning("Could not save user {$user->id}: {$e->getMessage()}");
            }
        }
    }
}