<?php
/**
 * Lantra Skills Plus for Craft CMS 3.x
 *
 * @link      https://coffeebean.design
 * @copyright Copyright (c) 2020 Coffee Bean Design
 */

namespace lantra\sp\controllers;

use Craft;

use craft\elements\Entry;
use craft\helpers\DateTimeHelper;

use lantra\sp\helpers\LantraHelper;
use lantra\sp\Plugin as Lantra;

class EntriesController extends BaseController {

    /**
     * Sets all results to pending
     *
     * @throws mixed
     */
    public function actionPendingResult()
    {
        $this->requirePostRequest();
        $this->requireLogin();
        ## get the posted id, ref and userId
        $id =  Craft::$app->request->getParam('id');
        $ref =  Craft::$app->request->getParam('ref');
        $userId =  Craft::$app->request->getParam('userId');
        $results = [];
        if ($ref == 'jobRole') {
            $results = Lantra::$app->results->getJobRoleUserResults($id, $userId);
        }
        if ($ref == 'package') {
            $results = Lantra::$app->results->getPackageUserResults($id, $userId);
            Lantra::$app->packages->stepRequest($id);
        }
        $count = 0;
        ## loop entries and update status
        foreach ($results as $resultEntry) {
            $resultEntry->setFieldValue('resultStatus', 'pending');
            Craft::$app->elements->saveElement($resultEntry);
            $count ++;
        }
        $this->_returnMessage('Endorsement requested for ' . $count . ' result(s).', true);
    }

    /**
     * Unlinks unit result attempts and unblocks result
     *
     * @throws mixed
     */
    public function actionResetResult()
    {
        $this->requirePostRequest();
        ## get the posted entryId
        $entryId = Craft::$app->request->getParam('entryId');
        if (false == $entry = Craft::$app->entries->getEntryById($entryId)) {
            $this->_returnError('Invalid entry ID ' . $entryId . '.');
        }
        Lantra::$app->results->unblockResult($entry);
        $this->_returnMessage( 'Result attempts unlinked and result unblocked.', true, Craft::$app->request->getReferrer());
    }

    /**
     * Deletes entries from the front end
     *
     * @throws mixed
     */
    public function actionDeleteEntry()
    {
        $this->requirePostRequest();
        $return = LantraHelper::returnRef();
        ## get the posted entryId
        $entryId = Craft::$app->request->getParam('entryId');
        if (false == $entry = Craft::$app->entries->getEntryById($entryId)) {
            $this->_returnError('Invalid entry ID ' . $entryId . '.');
        }
        ## if removing a company, disable teams and children
        if ($entry->section->id == 3) {
            $this->_disableTeams($entry);
            $this->_disableChildren($entry);
        }
        ## if a result update cache
        if ($entry->section->id == 10 && $entry->type == 'unitResult') {
            Lantra::$app->results->deleteUserResultCache($entry);
        }
        ## save disabled entry
        $this->_disableEntry($entry);
        $this->_returnMessage('Entry has been removed.', true, $return);
    }

    /**
     * Endorses evidence
     *
     * @throws mixed
     */
    public function actionEndorseEvidence()
    {
        $this->requirePostRequest();
        $return = LantraHelper::returnRef();
        ## get all the posted entryId(s)
        if (false != $entryId = Craft::$app->request->getParam('entryId')) {
            $results = [['entryId' => $entryId]];
        }
        else {
            $results = Craft::$app->request->getParam('results');
        }
        $count = 0;
        ## loop entries and update status
        foreach ($results as $result) {
            if (isset($result['entryId']) && false != $entry = Craft::$app->entries->getEntryById($result['entryId'])) {
                if (Lantra::$app->results->endorseResult($entry)) {
                    $count ++;
                }
            }
        }
        $this->_returnMessage($count . ' results endorsed.', true, $return);
    }

    /**
     * @param $entry
     * @throws mixed
     */
    private function _disableEntry($entry)
    {
        ## return company licences back to scheme
        if ($entry->section->id == 3) {
            Lantra::$app->licences->addSchemeLicences($entry->companyRemainingLicences);
            $entry->companyRemainingLicences = 0;
        }
        $entry->enabled = false;
        Craft::$app->elements->saveElement($entry);
    }

    /**
     * Disable teams related to a company
     *
     * @param $company
     * @throws mixed
     */
    private function _disableTeams($company)
    {
        $teams = Entry::find()
            ->relatedTo (['targetElement' => $company, 'field' => 'teamCompany'])
            ->all();
        foreach ($teams as $team) {
            $this->_disableEntry($team);
        }
    }

    /**
     * Disable children of a company
     *
     * @param $company
     * @throws mixed
     */
    private function _disableChildren($company)
    {
        $children = Entry::find()
            ->relatedTo(['targetElement' => $company, 'field' => 'companyParent'])
            ->all();
        foreach ($children as $child) {
            $this->_disableTeams($child);
            $this->_disableChildren($child);
            $this->_disableEntry($child);
        }
    }
}
