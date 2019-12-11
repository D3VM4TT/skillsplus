<?php
/**
 * Lantra Skills Plus for Craft CMS 3.x
 *
 * @link      https://coffeebean.design
 * @copyright Copyright (c) 2020 Coffee Bean Design
 */

namespace lantra\sp\services;

use Craft;
use craft\base\Component;

use League\Csv\Writer;

class Reports extends Component
{
    private $sectionIdReports = 13;
    private $typeIdReport = 15;

    /**
     * @param $search
     * @param $limit
     * @param $order
     * @param $automated
     * @return array
     */
    public function reportCriteria($search = '', $limit = 25, $order = 'title', $automated = false) {
        $user = Craft::$app->getUser();
        $criteria = Entry::find();
        $criteria->section = 'reports';
        $criteria->limit = $limit;
        $criteria->order = $order;
        if ($automated) {
            $criteria->reportAutomated = '1';
        }
        else {
            $criteria->reportAutomated = 'not 1';
            $criteria->authorId = $user->id;
        }
        if ($search) {
            $searchIds = $this->searchReportIds(trim($search));
            if (empty($searchIds)) {
                return null;
            }
            $criteria->id = 'or, ' . implode(',', $searchIds);
        }
        return $criteria;
    }

    /** more efficient way to search companies */
    private function searchReportIds($search = '') {
        if (intval($search)) {
            $mysql = 'SELECT c.elementId as id FROM {{%content}} c                
                WHERE c.elementId = "' . $search . '"';
        }
        else {
            $mysql = 'SELECT c.elementId as id FROM {{%content}} c               
                WHERE c.title LIKE "%' . $search . '%"';
        }

        $result = craft()->db->createCommand($mysql)->query();
        $ids = [];
        foreach ($result as $row) {
            $ids [] = $row['id'];
        }
        return $ids;
    }

    /**
     * Run all reports for today
     *
     * @throws Mixed
     */
    public function sendDailyReports()
    {
        $weekDay = (int) date('N');
        $monthDay = (int) date('j');
        $reportEntries = $this->getAutomatedReports();
        foreach ($reportEntries as $reportEntry) {
            $reportSendValue = (int) $reportEntry->reportSendValue;
            $reportSendFrequency = $reportEntry->reportSendFrequency->value;
            if (($reportSendFrequency == 'weekly' && $reportSendValue == $weekDay) || ($reportSendFrequency == 'monthly' && $reportSendValue == $monthDay)) {
                Lantra::$app->queue->add($reportEntry->id, 9);
            }
        }
    }

    /**
     * @param $reportType
     * @param $data
     * @throws HttpException
     */
    public function downloadReport($reportType, $data) {
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
        Craft::$app->request->sendFile('report-' . $reportType . '.csv', $content, array('forceDownload' => true, 'mimeType' => 'text/csv'));
    }

    /**
     * @param $author
     * @param string $title
     * @param array $fields
     * @param null $entryId
     * @return EntryModel|null
     * @throws \Exception
     */
    public function saveCustomReport($author, $title = '', $fields = [], $entryId = null) {
        if ($entryId) {
            $reportEntry = Craft::$app->entries->getEntryById($entryId);
        }
        else {
            $reportEntry = new EntryModel();
            $reportEntry->sectionId = $this->sectionIdReports;
            $reportEntry->typeId = $this->typeIdReport;
            $reportEntry->enabled = true;
            $reportEntry->authorId = $author->id;
        }
        $reportEntry->getContent()->title = $title;
        $reportEntry->setContentFromPost($fields);
        Craft::$app->entries->saveEntry($reportEntry);
        return $reportEntry;
    }

    /**
     * @param $reportEntry
     * @return array
     */
    public function getCustomReportFilter($reportEntry) {

        $filter = [
            'reportResultType'          => $reportEntry->reportResultType->value,
            'reportDisplayField'        => $reportEntry->reportDisplayField->value,
            'reportResultExpiry'        => $reportEntry->reportResultExpiry->value,
            // 'reportNoDates'             => $reportEntry->reportNoDates,
            'reportIncludeHierarchy'    => $reportEntry->reportIncludeHierarchy,
            'reportIncludeRequired'     => $reportEntry->reportIncludeRequired,
            'reportCompanies'           => [],
            'reportUnits'               => []
        ];

        if ($reportEntry->reportCompanies->total()) {
            $filter['reportCompanies'] = $reportEntry->reportCompanies->ids();
        }
        if ($reportEntry->reportUnits->total()) {
            $filter['reportUnits'] = $reportEntry->reportUnits->ids();
        }
        return $filter;
    }

