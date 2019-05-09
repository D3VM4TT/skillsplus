<?php

namespace Craft;

class Lantra_ReportsController extends Lantra_BaseController
{
    /**
     * Create a new automated report
     *
     * @throws Exception
     */
    public function actionSave()
    {
        craft()->userSession->requireLogin();
        $manager = craft()->userSession->getUser();
        $fields = craft()->request->getParam('fields');
        $type = $fields['reportType'];
        $automated = craft()->request->getParam('automated');
        $entryId = craft()->request->getParam('entryId');
        if ($entryId || $automated) {
            $reportEntry = craft()->lantra_reports->saveCustomReport($manager, $fields, $entryId);
            if ($reportEntry->hasErrors()) {
                craft()->urlManager->setRouteVariables(array('entry' => $reportEntry));
                return $this->redirectToPostedUrl();
            }
            $redirect = '/reporting/automated';
            return $this->_returnMessage('Custom report has been saved.', true, $redirect);
        }
        $data = craft()->lantra_reports->getCustomReportData($manager, $type, $fields);
        return craft()->lantra_reports->downloadReport($type, $data);
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