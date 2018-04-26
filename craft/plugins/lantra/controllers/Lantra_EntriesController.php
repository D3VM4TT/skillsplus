<?php

namespace Craft;

class Lantra_EntriesController extends Lantra_BaseController {

    public $allowAnonymous = array(
        'actionDeleteEntry',
        'actionEndorseEvidence'
    );

    /**
     * Deletes entries from the front end
     *
     * @throws Exception
     */
    public function actionDeleteEntry()
    {
        $this->requirePostRequest();
        craft()->userSession->requireLogin();

        $entryId = craft()->request->getPost('entryId');
        if (FALSE == $entry = craft()->entries->getEntryById($entryId)) {
            $this->_returnError('Invalid entry ID.');
        }

        // company
        if ($entry->section->id == 3) {
            $this->_disableTeams($entry);
            $this->_disableChildren($entry);
        }

        $this->_disableEntry($entry);

        $this->_returnMessage('Entry has been removed.', TRUE, craft()->request->getUrlReferrer());
    }

    /**
     * Endorses evidence
     *
     * @throws Exception
     */
    public function actionEndorseEvidence()
    {
        $this->requirePostRequest();
        craft()->userSession->requireLogin();

        $entryIds = craft()->request->getPost('entryIds');
        $count = 0;

        foreach ($entryIds as $entryId) {
            if (FALSE != $entry = craft()->entries->getEntryById($entryId)) {
                $entry->setContentFromPost(['resultStatus' => 'endorsed']);
                craft()->entries->saveEntry($entry);
                $count ++;
            }
        }

        $this->_returnMessage('Evidence endorsed for ' . $count . ' entries.');
    }

    protected function _disableEntry ($entry) {
        $entry->enabled = false;
        craft()->entries->saveEntry($entry);
    }

    protected function _disableTeams($company) {
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

    protected function _disableChildren($company) {
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
