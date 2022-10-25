<?php
/**
 * Lantra Skills+ Base for Craft CMS 3.x
 *
 * @link      https://coffeebean.design
 * @copyright Copyright (c) 2022 Coffee Bean Design
 */

namespace lantra\sp\jobs;

use Craft;
use craft\queue\BaseJob;
use craft\elements\User;
use lantra\sp\Plugin as Lantra;

class SyncBasePaymentsJob extends BaseJob
{
    /**
     * @inheritdoc
     */
    public function execute($queue): void
    {
        $users = User::find()->all();
        $total = count($users);

        $totalCount = 0;

        Craft::info('SyncBasePaymentsJob - total users ' . $total, __METHOD__);

        foreach ($users as $i => $user) {

            $label = $i + 1 . ' of ' . $total;
            $this->setProgress($queue, $i / $total, $label);

            try {
                ## add payments from base
                $count = Lantra::$app->users->syncUserPayments($user);
                $totalCount = $totalCount + $count;
                if ($count) {
                    Craft::info('SyncBasePaymentsJob - user ' . $user->id . ' (' . $count . ')', __METHOD__);
                }
            } catch (\Throwable $e) {
                Craft::warning("Could not save user {$user->id}: {$e->getMessage()}");
            }
        }

        Craft::info('SyncBasePaymentsJob - complete (' . $totalCount . ')', __METHOD__);
    }
}