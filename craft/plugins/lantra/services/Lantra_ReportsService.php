<?php
namespace Craft;

use League\Csv\Writer;

class Lantra_ReportsService extends BaseApplicationComponent
{
    /**
     *
     */
    public function init()
    {
        parent::init();

        require_once dirname(__FILE__) . '/../vendor/autoload.php';
    }

    /**
     * Run all reports for today
     *
     * @throws Mixed
     */
    public function sendDailyReports()
    {
        $weekDay = date('N');
        $monthDay = date('j');
        $reportEntries = $this->getReports();
        foreach ($reportEntries as $reportEntry) {
            if (($reportEntry->reportSendFrequency == 'weekly' && $reportEntry->reportSendValue == $weekDay) || ($reportEntry->reportSendFrequency == 'monthly' && $reportEntry->reportSendValue == $monthDay)) {
                $this->runReport($reportEntry);
            }
        }
    }

    /**
     * Get all reports
     *
     * @param object
     * @return null
     * @throws Mixed
     */
    public function runReport(EntryModel $reportEntry)
    {
        $values = $this->getReportData($reportEntry);
        if( ! count($values)) {
            return;
        }
        if ($reportEntry->reportType == 'users') {
            $labels = ['Name', 'Email', 'Companies', 'Teams', 'Job Roles'];
        }
        elseif ($reportEntry->reportType == 'modulesExpiring') {
            $labels = ['Name', 'Company'];
        }
        // create csv file in temp folder
        $filePath = craft()->path->getTempUploadsPath();
        $fileName = $reportEntry->slug . '-' . time() . '.csv';
        $this->reportCsv($values, $labels, $filePath.$fileName);
        $sourceId = 3;
        $source = craft()->assetSources->getSourceTypeById($sourceId);
        $folder = craft()->assets->findFolder(array(
            'sourceId' => $sourceId,
        ));
        $response = $source->insertFileByPath($filePath . $fileName, $folder, $fileName, true);
        // delete the temp file
        unlink($filePath . $fileName);
        $fileId = $response->getDataItem('fileId');
        // append asset to report entry
        $reportEntry->setContentFromPost(['reportData' => array_merge($reportEntry->reportData->ids(), [$fileId])]);
        craft()->entries->saveEntry($reportEntry);
        // send notification if applicable
        if ($reportEntry->reportSendFrequency != 'never') {
            $attachment = craft()->assets->getFileById($fileId);
            craft()->lantra_notify->notify(explode(',', $reportEntry->reportRecipients), $reportEntry->title, '### report attached ###', [$attachment]);
            $reportEntry->setContentFromPost(['reportLastSentDate' => time()]);
            craft()->entries->saveEntry($reportEntry);
        }
        return true;
    }

    /**
     * Get report data
     *
     * @param object
     * @param bool
     * @return null
     * @throws Mixed
     */
    public function getReportData($reportEntry, $count = false)
    {
        $data = [];
        $users = $this->getReportDataUsers($reportEntry);
        if ($reportEntry->reportType == 'users') {
            $data = $users;
        }
        if ($reportEntry->reportType == 'modulesExpiring') {
            $data = craft()->lantra_results->getModuleResults('all', null, true,'complete', null, $users->ids());
        }
        return $count ? $data->total() : $data;
    }

    /**
     * Get the users related to a report entry
     *
     * @param $reportEntry
     * @return array
     * @throws Exception
     */
    private function getReportDataUsers($reportEntry)
    {
        $criteria = craft()->elements->getCriteria(ElementType::User);
        $criteria->limit = null;
        $companyTeamIds = [];
        foreach($reportEntry->reportCompanies as $company) {
            $companyTeamIds = array_merge($companyTeamIds, craft()->lantra_users->getCompanyTeamIds($company->id));
        }
        $teamIds = array_merge($companyTeamIds,$reportEntry->reportTeams->ids());
        $jobRoleIds = $reportEntry->reportRoles->ids();
        $relatedToTeam = [
            'targetElement' => $teamIds,
            'field' => 'userTeam'
        ];
        $relatedToRole = [
            'targetElement' => $jobRoleIds,
            'field' => 'userRole'
        ];
        $relatedTo = [];
        if (count($teamIds) && count($jobRoleIds)) {
            $relatedTo = [
                'and',
                $relatedToTeam,
                $relatedToRole
            ];
        }
        elseif (count($teamIds)) {
            $relatedTo = $relatedToTeam;
        }
        elseif (count($jobRoleIds)) {
            $relatedTo = $relatedToRole;
        }
        $criteria->relatedTo = $relatedTo;
        return $criteria->find();
    }
    /**
     * Get all reports
     *
     * @param int
     * @param int
     * @return null
     * @throws Mixed
     */
    private function getReports()
    {
        $criteria = craft()->elements->getCriteria(ElementType::Entry);
        $criteria->section = 'reports';
        $criteria->limit = null;
        return $criteria->find();
    }

    /**
     * Takes an array of values and options labels and creates a downloadable CSV file
     *
     * @param array $values
     * @param array $labels
     * @param string $filePath
     */
    private function reportCsv(array &$values, array $labels = array(), $filePath = 'report.csv')
    {
        $filePath = str_replace('.csv', '', $filePath) . '.csv';
        if (empty($labels) && !empty($values)) {
            $arrayValues = array_values($values);
            $firstRowOfArray = array_shift($arrayValues);
            $labels = array_keys($firstRowOfArray);
        }
        $data = array_merge($labels, $values);
        $csv = Writer::createFromPath($filePath, "w");
        $csv->insertAll($data);
    }
}
