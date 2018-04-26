<?php
namespace Craft;

class LantraVariable
{
    /**
     * Check whether this user can manage teams or companies
     *
     * @param null $user
     * @return bool
     */
    public function canManage($user = null)
    {
        return craft()->lantra_users->canManage($user);
    }

    /**
     * Display list of user types
     *
     * @param null $user
     * @return string
     */
    public function userType($user = null)
    {
        if (is_null($user)) {
            $user = craft()->userSession->getUser();
        }

        if ($user->admin) {
            $type = 'Admin';
        }
        else {
            $type = 'User';

            if ($user->isInGroup('companyManagers')) {
                $type .= ', Company Manager';
            }

            if ($user->isInGroup('teamManagers')) {
                $type .= ', Team Manager';
            }
        }
        return $type;
    }

    /**
     * Return user company (team company)
     *
     * @param null $user
     * @return BaseElementModel|null
     * @throws Exception
     */
    public function userCompany($user = null)
    {
        if (is_null($user)) {
            $user = craft()->userSession->getUser();
        }
        if ( ! $user || ! $user->getContent()->userTeam) {
            return null;
        }
        $criteria = craft()->elements->getCriteria(ElementType::Entry);
        $criteria->section = 'companies';
        $criteria->limit = 1;
        $criteria->relatedTo = $user->getContent()->userTeam;
        return $criteria->first();
    }

    /**
     * @param null $userId
     * @param bool $includeChildren
     * @return BaseElementModel|null
     * @throws Exception
     */
    public function managerCompanies($userId = null, $includeChildren = FALSE)
    {
        if ( ! is_null($userId)) {
            $user = craft()->users->getUserById($userId);
        }
        else {
            $user = craft()->userSession->getUser();
        }
        $companyIds = craft()->lantra_users->getCompanyManagerCompanyIds($user, $includeChildren);
        if ( ! count($companyIds)) {
            return null;
        }
        $criteria = craft()->elements->getCriteria(ElementType::Entry);
        $criteria->section = 'companies';
        $criteria->limit = null;
        $criteria->id = $companyIds;
        $criteria->fixedOrder = true;
        return $criteria->find();
    }

    /**
     * Return managaer
     *
     * @param null $userId
     * @param
     * @return BaseElementModel|null
     * @throws Exception
     */
    public function managerTeams($userId = null, $includeHierarchy = FALSE)
    {
        if ( ! is_null($userId)) {
            $user = craft()->users->getUserById($userId);
        }
        else {
            $user = craft()->userSession->getUser();
        }
        $teamIds = craft()->lantra_users->getManagerTeamIds($user, $includeHierarchy);
        if ( ! count($teamIds)) {
            return null;
        }
        $criteria = craft()->elements->getCriteria(ElementType::Entry);
        $criteria->section = 'teams';
        $criteria->limit = null;
        $criteria->id = $teamIds;
        $criteria->fixedOrder = true;
        return $criteria->find();
    }

    /**
     * Return all result entries requiring endorsement for a manager
     *
     * @param null $userId
     * @return mixed
     * @throws Exception
     */
    public function managerEndorsementEntries($userId = null) {

        if ( ! is_null($userId)) {
            $user = craft()->users->getUserById($userId);
        }
        else {
            $user = craft()->userSession->getUser();
        }
        if ( ! $user) {
            return null;
        }
        $subordinateIds = craft()->lantra_users->getManagerSubordinateIds($user);
        if ( ! count($subordinateIds)) {
            return null;
        }
        $criteria = craft()->elements->getCriteria(ElementType::Entry);
        $criteria->section = 'results';
        $criteria->type = 'unitResult';
        $criteria->resultStatus = 'pending';
        $criteria->authorId = $subordinateIds;

        return $criteria->find();
    }
}
