<?php
namespace Craft;

class Lantra_NotifyService extends BaseApplicationComponent
{
    /**
     * Notify managers of module result
     *
     * @param $entry
     * @return null
     * @throws Exception
     */
    function notifyModuleResult(EntryModel $entry) {
        $module = $entry->resultModule->first();
        $author = $entry->getAuthor();
        $authorFullName = $author->getFullName();
        $company = craft()->lantra_users->userCompany($author);
        $subject = "Module ["  . $module->id . "] " . $authorFullName;
        $body = "User: " . $authorFullName  . "\n\n";
        $body .= "Company: " . $company->title . "\n\n";
        $body .= "Module Completed: " . $module->title . "\n\n";
        // send the emails to managers
        $this->notifyManagers($entry->getAuthor(), $subject, $body);
    }

    /**
     * Send a message to a user's team managers
     *
     * @param $user
     * @param $subject
     * @param $body
     * @return bool
     */
    function notifyManagers($user, $subject, $body) {

        $managers = craft()->lantra_users->getTeamMangers($user);
        $email = new EmailModel();
        $email->subject = $subject;
        $email->body = $body;

        foreach ($managers as $manager) {
            try {
                // in dev mode, all notifications sent to system email
                // @todo $toEmail = $manager->email;
                $toEmail = craft()->systemSettings->getSetting('email', 'emailAddress');
                $email->body = $body . "\n\n\nNotification sent to: " . $manager->email;
                $email->toEmail = $toEmail;
                // send the message
                craft()->email->sendEmail($email);
            } catch (\Exception $e) {
                Craft::log('notifyManagers() failed: ' . $e->getMessage(),LogLevel::Warning, false, 'notify', 'lantra');
                return false;
            }
        }
    }
}
