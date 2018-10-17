<?php
namespace Craft;

class Lantra_NotifyService extends BaseApplicationComponent
{
    /**
     * Notify scheme managers of scheme expiry
     *
     * @param $expiryDate
     * @throws Exception
     */
    function sendSchemeExpiry($expiryDate) {
        // send scheme managers remaining scheme licences
        $criteria = craft()->elements->getCriteria(ElementType::User);
        $criteria->groupId = 1;
        $criteria->limit = null;
        $subject = $this->getNotifyGlobal('subjectSchemeExpiry', 'Scheme Expiry Date');
        $message = "Your scheme expires on " . date('d/m/y', $expiryDate->getTimestamp()) . ".";
        foreach ($criteria->find() as $manager) {
            $this->notify($manager->email, $subject, $message);
        }
    }
    /**
     * Notify users of user expiry
     *
     * @param $expiryDate
     * @throws mixed
     */
    function sendUserExpiry($expiryDate) {
       $criteria = craft()->lantra_users->getExpiringUsers($expiryDate);
       if ($criteria->total()) {
           $subject = $this->getNotifyGlobal('subjectUserExpiry', 'User Expiry Date');
           foreach ($criteria->find() as $user) {
               $message = "Your individual licence expires on " . date('d/m/y', $user->userExpiryDate->getTimestamp()) . ".";
               $this->notify($user->email, $subject, $message);
           }
       }
    }

    /**
     * Notify managers of licences remaining
     *
     * @throws Exception
     */
    function sendLicencesRemaining() {
        // send scheme managers remaining scheme licences
        $criteria = craft()->elements->getCriteria(ElementType::User);
        $criteria->groupId = 1;
        $criteria->limit = null;
        $subject = $this->getNotifyGlobal('subjectLicencesRemaining', 'Licences Remaining');
        foreach ($criteria->find() as $manager) {
            $remainingLicences = craft()->lantra_licence->getSchemeLicences();
            if ($remainingLicences <= 10) {
                $message = "Your scheme has  " . craft()->lantra_licence->getSchemeLicences() . " remaining licences.";
                $this->notify($manager->email, $subject, $message);
            }
        }
        // send company managers remaining company licences
        $criteria = craft()->elements->getCriteria(ElementType::Entry);
        $criteria->section = 'companies';
        $criteria->limit = null;
        foreach ($criteria->find() as $company) {
            $remainingLicences = $company->companyRemainingLicences;
            if ($remainingLicences <= 10) {
                $manager = $company->companyPrimaryManager->first();
                if ($manager) {
                    $message = $company->title . " has  " . $remainingLicences . " remaining licences.";
                    $this->notify($manager->email, $subject, $message);
                }
            }
        }
    }

     /**
    * Notify users and managers of module result
    *
    * @param $entry
    * @return null
    * @throws mixed
    */
    function sendModuleResult(EntryModel $entry) {
        $module = $entry->resultModule->first();
        $author = $entry->getAuthor();
        $authorFullName = $author->getFullName();
        $subject = $this->getNotifyGlobal('subjectModuleResult', 'Module Completed');
        $message = $authorFullName  . " has completed " . $module->title;
        // send the emails to managers
        $this->notify($entry->getAuthor()->email, $subject, $message);
        $this->notifyManagers($entry->getAuthor(), $subject, $message);
    }

    /**
     * Notify managers of no attempts remaining (blocked result)
     *
     * @throws Exception
     */
    function sendManagerBlockedResult(EntryModel $resultEntry) {
        $unitEntry = $resultEntry->resultUnit->first();
        $author = $resultEntry->getAuthor();
        $authorFullName = $author->getFullName();
        $subject = $this->getNotifyGlobal('subjectBlockedResult', 'Result Blocked');
        $message = $authorFullName  . " has run out of attempts for unit " . $unitEntry->id . ' and the result is blocked.';
        // send the emails to managers
        $this->notifyManagers($author, $subject, $message);
    }

    /**
     * Notify manager of result requiring endorsement
     *
     * @param EntryModel
     * @param int
     * @throws Exception
     */
    function sendManagerEndorsementResult(EntryModel $resultEntry, $level = 1) {
        $author = $resultEntry->getAuthor();
        $authorFullName = $author->getFullName();
        $subject = $this->getNotifyGlobal('subjectEndorsementResult', 'Endorsement Required');
        $message = $authorFullName  . " has submitted a result " . $resultEntry->title . '.';
        // send the emails to managers
        $manager = craft()->lantra_users->getUserManagerByLevel($author, $level);
        if ($manager) {
            $this->notify($manager->email, $subject, $message);
        }
    }

