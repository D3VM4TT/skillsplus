<?php
namespace Craft;

use League\Csv\Writer;

class Lantra_ReportsService extends BaseApplicationComponent
{
    /**
     * Load in the vendor dependencies
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
     * @param $reportType
     * @param $data
     * @throws HttpException
     */
    public function sendReport($reportType, $data) {
        ob_start();
        $export = fopen('php://output', 'w');
        if ( ! count($data)) {
            return;
        }
        foreach ($data as $row) {
            if (is_array($row)) {
                fputcsv($export, $row);
            }
        }
        fclose($export);
        $content = ob_get_clean();
        $content = str_replace("\n", "\r\n", $content);
        craft()->request->sendFile('report-' . $reportType . '.csv', $content, array('forceDownload' => true, 'mimeType' => 'text/csv'));
    }

    /**
     * @param $manager
     * @param $type
     * @param $filter
     * @throws HttpException
     */
    public function getSpecialReport($manager, $type, $filter = []) {
        $userFilter = [];
        $resultFilter = [];
        if (isset($filter['companyIds'])) {
            $userFilter['relatedTo'] = [
                'targetElement' => $filter['companyIds'],
                'field' => 'userCompany'
            ];
        }
        if (isset($filter['unitIds'])) {
            $resultFilter['relatedTo'] = [
                'targetElement' => $filter['unitIds'],
                'field' => 'resultUnit'
            ];
        }
        if (isset($filter['resultType'])) {
            $resultFilter['resultType'] = $filter['resultType'];
        }
        if ($type == 'users') {
            $values = craft()->lantra_results->getManagerUserSummary($manager->id, $userFilter, $resultFilter);
        }
        elseif ($type == 'results') {
            $displayField = isset($filter['displayField']) ? $filter['displayField'] : 'expiryDate';
            $values = craft()->lantra_results->getManagerUserCompletedResults($manager->id, $userFilter, $resultFilter, $displayField);
        }
        ## @todo change to 'expired'
        elseif ($type == 'required') {
            $values = craft()->lantra_results->getManagerUnitRequiredResults($manager->id, $userFilter, $resultFilter);
        }

        return $this->sendReport($type, $values);

        /* @todo save report as asset for download later?

        // create csv file in temp folder
        $filePath = craft()->path->getTempUploadsPath();
        $fileName = $manager->id . '-' . $type . '.csv';

        $this->reportCsv($values, $filePath.$fileName);

        $sourceId = 3;
        $source = craft()->assetSources->getSourceTypeById($sourceId);
        $folder = craft()->assets->findFolder(array(
            'sourceId' => $sourceId,
        ));

        // copy to assets
        $response = $source->insertFileByPath($filePath . $fileName, $folder, $fileName, true);

        // delete temp file
        unlink($filePath . $fileName);

        $fileId = $response->getDataItem('fileId');
        return craft()->assets->getFileById($fileId);

         */
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
        $total = count($values);
        if ( ! $total ) {
            return 0;
        }
        // create csv file in temp folder
        $filePath = craft()->path->getTempUploadsPath();
        $fileName = $reportEntry->slug . '-' . time() . '.csv';
        $this->reportCsv($values, $filePath.$fileName);
        $sourceId = 3;
        $source = craft()->assetSources->getSourceTypeById($sourceId);
        $folder = craft()->assets->findFolder(array(
            'sourceId' => $sourceId,
        ));
        $response = $source->insertFileByPath($filePath . $fileName, $folder, $fileName, true);
        $fileId = $response->getDataItem('fileId');
        // append asset to report entry
        $reportEntry->setContentFromPost(['reportData' => array_merge($reportEntry->reportData->ids(), [$fileId])]);
        craft()->entries->saveEntry($reportEntry);
        // send notification if applicable
        if ($reportEntry->reportSendFrequency != 'never') {
            $asset = craft()->assets->getFileById($fileId);
            $attachment = [
                'path' => $filePath . $fileName,
                'filename' => $fileName,
                'mimeType' => $asset->getMimeType()
            ];
            craft()->lantra_notify->notify(explode(',', $reportEntry->reportRecipients), $reportEntry->title, '', [$attachment]);
            $reportEntry->setContentFromPost(['reportLastSentDate' => time()]);
            craft()->entries->saveEntry($reportEntry);
        }
        // delete the temp file
        unlink($filePath . $fileName);
        return $total;
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
            $data = $users->find();
        }
        elseif ($reportEntry->reportType == 'results') {
            $data = $this->getReportDataResults($users->ids(), $reportEntry);
        }
        return $count ? $data->total() : $this->formatReportValues($data, $reportEntry->type);
    }

    /**
     * Format report values
     *
     * @param array
     * @param string
     * @return array
     */
    private function formatReportValues($data, $type)
    {
        $return = [];
        foreach($data as $row) {
            // user fields go in all reports
            $user = ($type == 'result') ? $row->author : $row;
            $company = craft()->lantra_users->userCompany($user);
            $roles = [];
            foreach ($user->userRole as $role) {
                $roles[] = $role->title;
            }
            $team = $user->userTeam->count() ? $user->userTeam->first()->title : '~';
            $record = [
                $user->fullName,
                $user->email,
                $company->title,
                $team,
                implode(', ', $roles),
                $user->userStartDate ? $user->userStartDate->format('d/m/y') : '',
                $user->userStartDate ? $user->userDateOfBirth->format('d/m/y') : '',
                $user->userAddress,
                $user->userTelephone,
            ];
            foreach ($user->userCustomFields as $block) {
                $record = array_merge($record, [$block->customValue]);
            }
            // add the result fields
            if ($type == 'result') {
                $record = array_merge($record, [$row->resultModule->first()->title, $row->expiryDate->timestamp()]);
            }
            $return[] = $record;
        }
        return $return;
    }

    /**
     * Get the result data related to a report entry
     *
     * @param $userIds
     * @param $reportEntry
     * @return array
     * @throws mixed
     */
    private function getReportDataResults($userIds, $reportEntry) {
        $days = $reportEntry->reportResultExpiry;
        $expiring = true;
        if ($days == '0'){
            $expiring = 'expired';
            $days = 'all';
        }
        $criteria = craft()->lantra_results->getModuleResults($days, null, $expiring, 'complete', null, $userIds);
        return $criteria->find();
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
        return $criteria;
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
     * @param string $filePath
     */
    private function reportCsv(array &$values, $filePath = 'report.csv')
    {
        $data = $values;
        $csv = Writer::createFromPath($filePath, "w");
        $csv->insertAll($data);
    }
}
