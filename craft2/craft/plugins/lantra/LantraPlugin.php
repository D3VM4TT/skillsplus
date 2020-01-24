<?php

namespace Craft;

/**
 * Class LantraPlugin
 * @package Craft
 */
class LantraPlugin extends BasePlugin
{
    private $sectionIdAttempts = 12;
    private $sectionIdResults = 10;
    private $sectionIdCompanies = 3;
    private $sectionIdUnits = 7;

    /*
     * Settings version (auto migrate settings)
     */
    private $settingsVersion = 5;

    function getName()
    {
        return Craft::t('Lantra');
    }

    function getVersion()
    {
        return '0.0.2';
    }

    function getDeveloper()
    {
        return 'Traffic Marketing';
    }

    function getDeveloperUrl()
    {
        return 'http://thisistraffic.co';
    }

    public function init()
    {
        craft()->config->maxPowerCaptain();
        
        parent::init();

        Craft::import('plugins.lantra.helpers.LantraHelper');

        craft()->lantra_settings->updateSettings($this->settingsVersion);

        // create user result cache
        craft()->on('users.onSaveUser', function(Event $event) {
            $user = $event->params['user'];
            craft()->lantra_results->saveUserResultCache($user->id);
        });

        // check user licence
        craft()->on('users.onBeforeSaveUser', function(Event $event) {
            $user = $event->params['user'];
            // automatically set userType for reports
            $user->setContentFromPost(['userType' => craft()->lantra_users->canManage($user) ? 'manager' : 'member']);
            $licenceSource = 'None';
            $lantraLicences = ! $this->getSettings()->lantraDisableLicences;
            if ($lantraLicences && $event->params['isNewUser'] && ! $user->admin) {
                // assign company licence if joining a team
                if ($user->userCompany->count() OR $user->userTeam->count()) {
                    $companyEntry = craft()->lantra_users->userCompany($user);
                    if (false == craft()->lantra_licence->assignCompanyLicence($user, $companyEntry)) {
                        $event->performAction = false;
                        $user->addError('userCompany', 'There are insufficient company licences.');
                    }
                    else {
                        $licenceSource = 'Company #' . $companyEntry->id;
                    }
                }
                // assign scheme licence
                elseif (false == craft()->lantra_licence->assignSchemeLicence()) {
                    $event->performAction = false;
                    $user->addError('userCompany', 'There are insufficient scheme licences.');
                }
                else {
                    $licenceSource = 'Scheme';
                }
            }
            $user->setContentFromPost(['userLicenceSource' => $licenceSource]);
        });

        // Stop deletes
        craft()->on('elements.onBeforePerformAction', function(Event $event) {
            $action = $event->params['action']->classHandle;
            if ($action == 'Delete' && ! craft()->request->isCpRequest()){
                $event->performAction = false;
            }
        });

        // Stop deletes on front end (apart from SM and admin)
        craft()->on('users.onBeforeDeleteUser', function(Event $event) {
            $user = craft()->userSession->getUser();
            if (!craft()->request->isCpRequest() && !$user->isInGroup('schemeManagers') && !$user->admin){
                $event->performAction = false;
            }
        });

        craft()->on('entries.onBeforeSaveEntry', function(Event $event) {
            $entry = $event->params['entry'];
            // Saving user/unit results
            if ($entry->sectionId == $this->sectionIdResults && ($entry->type == 'unitResult' || $entry->type == 'userResult')) {
                // set custom author
                $authorId = craft()->request->getPost('authorId');
                if ($entry->type == 'userResult' && $authorId) {
                    $entry->authorId = $authorId;
                }
                $fields = craft()->request->getPost('fields');
                $resultUnitId = isset($fields['resultUnit']) && $fields['resultUnit'] ? $fields['resultUnit'] : null;
                // set result title
                if ($entry->type == 'userResult' && $resultUnitId) {
                    $unitEntry = craft()->entries->getEntryById($resultUnitId);
                    if ($unitEntry) {
                        $entry->getContent()->title = $unitEntry->title;
                    }
                }
                // check endorsed change
                $oldEntry = craft()->entries->getEntryById($entry->id);
                $currentUser = craft()->userSession->getUser();
                if (!craft()->request->isCpRequest() && $entry->resultStatus == 'endorsed') {
                    if ($entry->authorId != $currentUser->id && craft()->lantra_users->isManager($entry->authorId)) {
                        $entry->setContentFromPost(['resultStatus' => 'endorsed']);
                        if (!$oldEntry or !$oldEntry->resultEndorsedDate) {
                            $entry->setContentFromPost(['resultEndorsedDate' => DateTimeHelper::currentTimeForDb()]);
                            $entry->setContentFromPost(['resultEndorsedUser' => [$currentUser->id]]);
                        }
                    }
                    else {
                        $entry->setContentFromPost(['resultStatus' => $oldEntry ? $oldEntry->resultStatus : 'pending']);
                    }
                }
                // force clear endorsed date if pending
                if ($entry->resultStatus != 'endorsed') {
                    $entry->setContentFromPost(['resultEndorsedDate' => '']);
                    $entry->setContentFromPost(['resultEndorsedUser' => []]);
                }
                // check change from draft to pending
                if ($oldEntry && $oldEntry->resultStatus == 'draft' && $entry->resultStatus == 'pending') {
                    // send notification
                    if (craft()->lantra_results->notifyManagerEndorsementResult($entry)) {
                        craft()->lantra_notify->sendManagerEndorsementResult($entry);
                    }
                }

                $dateFormat = 'Y-m-d H:i:s';
                // set a user start date
                $userStartDate = craft()->request->getPost('userStartDate');
                if ($userStartDate && false != $date = DateTime::createFromFormat($dateFormat, $userStartDate)) {
                    $userStartDate = $date->getTimestamp();
                }
                $entry->setContentFromPost(['resultStartDate' => $userStartDate]);
                // set a user finish date
                $userFinishDate = craft()->request->getPost('userFinishDate');
                if ($userFinishDate && false != $date = DateTime::createFromFormat($dateFormat, $userFinishDate)) {
                    $userFinishDate = $date->getTimestamp();
                }
                $entry->setContentFromPost(['resultFinishDate' => $userFinishDate]);
                // validate dates
                $userExpiryDate = craft()->request->getPost('userExpiryDate');
                if ($userExpiryDate && false != $date = DateTime::createFromFormat($dateFormat, $userExpiryDate)) {
                    $userExpiryDate = $date->getTimestamp();
                    $entry->expiryDate = $date->getTimestamp();
                }
                if ($userStartDate && $userFinishDate && $userStartDate > $userFinishDate) {
                    $entry->addError('resultStartDate', 'Start date cannot be later than finish date.');
                    $event->performAction = false;
                }
                if ($userStartDate && $userExpiryDate && $userStartDate > $userExpiryDate) {
                    $entry->addError('resultStartDate', 'Start date cannot be later than expiry date.');
                    $event->performAction = false;
                }
                if ($userFinishDate && $userExpiryDate && $userFinishDate > $userExpiryDate) {
                    $entry->addError('resultFinishDate', 'Finish date cannot be later than expiry date.');
                    $event->performAction = false;
                }
                if ($event->performAction == false) {
                    craft()->urlManager->setRouteVariables(array(
                        'resultEntry'    => $entry
                    ));
                }
            }
            // check remaining attempts
            if ($event->params['isNewEntry'] && $entry->sectionId == $this->sectionIdAttempts) {
                $unitEntry = $entry->attemptUnit->first();
                if ( ! is_object($unitEntry) || ! craft()->lantra_attempts->canAttempt($entry->authorId, $unitEntry)) {
                    $event->performAction = false;
                    craft()->request->redirect('/unit/' . $unitEntry->id);
                }
            }
            // add comments
            $comment = craft()->request->getPost('comment');
            if ($entry->sectionId == $this->sectionIdResults && $comment) {
                unset($_POST['comment']);
                $resultComments = craft()->lantra_results->addComment($entry, $comment);
                $event->params['entry']->setContentFromPost(array('resultComments' => $resultComments));
            }
            // handle company licence changes
            if ($entry->sectionId == $this->sectionIdCompanies){
                if (! $this->getSettings()->lantraDisableLicences && ! craft()->lantra_licence->updateCompanyLicences($entry)){
                   $entry->addError('companyRemainingLicences', 'There are insufficient scheme licences.');
                   $event->performAction = false;
                }
                // update company label
                $companyLabel = craft()->lantra_structure->getCompanyLabel($entry);
                $event->params['entry']->setContentFromPost(array('companyLabel' => $companyLabel));
            }
        });

        craft()->on('entries.onSaveEntry', function(Event $event) {
            $this->resetUploads();
            $entry = $event->params['entry'];
            if ($entry->sectionId == $this->sectionIdCompanies) {
                craft()->lantra_structure->saveCompanyChildren($entry);
            }
            // saving user/unit results
            if ($event->params['isNewEntry'] && $entry->sectionId == $this->sectionIdResults) {
                craft()->lantra_results->saveNewResult($entry);
            }
            // set expiry date on entry record
            $dateFormat = 'Y-m-d H:i:s';
            $userExpiryDate = craft()->request->getPost('userExpiryDate');
            if ($entry->sectionId == $this->sectionIdResults && $userExpiryDate) {
                if (false != $date = DateTime::createFromFormat($dateFormat, $userExpiryDate)) {
                    $entryRecord = EntryRecord::model()->findById($entry->id);
                    $entryRecord->expiryDate = $date->getTimestamp();
                    $entryRecord->save(false);
                }
            }
            // mark unit attempt and create result entry
            if ($event->params['isNewEntry'] && $entry->sectionId == $this->sectionIdAttempts && ! craft()->request->isCpRequest()){
                craft()->lantra_attempts->markAttempt($entry);
                craft()->lantra_results->saveAttemptResult($entry);
            }
            if (!craft()->request->isCpRequest()) {
                // Check unit result for new module result
                if ($entry->sectionId == $this->sectionIdResults && $entry->type == 'unitResult') {
                    craft()->lantra_results->checkUnitResult($entry);
                    craft()->lantra_results->checkRemainingAttempts($entry);
                }
                // Check user result for new module result
                if ($entry->sectionId == $this->sectionIdResults && $entry->type == 'userResult') {
                    craft()->lantra_results->checkUserResult($entry);
                }
            }
            // Send notifications on completed module result
            if ($entry->sectionId == $this->sectionIdResults && $entry->type == 'moduleResult' && $entry->resultStatus == 'complete') {
                // module notifications disabled 09/05
                // craft()->lantra_notify->sendModuleResult($entry);
            }
            if (!$this->getSettings()->disableResultCache) {
                // create user result column
                if ($entry->sectionId == $this->sectionIdUnits) {
                    craft()->lantra_results->addUnitColumn($entry->id);
                }
                // save unit result in user result cache
                if ($entry->enabled && $entry->sectionId == $this->sectionIdResults && $entry->type == 'unitResult') {
                    craft()->lantra_results->saveUserResultCache($entry->authorId, $entry);
                }
            }
        });

        craft()->on('entries.onDeleteEntry', function(Event $event) {
            $entry = $event->params['entry'];
            if (!$this->getSettings()->disableResultCache) {
                // delete user result column
                if ($entry->sectionId == $this->sectionIdUnits) {
                    craft()->lantra_results->removeUnitColumn($entry->id);
                }
            }
        });
    }

