<?php
namespace Craft;

class LantraVariable
{
    public function canManage($user = null)
    {
        if (is_null($user)) {
            $user = craft()->userSession->getUser();
        }

        if ($user->admin or $user->isInGroup('schemeManagers') or $user->isInGroup('companyManagers') or $user->isInGroup('teamManagers')) {
            return true;
        }

        return false;
    }

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

            if ($user->isInGroup('schemeManagers')) {
                $type .= ', Scheme Manager';
            }

            if ($user->isInGroup('companyManagers')) {
                $type .= ', Company Manager';
            }

            if ($user->isInGroup('teamManagers')) {
                $type .= ', Team Manager';
            }
        }

        return $type;
    }

    public function userCompany($user = null)
    {
        if (is_null($user)) {
            $user = craft()->userSession->getUser();
        }

        if ( ! $user || ! $user->userTeam) {
            return null;
        }

        $criteria = craft()->elements->getCriteria(ElementType::Entry);
        $criteria->section = 'companies';
        $criteria->limit = 1;
        $criteria->relatedTo = $user->getContent()->userTeam;

        return $criteria->first();
    }
}
