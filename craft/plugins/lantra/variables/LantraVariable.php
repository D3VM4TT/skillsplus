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
            if ($user->isInGroup('user')) {
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
     * @param int $limit
     * @param bool $count
     * @return mixed
     * @throws Exception
     */
    public function managerResultsReport($reportType = 'recent', $userId = null,  $days = 'all', $limit = 10, $count = false) {
        if (false == $user = $this->getUser($userId)) {
            return null;
        }
        switch ($reportType) {
            case 'blocked':
                $criteria = craft()->lantra_results->getManagerBlockedUnitResults($user->id, $days, $limit);
                break;
            case 'expiring':
                $criteria = craft()->lantra_results->getManagerExpiringResults($user->id, $days, $limit);
            break;
            default :
                $criteria = craft()->lantra_results->getManagerRecentResults($user->id, $days, $limit);
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
     * @throws mixed
     * @return string
    */
    public function exportResultsReport($reportType = 'recent', $days = 28) {
        $data = [];
        if (false != $results = $this->managerResultsReport($reportType, null, $days)) {
            foreach ($results as $result) {
                $userTeam = $result->author->userTeam->first();
                $resultModule = $result->resultModule->first();
                $data[] = [
                    $result->author->getFullName(),
                    $userTeam ? $userTeam->title : '',
                    $resultModule ? $resultModule->title : '',
                    $result->postDate->format('d/m/y'),
                    $result->expiryDate ? $result->expiryDate->format('d/m/y') : ''
                ];
            }
        }
        $this->sendReport($reportType, $data);
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
