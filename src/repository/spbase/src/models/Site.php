<?php
/**
 * Lantra Skills Plus for Craft CMS 3.x
 *
 * @link      https://coffeebean.design
 * @copyright Copyright (c) 2020 Coffee Bean Design
 */

namespace lantra\spbase\models;

use lantra\sp\helpers\LantraHelper;

class Site extends SpBase
{
    public $subdomain;
    public $siteExpiryDate;
    public $licenceTypeSite = 'none';
    public $licenceTypeUser = 'none';
    public $hasCompanyLicences;
    public $totalActive;
    public $totalRemaining;

    /**
     * @return string|void
     */
    public function getLicenceModelLabel()
    {
        $model = '';

        if ($this->licenceTypeSite != 'none') {
            $model = 'Site ' . ucfirst($this->licenceTypeSite);
        }

        if ($this->licenceTypeUser != 'none') {
            $model .= ($model ? ' - '  : '') . 'User ' . ucfirst($this->licenceTypeUser);
        }

        return $model;
    }

    /**
     * @return bool
     */
    public function getEnabled()
    {
        return LantraHelper::enableBase();
    }
}