<?php

namespace Craft;

class Lantra_ReportsController extends Lantra_BaseController
{
    /**
     * @return array
     */
    private function getFields(){
        $fields = craft()->request->getParam('fields');
        if ( ! $fields['reportCompanies']) {
            $fields['reportCompanies'] = [];
        }
        if ( ! $fields['reportUnits']) {
            $fields['reportUnits'] = [];
        }
        $default = [
            'reportType'                => 'users',
            'reportCompanies'           => [],
            'reportAllCompanies'        => false,
            'reportIncludeHierarchy'    => false,
            'reportResultType'          => 'all',
            'reportUnits'               => [],
            'reportResultExpiry'        => 0,
            'reportIncludeExpired'      => false,
            'reportIncludeRequired'     => false,
            'reportDisplayField'        => 'expiryDate',
            'reportAutomated'           => false,
            // automated fields
            'reportTitle'               => '',
            'reportRecipients'          => [],
            'reportEmails'              => '',
            'reportSendFrequency'       => 'never',
            'reportSendValue'           => 1
        ];
        return array_merge($default, $fields);
    }

    /**
     * @return null|void
     * @throws Exception
     * @throws \CException
     */
    public function actionSave()
    {
        craft()->userSession->requireLogin();
        $manager = craft()->userSession->getUser();
        $fields = $this->getFields();
        if (! $fields['reportAllCompanies'] && ! count($fields['reportCompanies'])) {
            $this->_returnError('You must select some companies or select Include all companies.');
        }
        $type = $fields['reportType'];
        $automated = craft()->request->getParam('automated');
        $entryId = craft()->request->getParam('entryId');
        $title = craft()->request->getParam('title');
        // custom title
        if ($automated) {
            $fields['reportAutomated'] = true;
            $redirect = '/reporting/automated';
        }
        else {
            $fields['reportAutomated'] = false;
            $redirect = '/reporting/custom';
        }
        $reportEntry = Lantra::$app->report->saveCustomReport($manager, $title, $fields, $entryId);
        if ($reportEntry->hasErrors()) {
            return craft()->urlManager->setRouteVariables(array('entry' => $reportEntry));
        }
        // add to queue
        if (!$automated) {
            Lantra::$app->queue->add($reportEntry->id);
        }
        return $this->_returnMessage('Custom report has been saved.', true, $redirect);
    }

    /**
     * @return null|void
     * @throws Exception
     * @throws \CException
     */
    public function actionDelete()
    {
        $this->requirePostRequest();
        craft()->userSession->requireLogin();
        $manager = craft()->userSession->getUser();
        // get the posted entryId
        $entryId = craft()->request->getPost('entryId');
        if (false == $entry = craft()->entries->getEntryById($entryId)) {
            $this->_returnError('Invalid entry ID ' . $entryId . '.');
        }
        if ($manager->id != $entry->getAuthor()->id) {
            $this->_returnError('Invalid report author.');
        }
        // delete report assets
        if ($entry->reportData) {
            craft()->assets->deleteFiles($entry->reportData->ids());
        }
        craft()->entries->deleteEntryById($entryId);
        $this->_returnMessage( 'Report has been deleted.', true);
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
        $total = Lantra::$app->report->runCustomReport($entry);
        if ($total) {
            return $this->_returnMessage( $entry->title . ' has been successfully run (' . $total . ' rows).', true);

        }
        $this->_returnMessage( $entry->title . ' currently has no data.', false);
    }
}