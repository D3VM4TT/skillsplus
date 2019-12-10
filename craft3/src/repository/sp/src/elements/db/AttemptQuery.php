<?php
/**
 * Lantra Skills Plus for Craft CMS 3.x
 *
 * @link      https://coffeebean.design
 * @copyright Copyright (c) 2020 Coffee Bean Design
 */

namespace lantra\sp\elements\db;

use craft\elements\db\ElementQuery;
use lantra\sp\elements\Attempt;

class AttemptQuery extends ElementQuery
{
    /**
     * @return bool
     */
    protected function beforePrepare(): bool
    {
        return parent::beforePrepare();
    }

    /**
     * @inheritdoc
     */
    protected function statusCondition(string $status)
    {
        return parent::statusCondition($status);
    }
}