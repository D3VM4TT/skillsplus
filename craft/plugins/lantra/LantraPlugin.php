<?php

namespace Craft;

class LantraPlugin extends BasePlugin
{
    private $sectionIdAttempts = 12;
    private $sectionIdResults = 10;
    private $sectionIdCompanies = 3;

    /*
     * Settings version (auto migrate settings)
     */
    private $settingsVersion = 2;

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

        // check user licence
        craft()->on('users.onBeforeSaveUser', function(Event $event) {
            $user = $event->params['user'];
            $licenceSource = '';
            if ($event->params['isNewUser'] && ! $user->admin) {
                // assign company licence if joining a team
                if (! $this->getSettings()->lantraDisableLicences && $user->userCompany->count() OR $user->userTeam->count()) {
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

        // Stop deletes
        craft()->on('users.onBeforeDeleteUser', function(Event $event) {
            if ( ! craft()->request->isCpRequest()){
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
                // Check endorsed change
                $oldEntry = craft()->entries->getEntryById($entry->id);
                $currentUser = craft()->userSession->getUser();
                // Auto endorse
                if (! craft()->request->isCpRequest() && $entry->resultStatus != 'draft' && $entry->authorId != $currentUser->id && craft()->lantra_users->isManager($entry->authorId)) {
                    $entry->setContentFromPost(['resultStatus' => 'endorsed']);
                    if (!$oldEntry) {
                        $entry->setContentFromPost(['resultEndorsedDate' => DateTimeHelper::currentTimeForDb()]);
                    }
                }
                // force clear endorsed date if pending
                if ($entry->resultStatus == 'pending') {
                    $entry->setContentFromPost(['resultEndorsedDate' => null]);
                } elseif ($oldEntry && $oldEntry->resultStatus == 'pending' && $entry->resultStatus == 'endorsed') {
                    $entry->setContentFromPost(['resultEndorsedDate' => DateTimeHelper::currentTimeForDb()]);
                }
                if ($entry->type == 'unitResult') {
                    $unitEntry = $entry->resultUnit->first();
                    $unitEvidence = $entry->resultEvidence->first();
                }

                $dateFormat = craft()->lantra_settings->getSetting('themeDateFormat', 'd-m-Y');
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
            }
        });

        craft()->on('entries.onSaveEntry', function(Event $event) {
            $this->resetUploads();
            $entry = $event->params['entry'];
            // saving user/unit results
            if ($event->params['isNewEntry'] && $entry->sectionId == $this->sectionIdResults) {
                craft()->lantra_results->saveNewResult($entry);
            }
            // set expiry date on entry record
            $dateFormat = craft()->lantra_settings->getSetting('themeDateFormat', 'd-m-Y');
            $userExpiryDate = craft()->request->getPost('userExpiryDate');
            if ($entry->sectionId == $this->sectionIdResults && $userExpiryDate) {
                if (false != $date = DateTime::createFromFormat($dateFormat, $userExpiryDate)) {
                    $entryRecord = EntryRecord::model()->findById($entry->id);
                    $entryRecord->expiryDate = $date->getTimestamp();
                    $entryRecord->save(false);
                }
            }
            // Mark unit attempt and create result entry
            if ($event->params['isNewEntry'] && $entry->sectionId == $this->sectionIdAttempts && ! craft()->request->isCpRequest()){
                craft()->lantra_attempts->markAttempt($entry);
                craft()->lantra_results->saveAttemptResult($entry);
            }
            // Check unit result for new module result
            if ($entry->sectionId == $this->sectionIdResults && $entry->type == 'unitResult') {
                craft()->lantra_results->checkUnitResult($entry);
                craft()->lantra_results->checkRemainingAttempts($entry);
            }
            // Check user result for new module result
            if ($entry->sectionId == $this->sectionIdResults && $entry->type == 'userResult') {
                craft()->lantra_results->checkUserResult($entry);
            }
            // Send notifications on completed module result
            if ($entry->sectionId == $this->sectionIdResults && $entry->type == 'moduleResult' && $entry->resultStatus == 'complete') {
                // module notifications disabled 09/05
                // craft()->lantra_notify->sendModuleResult($entry);
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

            ## licence settings
            'lantraDisableLicences'             => AttributeType::Bool,
            'schemeRemainingLicences'           => AttributeType::Number,
            'schemeExpiryDate'                  => AttributeType::DateTime,

            ## individual company settings
            'individualCompany'                 => AttributeType::Number,
            'individualLicenceDays'             => AttributeType::Number,
            'individualLicencePaypalButton'     => AttributeType::String,

            ## notifications
            'notifyFooter'                      => AttributeType::String,
            'notifySubjectBlockedResult'        => AttributeType::String,
            'notifySubjectEndorsementResult'    => AttributeType::String,
            'notifySubjectLicencesRemaining'    => AttributeType::String,
            'notifySubjectManagerSummary'       => AttributeType::String,
            'notifySubjectModuleResult'         => AttributeType::String,
            'notifySubjectSchemeExpiry'         => AttributeType::String,
            'notifySubjectUserExpiry'           => AttributeType::String,

            ## user profile
            'userEditName'                      => AttributeType::Bool,
            'userEditEmail'                     => AttributeType::Bool,
            'userEditAddress'                   => AttributeType::Bool,
            'userEditTelephone'                 => AttributeType::Bool,
            'userEditDob'                       => AttributeType::Bool,
            'userEditRole'                      => AttributeType::Bool,
            'userEditPhoto'                     => AttributeType::Bool,
            'userEditCustomFields'              => AttributeType::Mixed,

            ## user profile
            'userAccountInformation'            => AttributeType::Mixed,
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
