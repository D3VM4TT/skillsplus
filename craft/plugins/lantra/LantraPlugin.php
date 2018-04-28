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
            // check remaining attempts
            if ($event->params['isNewEntry'] && $entry->sectionId == $this->sectionIdAttempts) {
                $unitEntry = $entry->attemptUnit->first();
                if ( ! is_object($unitEntry) || ! craft()->lantra_attempts->canAttempt($entry->authorId, $unitEntry)) {
                    $event->performAction = false;
                    craft()->request->redirect('/unit/' . $unitEntry->id);
                }
            }
            // handle scheme licences
            if ($entry->sectionId == $this->sectionIdCompanies){
                $oldEntry = craft()->entries->getEntryById($entry->id);
                $existingCompanyLicences = ($oldEntry) ? $oldEntry->companyRemainingLicences : 0;
                $globalsScheme = craft()->globals->getSetByHandle('scheme');
                // look for change
                if ($existingCompanyLicences != $entry->companyRemainingLicences) {
                    $content = false;
                    // return scheme licences
                    if ($existingCompanyLicences > $entry->companyRemainingLicences) {
                        $returnedLicences = $existingCompanyLicences - $entry->companyRemainingLicences;
                        $content = ['schemeRemainingLicences' => $globalsScheme->schemeRemainingLicences + $returnedLicences];
                    }
                    // remove scheme licences
                    else {
                        $newLicences = $entry->companyRemainingLicences - $existingCompanyLicences;
                        // check scheme remaining licences
                        if ($newLicences > $globalsScheme->schemeRemainingLicences) {
                            $entry->addError('companyRemainingLicences', 'There are insufficient scheme licences.');
                            $event->performAction = false;
                        }
                        else {
                            // update scheme remaining licences
                            $content = ['schemeRemainingLicences' => $globalsScheme->schemeRemainingLicences - $newLicences];
                        }
                    }
                    // update the global
                    if ($content) {
                        $globalsScheme->setContentFromPost($content);
                        craft()->globals->saveContent($globalsScheme);
                    }
                }
            }
        });

        craft()->on('entries.onSaveEntry', function(Event $event) {
            $entry = $event->params['entry'];
            // Mark unit attempt and create result entry
            if ($event->params['isNewEntry'] && $entry->sectionId == $this->sectionIdAttempts && ! craft()->request->isCpRequest()){
                craft()->lantra_attempts->markAttempt($entry);
                craft()->lantra_results->saveAttemptResult($entry);
            }
            // Check unit result for new module result
            if ($entry->sectionId == $this->sectionIdResults && $entry->type == 'unitResult') {
                craft()->lantra_results->checkUnitResult($entry);
            }
            // Send notifications on new module result
            if ($event->params['isNewEntry'] && $entry->sectionId == $this->sectionIdResults && $entry->type == 'moduleResult') {
                craft()->lantra_notify->notifyModuleResult($entry);
            }
        });
    }

    public function registerSiteRoutes()
    {
        return array(
            'lantra/cron' => array('action' => 'lantra/cron/runCron'),
        );
    }
}
