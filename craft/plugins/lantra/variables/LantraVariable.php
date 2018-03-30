<?php
namespace Craft;

class LantraVariable
{
    public function canManage()
    {
        $user = craft()->userSession->getUser();

        if ($user->admin or $user->isInGroup('schemeManagers') or $user->isInGroup('companyManagers') or $user->isInGroup('teamManagers')) {
            return true;
        }

        return false;
    }
}
