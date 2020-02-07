<?php
/**
 * Lantra Skills Plus for Craft CMS 3.x
 *
 * @link      https://coffeebean.design
 * @copyright Copyright (c) 2020 Coffee Bean Design
 */

namespace lantra\sp\controllers;

use Craft;

use lantra\sp\Plugin as Lantra;

class ReportsController extends BaseController
{
    /**
     * @return void|\yii\web\Response
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\BadRequestHttpException
     */
    public function actionSaveReport()
    {
        $manager = Craft::$app->getUser();
        $fields = $this->getFields();
        if (!$fields['reportAllCompanies'] && !count($fields['reportCompanies'])) {
            Craft::$app->session->setError('You must select some companies or select Include all companies.');
            return;
        }
        $type = $fields['reportType'];
        $automated = Craft::$app->request->getParam('automated');
        $entryId = Craft::$app->request->getParam('entryId');
        $title = Craft::$app->request->getParam('title');
        ## custom title
        if ($automated) {
            $fields['reportAutomated'] = true;
            $redirect = 'reporting/automated';
        }
        else {
            $fields['reportAutomated'] = false;
            $redirect = 'reporting/custom';
        }
        $reportEntry = Lantra::$app->reports->saveCustomReport($manager, $title, $fields, $entryId);
        if ($reportEntry->hasErrors()) {
            Craft::$app->urlManager->setRouteParams(['entry' => $reportEntry]);
            return;
        }
        ## add to queue
        if (!$automated) {
            Lantra::$app->queue->add($reportEntry->id);
        }
        return $this->_returnMessage('Custom report has been saved.', true, $redirect);
    }

    /**
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\BadRequestHttpException
     */
    public function actionDeleteReport()
    {
        $this->requirePostRequest();
        $manager = Craft::$app->getUser();
        ## get the posted entryId
        $entryId = Craft::$app->request->getParam('entryId');
        if (false == $entry = Craft::$app->entries->getEntryById($entryId)) {
            $this->_returnError('Invalid entry ID ' . $entryId . '.');
        }
        if ($manager->id != $entry->getAuthor()->id) {
            $this->_returnError('Invalid report author.');
        }
        ## delete report assets
        if ($entry->reportData) {
            foreach ($entry->reportData->all() as $asset) {
                Craft::$app->elements->deleteElement($asset);
            }
        }
        Craft::$app->elements->deleteElementById($entryId);
        $this->_returnMessage( 'Report has been deleted.', true);
    }

    /**
     * Run  specific report
     *
     * @throws mixed
     */
    public function actionRunReport()
    {
        $this->requirePostRequest();
        ## get the posted entryId
        $entryId = Craft::$app->request->getParam('entryId');
        if (false == $entry = Craft::$app->entries->getEntryById($entryId)) {
            $this->_returnError('Invalid entry ID ' . $entryId . '.');
        }
        $response = Lantra::$app->reports->runCustomReport($entry);
        if ($response['success']) {
            return $this->_returnMessage( $entry->title . ' has been successfully run (' . $response['total'] . ' rows).', true);

        }
        $this->_returnMessage($response['message'],false);
    }

    /**
     * @return array
     */
    private function getFields()
    {
        $fields = Craft::$app->request->getParam('fields');
        if (!$fields['reportCompanies']) {
            $fields['reportCompanies'] = [];
        }
        if (!$fields['reportUnits']) {
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
            ## automated fields
            'reportTitle'               => '',
            'reportRecipients'          => [],
            'reportEmails'              => '',
            'reportSendFrequency'       => 'never',
            'reportSendValue'           => 1
        ];
        return array_merge($default, $fields);
    }

}