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
use craft\elements\Entry;
use craft\helpers\DateTimeHelper;

use lantra\sp\helpers\ReportHelper;
use lantra\sp\Plugin as Lantra;
use lantra\sp\helpers\LantraHelper;
use League\Csv\Writer;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;

class Reports extends Component
{

    /**
     * @param $ext
     * @param $data
     * @param $reportId
     * @param bool $download
     * @return \craft\web\Response|\yii\console\Response
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @throws \yii\base\Exception
     */
    public function createAsset($ext = 'csv', $data, $reportId, $download = false)
    {
        ## force default format
        if (empty($ext) || !in_array($ext, ['csv', 'xlsx', 'pdf'])) {
            $ext = 'csv';
        }

        ## create file in temp folder
        $tempFolder = Craft::$app->path->tempPath;
        $filename = 'report-' . $reportId .  '-' . time() . '.' . $ext;
        $tempPath = $tempFolder . $filename;

        if ($ext == 'xlsx') {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            for ($i = 0, $l = sizeof($data); $i < $l; $i++) {
                $j = 0;
                foreach ($data[$i] as $k => $v) {
                    $sheet->setCellValueByColumnAndRow($j + 1, ($i + 1), $v);
                    $j++;
                }
            }
            if ($ext == 'xlsx') {
                $mime = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
                $writer = new Xlsx($spreadsheet);
            }
            $writer->save($tempPath);
        }
        elseif ($ext == 'pdf') {
            $header = array_shift($data);
            $variables = [
                'filename'  => $filename,
                'header'    => $header,
                'rows'      => $data
            ];
            $view = Craft::$app->getView();
            $view->setTemplatesPath(Lantra::getInstance()->getBasePath());
            $html = $view->renderTemplate('/templates/reports/default', $variables);
            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'landscape');
            $dompdf->render();
            file_put_contents($tempPath, $dompdf->output());
        }
        else {
            $csv = Writer::createFromPath($tempPath, "w");
            $csv->insertAll($data);
        }

        if ($download) {
            return Craft::$app->response->sendFile($tempPath);
        }

