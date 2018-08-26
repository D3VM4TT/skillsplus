<?php
namespace Craft;

class LantraVariable
{
    /**
     * Check whether this user can manage teams or companies
     *
     * @param null $userId
     * @param bool $scheme
     * @return bool
     */
    public function canManage($userId = null, $scheme = false) {
        if (false == $user = $this->getUser($userId)) {
            return false;
        }
        return craft()->lantra_users->canManage($user, $scheme);
    }

    /**
     * Check whether this user manages the subordinate
     *
     * @param null $subordinateId
     * @param bool $managerId
     * @return bool
     */
    public function isManager($subordinateId = null, $managerId = null) {
        $manager = (is_null($managerId)) ? null : $this->getUser($managerId);
        return craft()->lantra_users->isManager($subordinateId, $manager);
    }

    /**
     * Can add user
     *
     * @param null $userId
     * @return bool
     */
    public function canAddUser($userId = null) {
        if (false == $user = $this->getUser($userId)) {
            return false;
        }
        return craft()->lantra_users->canAddUser($user);
    }

    /**
     * Display list of user types
     *
     * @param null $userId
     * @return string
     */
    public function userType($userId = null) {
        if (false == $user = $this->getUser($userId)) {
            return '';
        }
        if ($user->admin) {
            $type = 'Lantra Admin';
        }
        else {
            $type = '';
            if ($user->isInGroup('users')) {
                $type .= 'User';
            }
            if ($user->isInGroup('schemeManagers')) {
                $type .= ($type ? ', ': '') . 'Scheme Manager';
            }
            if ($user->isInGroup('companyManagers')) {
                $type .= ($type ? ', ': '') . 'Company Manager';
            }
            if ($user->isInGroup('teamManagers')) {
                $type .= ($type ? ', ': '') . 'Team Manager';
            }
        }
        return $type;
    }

    /**
     * Return total number of company licences
     *
     * @return int
     * @throws Exception
     */
    public function totalCompanyLicences() {
        $result = craft()->db->createCommand()
            ->from('{{content}}')
            ->select("SUM(field_companyRemainingLicences) as total")
            ->queryRow();
        return $result['total'];
    }

    /**
     * Return user company (team company)
     *
     * @param null $userId
     * @return BaseElementModel|null
     * @throws Exception
     */
    public function userCompany($userId = null) {
        if (false == $user = $this->getUser($userId)) {
            return null;
        }
        return craft()->lantra_users->userCompany($user);
    }

    /**
     * Return user managers
     *
     * @param null $userId
     * @param int $level
     * @return BaseElementModel|null
     * @throws Mixed
     */
    public function userManagers($userId = null, $level = 0) {
        if (false == $user = $this->getUser($userId)) {
            return null;
        }
        if ($level > 0) {
            return craft()->lantra_users->getUserManagerByLevel($user, $level);
        }
        return craft()->lantra_users->getUserMangers($user, true);
    }

    /**
     * Return company users
     *
     * @param null $companyId
     * @return BaseElementModel|null
     * @throws Exception
     */
    public function companyUsers($companyId = null) {
        return craft()->lantra_users->getCompanyUsers($companyId);
    }

    /**
     * Display remaining attempts
     *
     * @param EntryModel $unitEntry
     * @param EntryModel $resultEntry
     * @param null $userId
     * @return int|string
     */
    public function remainingAttempts(EntryModel $unitEntry, $resultEntry = null, $userId = null) {
        if (false == $user = $this->getUser($userId)) {
            return 0;
        }
        if (is_null($resultEntry)) {
            $resultEntry = craft()->lantra_results->getUnitResult($user->id, $unitEntry->id);
        }
        return craft()->lantra_attempts->remainingAttempts($unitEntry, $resultEntry, $user->id);
    }

    /**
     * Get manager companies
     *
     * @param null $userId
     * @param bool $includeChildren
     * @return BaseElementModel|null
     * @throws Exception
     */
    public function managerCompanies($userId = null, $includeChildren = false) {
        if (false == $user = $this->getUser($userId)) {
            return null;
        }
        return craft()->lantra_users->getManagerCompanies($user, $includeChildren);
    }

    /**
     * Return manager teams
     *
     * @param null $userId
     * @param bool $includeCompanyTeams
     * @return BaseElementModel|null
     * @throws Exception
     */
    public function managerTeams($userId = null, $includeCompanyTeams = false) {
        if (false == $user = $this->getUser($userId)) {
            return null;
        }
        return craft()->lantra_users->getManagerTeams($user, $includeCompanyTeams);
    }

    /**
     * Return available teams (company licences available)
     *
     * @param null $userId
     * @return array|null
     * @throws Exception
     */
    public function availableTeams($userId = null) {
        if (false == $user = $this->getUser($userId)) {
            return null;
        }
        return craft()->lantra_users->getAvailableTeams($user);
    }

