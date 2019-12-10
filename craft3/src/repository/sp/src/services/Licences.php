<?php
/**
 * Lantra Skills Plus for Craft CMS 3.x
 *
 * @link      https://coffeebean.design
 * @copyright Copyright (c) 2020 Coffee Bean Design
 */

namespace lantra\sp\services;

use Craft;
use craft\base\Component;

class Licences extends Component
{
    /**
     * Assign user to a company licence
     *
     * @param $user
     * @return bool
     * @throws mixed
     */
    function assignCompanyLicence($user, $companyEntry) {
        // return false if none remaining
        if ( ! $companyEntry || ! $companyEntry->companyRemainingLicences) {
            return false;
        }
        $companyEntry->setContentFromPost([
            'companyRemainingLicences' => $companyEntry->companyRemainingLicences - 1
        ]);
        if ( ! Craft::$app->entries->saveEntry($companyEntry)) {
            return false;
        }
        return true;
    }

    /**
     * Assign a scheme licence
     *
     * @return bool
     * @throws mixed
     */
    function assignSchemeLicence() {
        // return false if none remaining
        if ( ! $this->getSchemeLicences()) {
            return false;
        }
        $this->subtractSchemeLicences();
        return true;
    }

    /**
     * Check a team companies remaining licences
     *
     * @param $teamEntry
     * @return int
     * @throws mixed
     */
    function getTeamCompanyLicences($teamEntry) {
        return (int) $teamEntry->teamCompany->first()->companyRemainingLicences;
    }

    /**
     * Handle company licence changes
     *
     * @param $companyEntry
     * @return bool
     * @throws mixed
     */
    function updateCompanyLicences($companyEntry) {
        // check existing company entry
        $oldEntry = Craft::$app->entries->getEntryById($companyEntry->id);
        $existingCompanyLicences = ($oldEntry) ? $oldEntry->companyRemainingLicences : 0;
        // look for change
        if ($existingCompanyLicences != $companyEntry->companyRemainingLicences) {
            // return scheme licences
            if ($existingCompanyLicences > $companyEntry->companyRemainingLicences) {
                $returnedLicences = $existingCompanyLicences - $companyEntry->companyRemainingLicences;
                $this->addSchemeLicences($returnedLicences);
            }
            // remove scheme licences
            else {
                $schemeLicences = $this->getSchemeLicences();
                $newLicences = $companyEntry->companyRemainingLicences - $existingCompanyLicences;
                // not enough scheme licences
                if ($newLicences > $schemeLicences) {
                    return false;
                }
                else {
                    $this->subtractSchemeLicences($newLicences);
                }
            }
        }
        return true;
    }

    /**
     * Add scheme licences
     *
     * @param $number
     * @throws mixed
     * @return mixed
     */
    function addSchemeLicences($number = 1) {
        $schemeRemainingLicences = (int) Lantra::$app->setting->getSetting('schemeRemainingLicences', 0);
        return Lantra::$app->setting->saveSetting('schemeRemainingLicences', $schemeRemainingLicences + (int) $number);
    }

    /**
     * Subtract scheme licences
     *
     * @param $number
     * @throws mixed
     * @return mixed
     */
    function subtractSchemeLicences($number = 1) {
        $schemeRemainingLicences = (int) Lantra::$app->setting->getSetting('schemeRemainingLicences', 0);
        return Lantra::$app->setting->saveSetting('schemeRemainingLicences', $schemeRemainingLicences - (int) $number);
    }

    /**
     * Get scheme expiry date
     *
     * @return DateTime $schemeExpiryDate
     */
    function getSchemeExpiryDate() {
        return Lantra::$app->setting->getSetting('schemeExpiryDate');
    }

    /**
     * Get scheme licences
     *
     * @return int $number
     */
    function getSchemeLicences() {
        return Lantra::$app->setting->getSetting('schemeRemainingLicences');
    }

    /** Get individual days till expiry
     *
     * @param null $user
     * @return null
     */
    public function getIndividualLicenceDays() {
        return Lantra::$app->setting->getSetting('individualLicenceDays');
    }
}
