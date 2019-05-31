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
        $subject = $this->getNotifySetting('subjectSchemeExpiry', 'Scheme Expiry Date');
        $variables = ['expiryDate' => $expiryDate];
        $template = $this->getNotifySetting('userExpiry', "Your scheme expires on  {{ expiryDate|date('d-m'Y') }}.");
        $message = craft()->templates->renderString($template, $variables);
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
           $subject = $this->getNotifySetting('subjectUserExpiry', 'User Expiry Date');
           foreach ($criteria->find() as $user) {
               $variables = ['user' => $user];
               $template = $this->getNotifySetting('userExpiry', "Your individual licence expires on {{ user.userExpiryDate|date('d-m'Y') }}.");
               $message = craft()->templates->renderString($template, $variables);
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
        $subject = $this->getNotifySetting('subjectLicencesRemaining', 'Licences Remaining');
        foreach ($criteria->find() as $manager) {
            $remainingLicences = craft()->lantra_licence->getSchemeLicences();
            if ($remainingLicences <= 10) {
                $variables = ['title' =>  craft()->getSiteName(), 'licences' => craft()->lantra_licence->getSchemeLicences()];
                $template = $this->getNotifySetting('licencesRemaining', "{{ title }} has {{ licences}} remaining.");
                $message = craft()->templates->renderString($template, $variables);
                $this->notify($manager->email, $subject, $message);
            }
        }
        // send company managers remaining company licences
        $criteria = craft()->elements->getCriteria(ElementType::Entry);
        $criteria->section = 'companies';
        $criteria->limit = null;
        foreach ($criteria->find() as $company) {
            $remainingLicences = $company->companyRemainingLicences;
            if ($remainingLicences <= 10 && $company->companyPrimaryManagers->total()) {
                $emails = [];
                foreach($company->companyPrimaryManagers as $primaryManager) {
                    $emails[] = $primaryManager->email;
                }
                $variables = ['title' =>  $company->title, 'licences' => $remainingLicences];
                $template = $this->getNotifySetting('licencesRemaining', "{{ title }} has {{ licences}} remaining.");
                $message = craft()->templates->renderString($template, $variables);
                $this->notify($emails, $subject, $message);
            }
        }
    }

    /**
     * @param EntryModel $entry
     */
    function sendCommentUpdate(EntryModel $entry, $comment, $userId) {
        $user = craft()->users->getUserById($userId);
        $variables = ['entry' => $entry, 'user' => $user, 'comment' => $comment];
        $subject = $this->getNotifySetting('subjectComment', 'New Comment');
        $template = $this->getNotifySetting('comment', "{{ entry.title }} - {{ user.fullName}}: {{ comment }}");
        $message = craft()->templates->renderString($template, $variables);
        // manager commenting - notify user
        if ($userId != $entry->authorId) {
            $this->notify($entry->getAuthor()->email, $subject, $message);
        }
        // user commenting - notify managers
        else {
            $this->notifyManagers($entry->getAuthor(), $subject, $message);
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
        $moduleEntry = $entry->resultModule->first();
        $user = $entry->getAuthor();
        $subject = $this->getNotifySetting('subjectModuleResult', 'Module Completed');
        $variables = ['entry' => $moduleEntry, 'user' => $user];
        $template = $this->getNotifySetting('moduleResult', "{{ user.fullName}} has completed {{ entry.title }}.");
        $message = craft()->templates->renderString($template, $variables);

        // send the emails to managers
        $this->notify($user->email, $subject, $message);
        $this->notifyManagers($user, $subject, $message);
    }

    /**
     * Notify managers of no attempts remaining (blocked result)
     *
     * @throws Exception
     */
    function sendManagerBlockedResult(EntryModel $resultEntry) {
        $unitEntry = $resultEntry->resultUnit->first();
        $user = $resultEntry->getAuthor();
        $subject = $this->getNotifySetting('subjectBlockedResult', 'Result Blocked');
        $variables = ['entry' => $unitEntry, 'user' => $user];
        $template = $this->getNotifySetting('blockedResult', "{{ user.fullName}} has run out of attempts for unit {{ entry.title }} and the result is blocked.");
        $message = craft()->templates->renderString($template, $variables);
        // send the emails to managers
        $this->notifyManagers($user, $subject, $message);
    }

    /**
     * Notify manager of result requiring endorsement
     *
     * @param EntryModel
     * @param int
     * @throws Exception
     */
    function sendManagerEndorsementResult(EntryModel $resultEntry, $level = 1) {
        $user = $resultEntry->getAuthor();
        $subject = $this->getNotifySetting('subjectEndorsementResult', 'Endorsement Required');
        $variables = ['entry' => $resultEntry, 'user' => $user];
        $template = $this->getNotifySetting('endorsementResult', "{{ user.fullName}} has submitted a result {{ entry.title }}.");
        $message = craft()->templates->renderString($template, $variables);
        // send the emails to managers
        $manager = craft()->lantra_users->getUserManagerByLevel($user, $level);
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
        $subject = $this->getNotifySetting('subjectManagerSummary', 'Manager Summary');
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
                $message .= "Team: " . ($result->author->userTeam->count() ? $result->author->userTeam->first()->title : '~') . "\n\n";
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
    public function getNotifySetting($key, $default = '') {
        return craft()->lantra_settings->getSetting('notify'.ucwords($key), $default);
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
        // all notifications sent to test email address
        $server = craft()->lantra_settings->getConfig('server', 'dev');
        if ($server != 'prod') {
            $subject = '[' . $server . '] ' . $subject;
            $message .= "\n\n\nNotification for: " . implode(', ', $toEmail);
            $schemeTestEmails = explode(',', craft()->lantra_settings->getSetting('schemeTestEmailAddress'));
            $toEmail = count($schemeTestEmails) ? $schemeTestEmails : [craft()->systemSettings->getSetting('email', 'emailAddress')];
        }
        // add notification footer
        $message .= $this->getNotifySetting('footer');
        $message = craft()->templates->render('lantra/emails/default', ['message' => $message]);

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
