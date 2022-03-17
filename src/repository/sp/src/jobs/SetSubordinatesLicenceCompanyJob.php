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
use lantra\sp\Plugin as Lantra;

class SetSubordinatesLicenceCompanyJob extends BaseJob
{
    /**
     * @var bool
     */
    public $companyId;

    /**
     * @var bool
     */
    public $includeHierarchy;

    /**
     * @inheritdoc
     */
    public function execute($queue): void
    {
        $companySubordinates = Lantra::$app->users->getCompanySubordinates($this->companyId, $this->includeHierarchy);
        $total = count($companySubordinates);

        foreach($companySubordinates as $i => $user) {

            $label = $i + 1 . ' of ' . $total;
            $this->setProgress($queue, $i / $total, $label);

            try {
                $user->setFieldValue('userLicenceCompany', [$this->companyId]);
                Craft::$app->elements->saveElement($user);
            } catch (\Throwable $e) {
                Craft::warning("Could not save user {$user->id}: {$e->getMessage()}");
            }
        }
    }
}