        $response = LantraHelper::addAsset($tempPath, $filename, 'data');
        return $response['asset'];
    }

    /**
     * @param $ext
     * @param $data
     * @param $reportId
     * @return \craft\web\Response|\yii\console\Response
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @throws \yii\base\Exception
     */
    public function reportDownload($ext = 'csv', $data, $reportId)
    {
        return $this->createAsset($ext, $data, $reportId, true);
    }

    /**
     * @param string $search
     * @param int $limit
     * @param string $order
     * @param bool $automated
     * @return \craft\elements\db\ElementQueryInterface|\craft\elements\db\EntryQuery|null
     * @throws \yii\db\Exception
     */
    public function reportCriteria($search = '', $limit = 25, $order = 'title', $automated = false)
    {
        $user = Craft::$app->getUser();
        $criteria = Entry::find();
        $criteria->section = 'reports';
        $criteria->limit = $limit;
        $criteria->order = $order;
        if ($automated) {
            $criteria->reportAutomated = true;
        }
        else {
            $criteria->reportAutomated = false;
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

    /**
     * @param string $search
     * @return array
     * @throws \yii\db\Exception
     */
    private function searchReportIds($search = '')
    {
        if (intval($search)) {
            $mysql = 'SELECT c.elementId as id FROM {{%content}} c                
                WHERE c.elementId = "' . $search . '"';
        }
        else {
            $mysql = 'SELECT c.elementId as id FROM {{%content}} c               
                WHERE c.title LIKE "%' . $search . '%"';
        }

        $result = Craft::$app->db->createCommand($mysql)->query();
        $ids = [];
        foreach ($result as $row) {
            $ids [] = $row['id'];
        }
        return $ids;
    }

    /**
     * Run all reports for today
     *
     * @param $weekValue
     * @param $monthValue
     * @throws Mixed
    */
    public function sendDailyReports($weekValue = null, $monthValue = null)
    {
        $weekDay = $weekValue ? $weekValue : (int) date('N');
        $monthDay = $monthValue ? $monthValue : (int) date('j');
        $reportEntries = $this->getAutomatedReports();
        foreach ($reportEntries as $reportEntry)
        {
            ## skip if report no longer active
            if (!ReportHelper::reportTypeSetting($reportEntry->reportType,'active')) {
                continue;
            }
            $reportSendValue = (int) $reportEntry->reportSendValue;
            $reportSendFrequency = $reportEntry->reportSendFrequency->value;
            if (($reportSendFrequency == 'weekly' && $reportSendValue == $weekDay) || ($reportSendFrequency == 'monthly' && $reportSendValue == $monthDay)) {
                Lantra::$app->queue->add($reportEntry->id, 9);
            }
        }
    }

    /**
     * @param Entry $reportEntry
     * @param null $limit
     * @param string $search
     * @param bool $count
     * @return ElementCriteriaModel|null
     */
    public function reportDataCriteria(Entry $reportEntry, $limit = null, $search = '', $count = false)
    {
        $filter = $this->getReportFilter($reportEntry);
        $userFilter = $this->_parseUserFilter($filter);
        $resultFilter = $this->_parseResultFilter($filter);

        $userId = $reportEntry->authorId;

        $criteria = null;
        switch ($reportEntry->reportType) {
            case 'standardUsers':
            case 'standardAnnualResults':
                $criteria = Lantra::$app->users->getManagerUsers($userId, $limit, $userFilter['search'], $userFilter['relatedTo'], $userFilter['lastLoginDate']);
                break;
            case 'standardResults':
                $days = $reportEntry->reportResultExpiry->value == '0' ? 'all' : $reportEntry->reportResultExpiry->value;
                if ($reportEntry->reportResultStandardType == 'endorsed') {
                    $criteria = Lantra::$app->results->getManagerUnitEndorsedResults($userId, $days, $limit, $search);
                }
                else {
                    $criteria = Lantra::$app->results->getManagerUnitExpiringResults($userId, $days, $limit, $search);
                }
                break;
            case 'standardCpd':
                $criteria = Lantra::$app->results->getManagerModuleCpdResults($userId, 'all', $limit, $resultFilter['search'], $resultFilter['relatedTo']);
                break;
            case 'standardPayments':
                $criteria = Lantra::$app->users->getUserPayments();
                break;
        }
        if ($criteria) {
            return ($count) ? $criteria->count() : $criteria;
        }
        return null;
    }

    /**
     * @param $reportType
     * @param $data
     * @throws HttpException
     */
    public function downloadReport($reportType, $data)
    {
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
     * @return Entry|null
     * @throws \Throwable
     * @throws \craft\errors\ElementNotFoundException
     * @throws \yii\base\Exception
     */
    public function saveCustomReport($author, $title = '', $fields = [], $entryId = null)
    {
        if ($entryId) {
            $reportEntry = Craft::$app->entries->getEntryById($entryId);
        }
        else {
            $reportEntry = new Entry();
            $reportEntry->sectionId = LantraHelper::sectionId('reports');
            $reportEntry->typeId = LantraHelper::entryTypeId('reports');
            $reportEntry->enabled = true;
            $reportEntry->authorId = $author->id;
        }
        $reportEntry->title = $title;
        unset($fields['reportTitle']);
        $reportEntry->setFieldValues($fields);
        Craft::$app->elements->saveElement($reportEntry);
        return $reportEntry;
    }

    /**
     * @param $reportEntry
     * @return array
     */
    public function getReportFilter($reportEntry)
    {
        $filter = [
            'reportDays'                => $reportEntry->reportDays,
            'reportResultType'          => $reportEntry->reportResultType->value,
            'reportDisplayField'        => $reportEntry->reportDisplayField->value,
            'reportResultExpiry'        => $reportEntry->reportResultExpiry->value,
            'reportIncludeHierarchy'    => $reportEntry->reportIncludeHierarchy,
            'reportIncludeExpired'      => $reportEntry->reportIncludeExpired,
            'reportIncludeRequired'     => $reportEntry->reportIncludeRequired,
            'reportCompanies'           => [],
            'reportUnits'               => [],
            'reportModules'             => []
        ];

        if ($reportEntry->reportCompanies->count()) {
            $filter['reportCompanies'] = $reportEntry->reportCompanies->ids();
        }
        if ($reportEntry->reportUnits->count()) {
            $filter['reportUnits'] = $reportEntry->reportUnits->ids();
        }
        if ($reportEntry->reportModules->count()) {
            $filter['reportModules'] = $reportEntry->reportModules->ids();
        }
        return $filter;
    }

    /**
     * @param $manager
     * @param $type
     * @param array $filter
     * @return array
     * @throws \yii\db\Exception
     */
    public function getCustomReportData($manager, $type, $filter = [])
    {
        $userFilter = $this->_parseUserFilter($filter);
        $resultFilter = $this->_parseResultFilter($filter);
        
        $values = [];

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
                $resultFilter['status'] = ['live', 'expired'];
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
     * @param $filter
     * @return array
     */
    private function _parseUserFilter($filter)
    {
        $userFilter = [
            'limit'         => null,
            'search'        => '',
            'relatedTo'     => [],
            'lastLoginDate' => null
        ];

        if (count($filter['reportCompanies'])) {
            if ($filter['reportIncludeHierarchy']) {
                $filter['reportCompanies'] = Lantra::$app->structure->appendCompanyDescendants($filter['reportCompanies']);
            }
            $userFilter['relatedTo'] = [
                'targetElement' => $filter['reportCompanies'],
                'field' => 'userCompany'
            ];
        }

        if ($filter['reportDays']) {
            if ($filter['reportDays'] == 9999) {
                $userFilter['lastLoginDate'] = ':empty:';
            }
            else {
                $date = new \DateTime();
                $userFilter['lastLoginDate'] = '> ' . $date->modify('-' . $filter['reportDays'] . ' days')->getTimestamp();
            }
        }

        return $userFilter;
    }

    /**
     * @param $filter
     * @return array
     */
    private function _parseResultFilter($filter)
    {
        $resultFilter = [
            'limit'         => null,
            'search'        => '',
            'relatedTo'     => null,
            'resultType'    => null,
            'unitIds'       => null
        ];
        if ($filter['reportResultType'] != 'all') {
            $resultFilter['resultType'] = $filter['reportResultType'];
        }
        ## clear report units if non mandatory
        if ($filter['reportResultType'] != 'unitResult') {
            $filter['reportUnits'] = [];
        }
        if (count($filter['reportUnits'])) {
            $resultFilter['relatedTo'] = [
                'targetElement' => $filter['reportUnits'],
                'field' => 'resultUnit'
            ];
            $resultFilter['unitIds'] = $filter['reportUnits'];
        }
        if (count($filter['reportModules'])) {
            $resultFilter['relatedTo'] = [
                'targetElement' => $filter['reportModules'],
                'field' => 'resultModule'
            ];
        }
        return $resultFilter;
    }

    /**
     * Get all reports
     *
     * @param object
     * @return null
     * @throws Mixed
     */
    public function runCustomReport(Entry $reportEntry)
    {
        $response = [
            'success'   => false,
            'total'     => 0,
            'message'   => ''
        ];
        $filter = $this->getReportFilter($reportEntry);
        $values = $this->getCustomReportData($reportEntry->getAuthor(), $reportEntry->reportType, $filter);
        $response['total'] = count($values) - 1;
        if (!$response['total']) {
            $response['message'] = $reportEntry->title . ' returns no data.';
            Lantra::$app->queue->delete($reportEntry->id);
            return $response;
        }
        ## create report data asset
        if (null == $asset = $this->createAsset($reportEntry->reportFormat, $values, $reportEntry->id)) {
            Lantra::$app->queue->delete($reportEntry->id);
            $response['message'] = 'Could not create report asset';
            return $response;
        }
        ## append asset to report entry
        $reportData = array_merge($reportEntry->reportData->ids(), [$asset->id]);
        $reportEntry->setFieldValue('reportData', $reportData);
        Craft::$app->elements->saveElement($reportEntry);
        ## send notification if applicable
        if ($reportEntry->reportSendFrequency != 'never' && Lantra::$app->settings->getSetting('notifyEnableCustomReport')) {
            $attachment = [
                'path' => LantraHelper::assetPath($asset),
                'filename' => $asset->fileName,
                'mimeType' => $asset->mimeType
            ];
            $emails = [];
            foreach($reportEntry->reportRecipients as $user) {
                $emails[] = $user->email;
            }
            ## add on custom report emails
            if ($reportEntry->reportEmails) {
                $emails = array_merge($emails, explode(',', $reportEntry->reportEmails));
            }
            $subject = Lantra::$app->notify->getNotifySetting('subjectCustomReport', $reportEntry->title);
            $variables = ['entry' => $reportEntry];
            $template = Lantra::$app->notify->getNotifySetting('customReport', "Custom report: {{ entry.title }}.");
            $message = Craft::$app->view->renderString($template, $variables);
            Lantra::$app->notify->notify($emails, $subject, $message, [$attachment]);
            $reportEntry->setFieldValue('reportLastSentDate', DateTimeHelper::currentUTCDateTime());
            Craft::$app->elements->saveElement($reportEntry);
        }
        ## delete from queue (if it came from the queue)
        Lantra::$app->queue->success($reportEntry->id);
        $response['success'] = true;
        return $response;
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
        return $count ? $data->count() : $this->formatReportValues($data, $reportEntry->type);
    }

    /**
     * @param $manager
     * @return array|\craft\base\ElementInterface[]|Entry[]
     */
    public function getManagerReports($manager)
    {
        $criteria = Entry::find();
        $criteria->section = 'reports';
        $criteria->order = 'title';
        $criteria->relatedTo = [
            'targetElement' => $manager,
            'field' => 'reportRecipients'
        ];
        return $criteria->all();
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
            ## user fields go in all reports
            $user = ($type == 'result') ? $row->author : $row;
            $company = Lantra::$app->users->userCompany($user);
            $roles = [];
            foreach ($user->userRole as $role) {
                $roles[] = $role->title;
            }
            $team = $user->userTeam->count() ? $user->userTeam->one()->title : '~';
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
            ## add the result fields
            if ($type == 'result') {
                $record = array_merge($record, [$row->resultModule->one()->title, $row->expiryDate->timestamp()]);
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
        return $criteria->all();
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
        return $criteria->all();
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
        return $criteria->all();
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