    /**
     * Notify managers summary
     *
     * @param $manager
     * @param int $days
     * @return null
     * @throws mixed
     */
    function sendManagerSummary(UserModel $manager, $days = 7) {
        $subject = $this->getNotifyGlobal('subjectManagerSummary', 'Manager Summary');
        $criteria = craft()->lantra_results->getManagerModuleExpiringResults($manager->id, $days, null);
        if ($criteria && $criteria->total()) {
            $message = "The following user results expire in the next " . $days . " days:\n\n";
            foreach ($criteria->find() as $result) {
                $moduleEntry = $result->resultModule->first();
                $message .= "User: " . $result->author->getFullName() . "\n\n";
                $message .= "Team: " . $result->author->userTeam->first()->title . "\n\n";
                $message .= "Module: " . ($moduleEntry ? $moduleEntry->title : '~'). "\n\n";
                $message .= "Expires: " . $result->expiryDate . "\n\n";
                $message .= "\n##########################\n\n";
            }
        }
        else {
            $message = "There are no expiring results in the next " . $days . " days:\n\n";
        }

        $criteria = craft()->lantra_results->getManagerModuleCompletedResults($manager->id, $days, null);
        if ($criteria && $criteria->total()) {
            $message .= "The following modules have been completed in the past " . $days . " days:\n\n";
            foreach ($criteria->find() as $result) {
                $moduleEntry = $result->resultModule->first();
                $message .= "User: " . $result->author->getFullName() . "\n\n";
                $message .= "Team: " . $result->author->userTeam->first()->title . "\n\n";
                $message .= "Module: " . ($moduleEntry ? $moduleEntry->title : '~') . "\n\n";
                $message .= "Expires: " . $result->expiryDate . "\n\n";
                $message .= "\n##########################\n\n";
            }
        }
        else {
            $message = "There are no expiring results in the next " . $days . " days:\n\n";
        }
        // send the emails to managers
        $this->notify($manager->email, $subject, $message);
    }

    /**
     * Send a message to a user's team managers
     *
     * @param $user
     * @param $subject
     * @param $message
     * @throws mixed
     */
    function notifyManagers($user, $subject, $message) {
        $managers = craft()->lantra_users->getUserMangers($user);
        if ($managers && count($managers)) {
            foreach ($managers as $manager) {
                $this->notify($manager->email, $subject, $message);
            }
        }
    }

    /** Get notification global
     *
     * @param string
     * @param string
     * @return string
     */
    public function getNotifyGlobal($key, $default = '') {
        $globalsNotify = craft()->globals->getSetByHandle('globalsNotify');
        $key = 'notify' . ucwords($key);
        return isset($globalsNotify->$key) ? $globalsNotify->$key : $default;
    }

    /**
     * Send a message to a user
     *
     * @param $toEmail
     * @param $subject
     * @param $message
     * @param $attachments
     * @return mixed
     * @throws mixed
     */
    function notify($toEmail, $subject, $message, $attachments = [])
    {
        if ( ! is_array($toEmail)) {
            $toEmail = [$toEmail];
        }
        // in dev mode, all notifications sent to system email
        if ( craft()->config->get( 'devMode' ) ) {
            $message .= "\n\n\nNotification for: " . implode(', ', $toEmail);
            $toEmail = [craft()->systemSettings->getSetting('email', 'emailAddress')];
        }
        // add notification footer
        $message .= $this->getNotifyGlobal('footer');
        // build the email
        $email = new EmailModel();
        $email->subject = $subject;
        $email->body = $message;
        $return = true;
        foreach($toEmail as $address) {
            $email->toEmail = $address;
            try {
                if (count($attachments)) {
                    foreach($attachments as $attachment) {
                        $this->addAttachment($email, $attachment);
                    }
                }
                if (craft()->email->sendEmail($email)) {
                    Craft::log('notify(' . $address . ')', LogLevel::Info, true, 'notify', 'lantra');
                }
                else {
                    Craft::log('notify(' .  $address. ') ' . implode(', ', $email->getAllErrors()),LogLevel::Error, true, 'notify', 'lantra');
                    $return = false;
                }
            } catch (\Exception $e) {
                Craft::log('notify(' .  $address. ') ' . $e->getMessage(),LogLevel::Error, true, 'notify', 'lantra');
                $return = false;
            }
        }
        return $return;
    }

    /**
     * @param $email
     * @param $attachment
     */
    function addAttachment(EmailModel $email, $attachment) {
        try {
            $email->addAttachment($attachment['path'], $attachment['filename'], 'base64');
        }
        catch (\Exception $e) {
            Craft::log('notify(' .  $email->toEmail. ') ' . $e->getMessage(),LogLevel::Error, true, 'notify', 'lantra');
        }
    }
}
