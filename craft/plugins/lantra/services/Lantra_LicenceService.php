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
        if ($companyEntry && $companyEntry->companyRemainingLicences) {
            $companyEntry->setContentFromPost([
                'companyManager' => array($companyEntry->companyManager->first()->id),
                'companyRemainingLicences' => $companyEntry->companyRemainingLicences - 1
            ]);
            if ( ! craft()->entries->saveEntry($companyEntry)) {
                return false;
            }
            return true;
        }
        // return false if none remaining
        return false;
    }

    /**
     * Assign a scheme licence
     *
     * @param $user
     * @return bool
     * @throws mixed
     */
    function assignSchemeLicence() {
        $globalsScheme = craft()->globals->getSetByHandle('scheme');
        // return false if none remaining
        if ( ! $globalsScheme->schemeRemainingLicences) {
            return false;
        }
        $content = ['schemeRemainingLicences' => $globalsScheme->schemeRemainingLicences - 1];
        $globalsScheme->setContentFromPost($content);
        craft()->globals->saveContent($globalsScheme);
        return true;
    }
}
