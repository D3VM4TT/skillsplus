<?php

namespace Craft;

class Lantra_EntriesController extends Lantra_BaseController {

    public $allowAnonymous = array(
        'actionDeleteEntry',
        'actionEndorseEvidence',
        'actionResetResult'
    );

    /**
     * Unlinks unit result attempts
     *
     * @throws mixed
     */
    public function actionResetResult() {
        $this->requirePostRequest();
        craft()->userSession->requireLogin();
        // get the posted entryId
        $entryId = craft()->request->getPost('entryId');
        if (false == $entry = craft()->entries->getEntryById($entryId)) {
            $this->_returnError('Invalid entry ID ' . $entryId . '.');
        }
        $entry->setContentFromPost(['resultAttempts' => []]);
        craft()->entries->saveEntry($entry);
        $this->_returnMessage( 'Result attempts unlinked.', true, craft()->request->getUrlReferrer());
    }

    /**
     * Deletes entries from the front end
     *
     * @throws mixed
     */
    public function actionDeleteEntry() {
        $this->requirePostRequest();
        craft()->userSession->requireLogin();
        // get the posted entryId
        $entryId = craft()->request->getPost('entryId');
        if (false == $entry = craft()->entries->getEntryById($entryId)) {
            $this->_returnError('Invalid entry ID ' . $entryId . '.');
        }
        // if removing a company, disable teams and children
        if ($entry->section->id == 3) {
            $this->_disableTeams($entry);
            $this->_disableChildren($entry);
        }
        // save disabled entry
        $this->_disableEntry($entry);
        $this->_returnMessage('Entry has been removed.', TRUE, craft()->request->getUrlReferrer());
    }

    /**
     * Endorses evidence
     *
     * @throws mixed
     */
    public function actionEndorseEvidence() {
        $this->requirePostRequest();
        craft()->userSession->requireLogin();
        // get all the posted entryIds
        $entryIds = craft()->request->getPost('entryIds');
        $count = 0;
        // loop entries and update status
        foreach ($entryIds as $entryId) {
            if (FALSE != $entry = craft()->entries->getEntryById($entryId)) {
                $entry->setContentFromPost(['resultStatus' => 'endorsed']);
                craft()->entries->saveEntry($entry);
                $count ++;
            }
        }
        $this->_returnMessage($count . ' results endorsed.');
    }

    /**
     * @param $entry
     * @throws mixed
     */
    private function _disableEntry ($entry) {
        // return company licences back to scheme
        if ($entry->section->id == 3) {
            craft()->lantra_licence->addSchemeLicences($entry->companyRemainingLicences);
            $entry->setContentFromPost(['companyRemainingLicences' => 0]);
        }
        $entry->enabled = false;
        craft()->entries->saveEntry($entry);
    }

    /**
     * @param $company
     * @throws Exception
     */
    private function _disableTeams($company) {
        $criteria = craft()->elements->getCriteria(ElementType::Entry);
        $criteria->relatedTo = array(
            'targetElement' => $company,
            'field' => 'teamCompany'
        );
        $teams = craft()->elements->findElements($criteria);
        foreach ($teams as $team) {
            $this->_disableEntry($team);
        }
    }

    /**
     * @param $company
     * @throws Exception
     */
    private function _disableChildren($company) {
        $criteria = craft()->elements->getCriteria(ElementType::Entry);
        $criteria->relatedTo = array(
            'targetElement' => $company,
            'field' => 'companyParent'
        );
        $children = craft()->elements->findElements($criteria);
        foreach ($children as $child) {
            $this->_disableTeams($child);
            $this->_disableChildren($child);
            $this->_disableEntry($child);
        }
    }
}
