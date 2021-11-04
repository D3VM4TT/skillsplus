<?php
/**
 * Lantra Skills Plus for Craft CMS 3.x
 *
 * @link      https://coffeebean.design
 * @copyright Copyright (c) 2020 Coffee Bean Design
 */

namespace lantra\sp\controllers;

use Craft;

use yii\web\HttpException;
use lantra\sp\helpers\ReportHelper;
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
        }
        else {
            $fields['reportAutomated'] = false;
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
        if ($automated) {
            $redirect = 'reporting/automated';
        }
        else {
            $redirect = ReportHelper::isStandardReport($reportEntry) ? 'reporting/data/' . $reportEntry->id : 'reporting';
        }
        return $this->_returnMessage('Report has been saved.', true, $redirect);
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
    public function actionRunCustomReport(int $entryId)
    {
        if (false == $entry = Craft::$app->entries->getEntryById($entryId)) {
            $this->_returnError('Invalid entry ID ' . $entryId . '.');
        }
        $response = Lantra::$app->reports->runCustomReport($entry);
        if ($response['success']) {
            $asset = $entry->reportData->one();
            $volumePath = rtrim($asset->getVolume()->settings['path'], '/') . '/';
            $folderPath = rtrim($asset->getFolder()->path, '/') . '/';
            $assetFilePath = Craft::getAlias($volumePath) . $folderPath . $asset->filename;
            if (!is_file($assetFilePath)) {
                throw new HttpException(404, "Asset file does not exist.");
                return;
            }
            return Craft::$app->response->sendFile($assetFilePath);
        }
        $this->_returnError($response['message']);
    }

    /**
     * @param string $ext
     * @param int $entryId
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function actionDownloadReport($ext, int $entryId)
    {
        if (false == $reportEntry = Craft::$app->entries->getEntryById($entryId)) {
            $this->_returnError('Invalid entry ID ' . $entryId . '.');
        }
        $criteria = Lantra::$app->reports->reportDataCriteria($reportEntry, null);
        $results = $criteria->all();
        $data[] = ReportHelper::reportHeader($reportEntry, false);
        foreach ($results as $row) {
            $data[] = ReportHelper::reportRow($reportEntry, $row,false);
        }
        Lantra::$app->reports->reportDownload($ext, $data, $reportEntry->id);
    }

    /**
     * @param $data
     * @param $name
     * @throws \yii\web\HttpException
     * @throws \yii\web\RangeNotSatisfiableHttpException
     */
    private function reportCsv($data, $name)
    {
        ob_start();
        $export = fopen('php://output', 'w');
        foreach ($data as $row) {
            fputcsv($export, $row);
        }
        fclose($export);
        $content = ob_get_clean();
        $content = str_replace("\n", "\r\n", $content);
        Craft::$app->response->sendContentAsFile($content, $name, ['mimeType' => 'text/csv']);
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