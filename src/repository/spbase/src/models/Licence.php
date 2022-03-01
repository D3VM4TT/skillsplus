<?php
/**
 * Lantra Skills Plus for Craft CMS 3.x
 *
 * @link      https://coffeebean.design
 * @copyright Copyright (c) 2020 Coffee Bean Design
 */

namespace lantra\spbase\models;

class Licence extends SpBase
{
    public $siteId;

    public $userId;

    public $payments;

    public $valid = false;

    /**
     * @param $relatedSite
     */
    public function setRelatedSite($relatedSite)
    {
        $this->siteId = $relatedSite[0]->id;
    }
}