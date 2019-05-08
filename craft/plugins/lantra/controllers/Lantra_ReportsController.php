<?php

namespace Craft;

class Lantra_ReportsController extends Lantra_BaseController
{
    /**
     * Create a new automated report
     *
     * @throws Exception
     */
    public function actionCreate()
    {
        craft()->userSession->requireLogin();
        $manager = craft()->userSession->getUser();
        $fields = craft()->request->getParam('fields');
        $type = $fields['reportType'];
        $automated = craft()->request->getParam('automated');
        if ( ! $automated) {
            $data = craft()->lantra_reports->getCustomReportData($manager, $type, $fields);
            return craft()->lantra_reports->downloadReport($type, $data);
        }

        $result = craft()->lantra_reports->saveCustomReport($manager, $fields);
        if ($result !== true) {
             return $this->_returnError('Could not save report.');
        }

        $redirect = '/reporting/automated';
        $this->_returnMessage('Automated report has been saved.', true, $redirect);
    }

    /**
     * Run  specific report
     *
     * @throws mixed
     */
    public function actionRun() {
        $this->requirePostRequest();
        craft()->userSession->requireLogin();
        // get the posted entryId
        $entryId = craft()->request->getPost('entryId');
        if (false == $entry = craft()->entries->getEntryById($entryId)) {
            $this->_returnError('Invalid entry ID ' . $entryId . '.');
        }
        $total = craft()->lantra_reports->runCustomReport($entry);
        if ($total) {
            return $this->_returnMessage( $entry->title . ' has been successfully run (' . $total . ' rows).', true);

        }
        $this->_returnMessage( $entry->title . ' currently has no data.', false);
    }
}