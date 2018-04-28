<?php
namespace Craft;

class Lantra_LicenceService extends BaseApplicationComponent
{
    /**
     * Assign user to a company licence
     *
     * @param $user
     * @return bool
     * @throws mixed
     */
    function assignCompanyLicence($user) {
        $companyEntry = $this->userCompany($user);
        // return false if none remaining
        if ( ! $companyEntry || ! $companyEntry->companyRemainingLicences) {
            return false;
        }
        $companyEntry->setContentFromPost([
            'companyManager' => array($companyEntry->companyManager->first()->id),
            'companyRemainingLicences' => $companyEntry->companyRemainingLicences - 1
        ]);
        if ( ! craft()->entries->saveEntry($companyEntry)) {
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
        $oldEntry = craft()->entries->getEntryById($companyEntry->id);
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
        $globalsScheme = craft()->globals->getSetByHandle('scheme');
        $content = ['schemeRemainingLicences' => $globalsScheme->schemeRemainingLicences + (int) $number];
        $globalsScheme->setContentFromPost($content);
        return craft()->globals->saveContent($globalsScheme);
    }

    /**
     * Subtract scheme licences
     *
     * @param $number
     * @throws mixed
     * @return mixed
     */
    function subtractSchemeLicences($number = 1) {
        $globalsScheme = craft()->globals->getSetByHandle('scheme');
        $content = ['schemeRemainingLicences' => $globalsScheme->schemeRemainingLicences - (int) $number];
        $globalsScheme->setContentFromPost($content);
        return craft()->globals->saveContent($globalsScheme);
    }

    /**
     * Get scheme licences
     *
     * @return int $number
     */
    function getSchemeLicences() {
        $globalsScheme = craft()->globals->getSetByHandle('scheme');
        return (int) $globalsScheme->schemeRemainingLicences;
    }
}
