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
            $licenceSource = '';
            if ($event->params['isNewUser'] && ! $user->admin) {
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
                // force clear endorsed date if pending
                if ($entry->resultStatus == 'pending') {
                    $entry->setContentFromPost(['resultEndorsedDate' => null]);
                }
                elseif ($oldEntry && $oldEntry->resultStatus == 'pending' && $entry->resultStatus == 'endorsed') {
                    $entry->setContentFromPost(['resultEndorsedDate' => DateTimeHelper::currentTimeForDb()]);
                }
                if ($entry->type == 'unitResult') {
                    $unitEntry = $entry->resultUnit->first();
                    $unitEvidence = $entry->resultEvidence->first();
                }
                // set custom author
                $authorId = craft()->request->getPost('authorId');
                if ($entry->type == 'userResult' && $authorId) {
                    $entry->authorId = $authorId;
                }
                // set date defaults
                $entry->setContentFromPost([
                    'resultStartDate' => '',
                    'resultFinishDate' => ''
                ]);
                // set a user start date
                $userStartDate = craft()->request->getPost('userStartDate');
                if ($userStartDate && $this->checkDate($userStartDate)) {
                    $date = new \DateTime($userStartDate . ' 12:00:00');
                    $entry->setContentFromPost(['resultStartDate' => $date->getTimestamp()]);
                }
                // set a user finish date
                $userFinishDate = craft()->request->getPost('userFinishDate');
                if ($userFinishDate && $this->checkDate($userFinishDate)) {
                    $date = new \DateTime($userFinishDate . ' 12:00:00');
                    $entry->setContentFromPost(['resultFinishDate' => $date->getTimestamp()]);
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
               if ( ! craft()->lantra_licence->updateCompanyLicences($entry)){
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
            $userExpiryDate = craft()->request->getPost('userExpiryDate');
            if ($entry->sectionId == $this->sectionIdResults && $userExpiryDate && $this->checkDate($userExpiryDate)) {
                $date = new \DateTime($userExpiryDate . ' 12:00:00');
                $entryRecord = EntryRecord::model()->findById($entry->id);
                $entryRecord->expiryDate = $date->getTimestamp();
                $entryRecord->save(false);
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
                craft()->lantra_notify->sendModuleResult($entry);
            }
        });
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

    private function checkDate($date) {
        $parts = explode('-', $date);
        return checkdate($parts[1], $parts[2], $parts[0]);
    }
}
