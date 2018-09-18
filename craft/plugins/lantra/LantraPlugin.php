<?php

namespace Craft;

class LantraPlugin extends BasePlugin
{
    private $sectionIdAttempts = 12;
    private $sectionIdResults = 10;
    private $sectionIdCompanies = 3;

    function getName()
    {
        return Craft::t('Lantra');
    }

    function getVersion()
    {
        return '0.0.1';
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
        parent::init();

        // check user licence
        craft()->on('users.onBeforeSaveUser', function(Event $event) {
            $user = $event->params['user'];
            if ($event->params['isNewUser'] && ! $user->admin) {
                // assign company licence if joining a team
                if ($user->userTeam->total()) {
                    if (false == craft()->lantra_licence->assignCompanyLicence($user)) {
                        $event->performAction = false;
                        $user->addError('userTeam', 'There are insufficient company licences to join this team.');
                    }
                }
                // assign scheme licence
                elseif (false == craft()->lantra_licence->assignSchemeLicence()) {
                    $event->performAction = false;
                    $user->addError('userTeam', 'There are insufficient scheme licences.');
                }
            }
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
                // Check endorsed change
                $oldEntry = craft()->entries->getEntryById($entry->id);
                if ($oldEntry && $oldEntry->resultStatus == 'pending' && $entry->resultStatus == 'endorsed') {
                    $entry->setContentFromPost(['resultEndorsedDate' => DateTimeHelper::currentTimeForDb()]);
                }
                if ($entry->type == 'unitResult') {
                    $unitEntry = $entry->resultUnit->first();
                    $unitEvidence = $entry->resultEvidence->first();
                    // Check evidence results
                    if ($event->params['isNewEntry'] && $unitEntry->unitType == 'evidence') {
                        if (empty($unitEvidence)) {
                            $entry->addError('fields[resultEvidence]', 'You must submit a file!');
                            $event->performAction = false;
                        }
                    }
                }
                // set custom author
                $authorId = craft()->request->getPost('authorId');
                if ($entry->type == 'userResult' && $authorId) {
                    $entry->authorId = $authorId;
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
            // handle company licence changes
            if ($entry->sectionId == $this->sectionIdCompanies){
               if ( ! craft()->lantra_licence->updateCompanyLicences($entry)){
                   $entry->addError('companyRemainingLicences', 'There are insufficient scheme licences.');
                   $event->performAction = false;
               }
            }
        });

        craft()->on('entries.onSaveEntry', function(Event $event) {
            $entry = $event->params['entry'];
            // saving user/unit results
            if ($event->params['isNewEntry'] && $entry->sectionId == $this->sectionIdResults) {
                craft()->lantra_results->saveNewResult($entry);
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
            // Send notifications on completed module result
            if ($entry->sectionId == $this->sectionIdResults && $entry->type == 'moduleResult' && $entry->resultStatus == 'complete') {
                craft()->lantra_notify->sendModuleResult($entry);
            }
        });
    }

    public function registerSiteRoutes()
    {
        return array();
    }
}
