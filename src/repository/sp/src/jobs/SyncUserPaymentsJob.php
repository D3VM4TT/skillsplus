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
use craft\elements\MatrixBlock;
use lantra\sp\Plugin as Lantra;

class SyncUserPaymentsJob extends BaseJob
{
    /**
     * @inheritdoc
     */
    public function execute($queue): void
    {
        $field = Craft::$app->fields->getFieldByHandle('userPayments');
        $criteria = MatrixBlock::find();
        $criteria->fieldId($field->id);
        $criteria->syncedToBase = FALSE;
        $payments = $criteria->all();
        $total = count($payments);

        foreach ($payments as $i => $block) {

            $label = $i + 1 . ' of ' . $total;
            $this->setProgress($queue, $i / $total, $label);

            try {
                $user = get_class($block->owner) == 'craft\elements\User' ? $block->owner : $block->owner->author;
                ## add payment to base
                if (Lantra::$app->spbase->addPayment($user->id, 'paypal', $block->mc_gross, $block->txn_id, ['payer_email' => $block->payer_email])) {
                    $block->setFieldValue('syncedToBase', TRUE);
                    Craft::$app->elements->saveElement($block, FALSE);

                    Craft::info('SyncUserPaymentsJob - added (' . $user->id . ' - ' . $block->txn_id . ')', __METHOD__);
                }
            } catch (\Throwable $e) {
                Craft::warning("Could not sync user payment {$block->id}: {$e->getMessage()}");
            }
        }

        Craft::info('SyncUserPaymentsJob - complete (' . $total . ')', __METHOD__);
    }
}