    /**
     * @param $manager
     * @param $type
     * @param array $filter
     * @return mixed
     */
    public function getCustomReportData($manager, $type, $filter = []) {
        $userFilter = [];
        $resultFilter = [];
        if ($filter['reportResultType'] != 'all') {
            $resultFilter['resultType'] = $filter['reportResultType'];
        }
        // clear report units if non mandatory
        if ($filter['reportResultType'] != 'unitResult') {
            $filter['reportUnits'] = [];
        }
        if (count($filter['reportCompanies'])) {
            if ($filter['reportIncludeHierarchy']) {
                $filter['reportCompanies'] = Lantra::$app->structure->appendCompanyDescendants($filter['reportCompanies']);
            }
            $userFilter['relatedTo'] = [
                'targetElement' => $filter['reportCompanies'],
                'field' => 'userCompany'
            ];
        }
        if (count($filter['reportUnits'])) {
            $resultFilter['relatedTo'] = [
                'targetElement' => $filter['reportUnits'],
                'field' => 'resultUnit'
            ];
            $resultFilter['unitIds'] = $filter['reportUnits'];
        }
        if ($type == 'users') {
            $values = Lantra::$app->results->getManagerUserSummary($manager->id, $userFilter, $resultFilter);
        }
        elseif ($type == 'results') {
            $resultFilter['resultStatus'] = 'endorsed';
            $displayField = isset($filter['reportDisplayField']) ? $filter['reportDisplayField'] : 'expiryDate';
            $values = Lantra::$app->results->getManagerUserCompletedResults($manager->id, $userFilter, $resultFilter, $displayField);
        }
        elseif ($type == 'expired') {
            $resultFilter['status'] = 'expired';
            if (isset($filter['reportResultExpiry'])) {
                $resultFilter['status'] = ['expired', 'live'];
                $resultFilter['expiryDate'] = ':notempty';
                // expired
                if ($filter['reportResultExpiry'] == '0') {
                    $resultFilter['expiryDate'] = '<' . time();
                }
                // after 365
                elseif ($filter['reportResultExpiry'] == '365+') {
                    $resultFilter['expiryDate'] = '>' . (time() + (365*86400));
                }
                // within x days
                else {
                    $days = $filter['reportResultExpiry'];
                    if (isset($filter['reportIncludeExpired']) && $filter['reportIncludeExpired']) {
                        $resultFilter['expiryDate'] = '<' . (time() + ($days*86400));
                    }
                    else {
                        $resultFilter['expiryDate'] = 'and, >' . time() . ', <' . (time() + ($days*86400));
                    }
                }
            }
            $values = Lantra::$app->results->getManagerUnitExpiredResults($manager->id, $userFilter, $resultFilter, $filter['reportIncludeRequired']);
        }
        elseif ($type == 'required') {
            $values = Lantra::$app->results->getManagerUnitRequiredResults($manager->id, $userFilter, $resultFilter);
        }
        return $values;
    }

    /**
     * Get all reports
     *
     * @param object
     * @return null
     * @throws Mixed
     */
    public function runCustomReport(EntryModel $reportEntry)
    {
        $filter = $this->getCustomReportFilter($reportEntry);
        $values = $this->getCustomReportData($reportEntry->getAuthor(), $reportEntry->reportType, $filter);
        $total = count($values) - 1;
        if (! $total) {
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
        Craft::$app->entries->saveEntry($reportEntry);
        // send notification if applicable
        if ($reportEntry->reportSendFrequency != 'never') {
            $asset = craft()->assets->getFileById($fileId);
            $attachment = [
                'path' => $filePath . $fileName,
                'filename' => $fileName,
                'mimeType' => $asset->getMimeType()
            ];
            $emails = [];
            foreach($reportEntry->reportRecipients as $user) {
                $emails[] = $user->email;
            }
            // add on custom report emails
            if ($reportEntry->reportEmails) {
                $emails = array_merge($emails, explode(',', $reportEntry->reportEmails));
            }
            $subject = craft()->lantra_notify->getNotifySetting('subjectCustomReport', $reportEntry->title);
            $variables = ['entry' => $reportEntry];
            $template = craft()->lantra_notify->getNotifySetting('customReport', "Custom report: {{ entry.title }}.");
            $message = craft()->templates->renderString($template, $variables);
            craft()->lantra_notify->notify($emails, $subject, $message, [$attachment]);
            $reportEntry->setContentFromPost(['reportLastSentDate' => time()]);
            Craft::$app->entries->saveEntry($reportEntry);
        }
        // delete the temp file
        unlink($filePath . $fileName);
        // delete from queue
        Lantra::$app->queue->delete($reportEntry->id);
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
            $company = Lantra::$app->users->userCompany($user);
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
                $user->userStartDate ? $user->userStartDate->format('d-m-Y') : '',
                $user->userStartDate ? $user->userDateOfBirth->format('d-m-Y') : '',
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
        $criteria = Lantra::$app->results->getModuleResults($days, null, $expiring, 'complete', null, $userIds);
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
        $criteria = User::find();
        $criteria->limit = null;
        $companyTeamIds = [];
        foreach($reportEntry->reportCompanies as $company) {
            $companyTeamIds = array_merge($companyTeamIds, Lantra::$app->users->getCompanyTeamIds($company->id));
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
        $criteria = Entry::find();
        $criteria->section = 'reports';
        $criteria->limit = null;
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
    private function getAutomatedReports()
    {
        $criteria = Entry::find();
        $criteria->section = 'reports';
        $criteria->reportAutomated = 1;
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
