<?php
/**
 * Lantra Skills Plus for Craft CMS 3.x
 *
 * @link      https://coffeebean.design
 * @copyright Copyright (c) 2020 Coffee Bean Design
 */

namespace lantra\sp\behaviors;

use yii\base\Behavior;

class MagicTitleBehavior extends Behavior
{
    public $owner;

    /**
     * @return mixed
     */
    public function magicTitle()
    {
        $title = !empty($this->owner->shortTitle) ? $this->owner->shortTitle : $this->owner->title;
        if (!empty($this->owner->code)) {
            $title .= '(' . $this->owner->code . ')';
        }
        return $title;
    }
}