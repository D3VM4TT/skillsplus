<?php
/**
 * Lantra Skills Plus for Craft CMS 3.x
 *
 * @link      https://coffeebean.design
 * @copyright Copyright (c) 2020 Coffee Bean Design
 */

namespace lantra\spbase\models;

class Site extends SpBase
{
    public $subdomain;
    public $siteExpiryDate;
    public $licenceModel;
    public $hasCompanyLicences;
    public $totalActive;
    public $totalRemaining;

    /**
     * @return string|void
     */
    public function getLicenceModelLabel()
    {
        if ($this->licenceModel == 1) {
            return 'Site Advance';
        }
        if ($this->licenceModel == 2) {
            return 'Site Arrears';
        }
        if ($this->licenceModel == 3) {
            return 'User Advance';
        }
        if ($this->licenceModel == 4) {
            return 'User Arrears';
        }
    }
}