<?php
namespace Craft;

class LantraVariable
{
    /**
     * Check whether this user can manage teams or companies
     *
     * @param null $userId
     * @return bool
     */
    public function canManage($userId = null) {
        if (false == $user = $this->getUser($userId)) {
            return false;
        }
        return craft()->lantra_users->canManage($user);
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
        $subordinateIds = craft()->lantra_users->getManagerSubordinateIds($user, $includeHierarchy);
        if ( ! count($subordinateIds)) {
            return null;
        }
        $criteria = craft()->elements->getCriteria(ElementType::User);
        $criteria->limit = null;
        $criteria->id = $subordinateIds;
        $criteria->order = 'lastName asc';
        return $criteria->find();
    }

    /**
     * Return all result entries requiring endorsement for a manager
     *
     * @param null $userId
     * @param bool $count
     * @return mixed
     * @throws Exception
     */
    public function managerEndorsementEntries($userId = null, $count = false) {
        if (false == $user = $this->getUser($userId)) {
            return null;
        }
        $subordinateIds = craft()->lantra_users->getManagerSubordinateIds($user, true);
        if ( ! count($subordinateIds)) {
            return null;
        }
        $criteria = craft()->elements->getCriteria(ElementType::Entry);
        $criteria->section = 'results';
        $criteria->type = 'unitResult';
        $criteria->resultEvidence = ':notempty:';
        $criteria->limit = null;
        $criteria->resultStatus = 'pending';
        $criteria->authorId = $subordinateIds;
        $criteria->order = 'postDate desc';
        return ($count) ? $criteria->count() : $criteria->find();
    }

    /**
     * Return all expiring module result entries
     *
     * @param null $userId
     * @param mixed $days
     * @param int $limit
     * @param bool $count
     * @return mixed
     * @throws Exception
     */
    public function managerExpiringResults($userId = null, $days = 'all', $limit = 10, $count = false) {
        if (false == $user = $this->getUser($userId)) {
            return null;
        }
        $criteria = craft()->lantra_results->getManagerExpiringResults($user->id, $days, $limit);
        if ( ! $criteria) {
            return null;
        }
        return ($count) ? $criteria->count() : $criteria;
    }

    /**
     * Return all recent module result entries
     *
     * @param null $userId
     * @param mixed $days
     * @param int $limit
     * @param bool $count
     * @return mixed
     * @throws Exception
     */
    public function managerRecentResults($userId = null,  $days = 'all', $limit = 10, $count = false) {
        if (false == $user = $this->getUser($userId)) {
            return null;
        }
        $criteria = craft()->lantra_results->getManagerRecentResults($user->id, $days, $limit);
        if ( ! $criteria) {
            return null;
        }
        return ($count) ? $criteria->count() : $criteria;
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