    /**
     * Return all subordinate users for a manager
     *
     * @param null $userId
     * @param bool $includeHierarchy
     * @return mixed
     * @throws Exception
     */
    public function managerSubordinates($userId = null, $includeHierarchy = false) {
        if (false == $user = $this->getUser($userId)) {
            return null;
        }
        return craft()->lantra_users->getManagerSubordinates($user, $includeHierarchy);
    }

    /**
     * Return all result entries requiring endorsement for a manager
     *
     * @param null $userId
     * @param bool $limit
     * @param bool $count
     * @return mixed
     * @throws Exception
     */
    public function managerEndorsementResults($userId = null, $limit = 10, $count = false) {
        if (false == $user = $this->getUser($userId)) {
            return null;
        }
        return craft()->lantra_results->getManagerEndorsementResults($user, ($count == false ? $limit : null), $count);
    }

    /**
     * Return a manager report
     *
     * @param string $reportType
     * @param null $userId
     * @param mixed $days
     * @param mixed $search
     * @param int $limit
     * @param bool $count
     * @return mixed
     * @throws Exception
     */
    public function managerReport($reportType = 'users', $userId = null,  $days = 'all', $search = '', $limit = 10, $count = false) {
        if (false == $user = $this->getUser($userId)) {
            return null;
        }
        $criteria = null;
        switch ($reportType) {
            case 'units-blocked':
                $criteria = craft()->lantra_results->getManagerUnitBlockedResults($user->id, $days, $limit, $search);
            break;
            case 'units-expiring':
                $criteria = craft()->lantra_results->getManagerUnitExpiringResults($user->id, $days, $limit, $search);
            break;
            case 'units-endorsed':
                $criteria = craft()->lantra_results->getManagerUnitEndorsedResults($user->id, $days, $limit, $search);
                break;
            case 'modules-active':
                $criteria = craft()->lantra_results->getManagerModuleActiveResults($user->id, $days, $limit, $search);
            break;
            case 'modules-expiring':
                $criteria = craft()->lantra_results->getManagerModuleExpiringResults($user->id, $days, $limit, $search);
            break;
            case 'modules-completed':
                $criteria = craft()->lantra_results->getManagerModuleCompletedResults($user->id, $days, $limit, $search);
            break;
            case 'users':
                $criteria = craft()->lantra_users->getManagerUsers($user->id, $limit, $search);
            break;
        }
        if ($criteria) {
            return ($count) ? $criteria->count() : $criteria;
        }
        return null;
    }

    /**
     * Export a results report
     *
     * @param string $reportType
     * @param int $days
     * @param string $search
     * @throws mixed
     * @return string
    */
    public function exportReport($reportType = 'users', $days = 28, $search = '') {
        $data = [];
        if (false != $results = $this->managerReport($reportType, null, $days, $search, false)) {
            foreach ($results as $row) {
                if ($reportType == 'users') {
                    $data[] = [
                        $row->getFullName(),
                        $row->email,
                        $row->userTeam->first(),
                    ];
                }
                else {
                    $userTeam = $row->author->userTeam->first();
                    $data[] = [
                        $row->author->getFullName(),
                        $userTeam ? $userTeam->title : '',
                        $row->resultModule->first()->title,
                        $row->postDate->format('d/m/y'),
                        $row->expiryDate ? $row->expiryDate->format('d/m/y') : '',
                    ];
                }
            }
        }
        $this->sendReport($reportType, $data);
    }

    /**
     * Get the individual team id
     *
     * @return int
     */
    public function individualTeamId() {
        $team = craft()->lantra_users->getIndividualTeam();
        return ($team) ? $team->id : null;
    }

    /**
     * Get the individual company id
     *
     * @return int
     */
    public function individualCompanyId() {
        $company = craft()->lantra_users->getIndividualCompany();
        return ($company) ? $company->id : null;
    }

    /**
     * Get report data
     *
     * @param $entryId
     * @return mixed
     */
    public function getReportData($entryId) {
        $reportEntry = craft()->entries->getEntryById($entryId);
        return ($reportEntry) ? craft()->lantra_reports->getReportData($reportEntry) : null;
    }

    /**
     * Send the csv report to the browser
     *
     * @param $reportType
     * @param $data
     * @throws HttpException
     */
    private function sendReport($reportType, $data) {
        ob_start();
        $export = fopen('php://output', 'w');
        foreach ($data as $row) {
            fputcsv($export, $row);
        }
        fclose($export);
        $content = ob_get_clean();
        $content = str_replace("\n", "\r\n", $content);
        craft()->request->sendFile('report-' . $reportType . '.csv', $content, array('forceDownload' => true, 'mimeType' => 'text/csv'));
    }

    /**
     * Get the user
     *
     * @param null $userId
     * @return UserModel
     */
    private function getUser($userId = null) {
        if(is_object($userId)) {
            return $userId;
        }
        elseif (is_null($userId)) {
            return $user = craft()->userSession->getUser();
        }
        else {
            return $user = craft()->users->getUserById($userId);
        }
    }
}