    /**
     * @return bool
     */
    public function hasCpSection()
    {
        return true;
    }

    /**
     * @return array @note same MUST be copied to settings model
     */
    protected function defineSettings()
    {
        return array(
            ## config settings
            'settingsVersion'                   => AttributeType::Number,

            ## theme settings
            'themeDateFormat'                   => AttributeType::String,
            'themeDefaultLimit'                 => AttributeType::Number,
            'themeLoginMessage'                 => AttributeType::String,
            'themeDisableCertificates'          => AttributeType::Bool,
            'themeResultHistoryTitle'           => AttributeType::String,
            'themeResultHistoryLink'            => AttributeType::Bool,
            'themeDisableResultHistory'         => AttributeType::Bool,
            'themeColorPrimary'                 => AttributeType::String,
            'themeColorSecondary'               => AttributeType::String,
            'themeNavigationPublic'             => AttributeType::Mixed,
            'themeNavigationPrivate'            => AttributeType::Mixed,

            ## scheme settings
            'schemeName'                        => AttributeType::String,
            'schemeDescription'                 => AttributeType::String,
            'schemeLogo'                        => AttributeType::Number,
            'schemeTeams'                       => AttributeType::Bool,
            'schemeUserReadOnly'                => AttributeType::Bool,
            'schemeEmailDomain'                 => AttributeType::String,
            'schemeTestEmailAddress'            => AttributeType::String,
            'jobRoleEndorse'                    => AttributeType::Bool,

            ## licence settings
            'lantraDisableLicences'             => AttributeType::Bool,
            'schemeRemainingLicences'           => AttributeType::Number,
            'schemeExpiryDate'                  => AttributeType::DateTime,

            ## individual company settings
            'individualCompany'                 => AttributeType::Number,
            'individualLicenceDays'             => AttributeType::Number,
            'individualLicencePaypalButton'     => AttributeType::String,
            'individualJobRole'                 => AttributeType::Mixed,

            ## notifications
            'notifyFooter'                      => AttributeType::String,
            'notifySubjectBlockedResult'        => AttributeType::String,
            'notifySubjectEndorsementResult'    => AttributeType::String,
            'notifySubjectLicencesRemaining'    => AttributeType::String,
            'notifySubjectManagerSummary'       => AttributeType::String,
            'notifySubjectModuleResult'         => AttributeType::String,
            'notifySubjectSchemeExpiry'         => AttributeType::String,
            'notifySubjectUserExpiry'           => AttributeType::String,
            'notifySubjectComment'              => AttributeType::String,
            'notifyBlockedResult'               => AttributeType::String,
            'notifyEndorsementResult'           => AttributeType::String,
            'notifyLicencesRemaining'           => AttributeType::String,
            'notifyManagerSummary'              => AttributeType::String,
            'notifyModuleResult'                => AttributeType::String,
            'notifySchemeExpiry'                => AttributeType::String,
            'notifyUserExpiry'                  => AttributeType::String,
            'notifyComment'                     => AttributeType::String,
            'notifySubjectCustomReport'         => AttributeType::String,
            'notifyCustomReport'                => AttributeType::String,

            'disableEndorsementNotify'          => AttributeType::Bool,

            ## user profile
            'userEditName'                      => AttributeType::Bool,
            'userEditEmail'                     => AttributeType::Bool,
            'userEditAddress'                   => AttributeType::Bool,
            'userEditTelephone'                 => AttributeType::Bool,
            'userEditDob'                       => AttributeType::Bool,
            'userEditStartDate'                 => AttributeType::Bool,
            'userEditRole'                      => AttributeType::Bool,
            'userEditPhoto'                     => AttributeType::Bool,
            'userEditCustomFields'              => AttributeType::Mixed,

            ## user profile
            'userAccountInformation'            => AttributeType::Mixed,

            ## labels
            'labelJobRole'                      => AttributeType::String,

            ## queue
            'queue'                             => AttributeType::Mixed,

            ## config settings
            'disableResultCache'                => AttributeType::Bool,
        );
    }

    /**
     * @return string
     */
    public function getSettingsUrl()
    {
        return 'lantra/settings';
    }

    public function registerCpRoutes()
    {
        return array(
            'lantra' =>
                array('action' => 'lantra/settings/index'),
            'lantra/settings' =>
                array('action' => 'lantra/settings/index'),
            'lantra/settings/tools' =>
                array('action' => 'lantra/settings/tools'),
            'lantra/import' =>
                array('action' => 'lantra/import/index'),
        );
    }

    public function registerSiteRoutes()
    {
        return array();
    }

    private function resetUploads()
    {
        unset($_FILES);
        UploadedFile::reset();
    }
}
