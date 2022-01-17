<?php
/**
 * Lantra Skills Plus for Craft CMS 3.x
 *
 * @link      https://coffeebean.design
 * @copyright Copyright (c) 2020 Coffee Bean Design
 */

namespace lantra\spbase\models;

class Company extends SpBase
{
    public $valid = false;

    public $relatedSite;

    /**
     * @param $relatedSite
     */
    public function setRelatedSite($relatedSite)
    {
        $this->relatedSite = $relatedSite[0]->id;
    }
}