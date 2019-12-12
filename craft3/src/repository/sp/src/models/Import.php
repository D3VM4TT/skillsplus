<?php
/**
 * Lantra Skills Plus for Craft CMS 3.x
 *
 * @link      https://coffeebean.design
 * @copyright Copyright (c) 2020 Coffee Bean Design
 */

namespace lantra\sp\models;

use Craft;
use craft\base\Model;

class Import extends Model
{
    public $type;
    public $data;
    public $processed;

    /**
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules[] = ['type', 'string'];
        $rules[] = ['data', 'string'];
        $rules[] = ['processed', 'number'];
        return $rules;
    }
}