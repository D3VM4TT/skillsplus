<?php
/**
 * Lantra Skills Plus for Craft CMS 3.x
 *
 * @link      https://coffeebean.design
 * @copyright Copyright (c) 2020 Coffee Bean Design
 */

namespace lantra\sp\records;

use craft\db\ActiveRecord;

/**
 * Class Import record.
 *
 */
class Import extends ActiveRecord
{
    /**
     *
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%lantra_import}}';
    }
}