<?php
namespace Craft;

class Lantra_UsersService extends BaseApplicationComponent
{
    /**
     * Check whether this user can manage teams or companies
     *
     * @param null $user
     * @return bool
     */
    function canManage($user = null)
    {
        if (is_null($user)) {
            $user = craft()->userSession->getUser();
        }

        if ($user->admin or $user->isInGroup('companyManagers') or $user->isInGroup('teamManagers')) {
            return true;
        }

        return false;
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
        if ( ! $user) {
            return null;
        }
        $company = $user->userTeam->first()->teamCompany;
        return $company ? $company->first() : null;
    }

    /**
     * Return all company ids (recursive)
     *
     * @param $entryId
     * @return array
     * @throws Exception
     */
    function getCompanyChildrenIds($entryId)
    {
        $return = [];
        $criteria = craft()->elements->getCriteria(ElementType::Entry);
        $criteria->section = 'companies';
        $criteria->order = 'title';
        $criteria->relatedTo = array(
            'targetElement' => $entryId,
            'field' => 'companyParent'
        );

        $ids = $criteria->ids();

        if (count($ids)) {
            foreach($ids as $id) {
                $return[] = $id;
                // add children companies recursively
                $childrenIds = $this->getCompanyChildrenIds($id);
                if (count($childrenIds)) {
                    $return = array_merge($return, $childrenIds);
                }
            }
        }
        return $return;
    }

    /**
     * Returns all team ids for a company
     *
     * @param $companyId
     * @return array
     * @throws Exception
     */
    function getCompanyTeamIds($companyId)
    {
        $criteria = craft()->elements->getCriteria(ElementType::Entry);
        $criteria->section = 'teams';
        $criteria->relatedTo = array(
            'targetElement' => $companyId,
            'field' => 'teamCompany'
        );
        return $criteria->ids();
    }

    /**
     * Return company ids where user is a company manager
     *
     * @param $user
     * @return array
     * @throws Exception
     */
    function getCompanyManagerCompanyIds(UserModel $user)
    {
        if (is_null($user)) {
            $user = craft()->userSession->getUser();
        }
        $criteria = craft()->elements->getCriteria(ElementType::Entry);
        $criteria->section = 'companies';
        $criteria->relatedTo = array(
            'targetElement' => $user->id,
            'field' => 'companyManager'
        );
        $criteria->order = 'title';
        return $criteria->ids();
    }

    /**
     * Returns team ids where user is primary or secondary manager
     *
     * @param $user
     * @return array
     * @throws Exception
     */
    function getTeamManagerTeamIds(UserModel $user)
    {
        if (is_null($user)) {
            $user = craft()->userSession->getUser();
        }
        $criteria = craft()->elements->getCriteria(ElementType::Entry);
        $criteria->section = 'teams';
        $criteria->relatedTo = array(
            'or',
            ['targetElement' => $user, 'field' => 'teamPrimaryManager'],
            ['targetElement' => $user, 'field' => 'teamSecondaryManagers']
        );
        return $criteria->ids();
    }

    /**
     * Return all team ids for a manager
     *
     * @param UserModel $user
     * @param bool $includeCompanyTeams
     * @return array
     * @throws Exception
     */
    function getManagerTeamIds(UserModel $user, $includeCompanyTeams = false)
    {
        if (is_null($user)) {
            $user = craft()->userSession->getUser();
        }
        $return = [];
        // add the direct teamPrimaryManager and teamSecondaryManager teams
        if (FALSE != $teamManagerTeamIds = $this->getTeamManagerTeamIds($user)) {
            $return = array_merge($return, $teamManagerTeamIds);
        }
        // add the company teams
        if ($includeCompanyTeams && $user->isInGroup('companyManagers')) {
            $companyIds = $this->getCompanyManagerCompanyIds($user);
            foreach ($companyIds as $companyId) {
                if (FALSE != $companyManagerTeamIds = $this->getCompanyTeamIds($companyId)) {
                    $return = array_merge($return, $companyManagerTeamIds);
                }
            }
        }

        return $return;
    }

    /**
     * Returns all user ids that belong to teams (or companies) managed by a manager
     *
     * @param $user
     * @param $includeHierarchy
     * @return array
     * @throws Exception
     */
    function getManagerSubordinateIds(UserModel $user, $includeHierarchy = true)
    {
        if (is_null($user)) {
            $user = craft()->userSession->getUser();
        }
        $return = [];
        if ( ! $this->canManage($user)) {
            return $return;
        }
        $teamIds = $this->getManagerTeamIds($user, $includeHierarchy);
        // get all users who belong to any of the manager's teams
        $criteria = craft()->elements->getCriteria(ElementType::User);
        $criteria->relatedTo = array(
            'targetElement' => $teamIds,
            'field' => 'userTeam'
        );

        return $criteria->ids();
    }

    /**
     * Returns all managers for a user
     *
     * @param $user
     * @param $includeHierarchy
     * @return array
     * @throws Exception
     */
    function getTeamMangers(UserModel $user)
    {
        $return = [];
        $team = $user->userTeam->first();
        if ( ! $team) {
            return $return;
        }
        $return[] = $team->teamPrimaryManager->first();
        foreach ($team->teamSecondaryManagers as $manager) {
            $return[] = $manager;
        }
        return $return;
    }
}
