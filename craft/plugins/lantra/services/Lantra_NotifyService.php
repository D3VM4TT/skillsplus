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
        $message = "User: " . $authorFullName  . "\n\n";
        $message .= "Company: " . $company->title . "\n\n";
        $message .= "Module Completed: " . $module->title . "\n\n";
        // send the emails to managers
        $this->notifyManagers($entry->getAuthor(), $subject, $message);
    }

    /**
     * Send a message to a user
     *
     * @param $toEmail
     * @param $subject
     * @param $message
     * @return mixed
     * @throws Exception
     */
    function notify($toEmail, $subject, $message) {

        $email = new EmailModel();
        $email->subject = $subject;
        $email->body = $message;
        $email->toEmail = $toEmail;
        try {
            return craft()->email->sendEmail($email);
        } catch (\Exception $e) {
            Craft::log('notify() failed: ' . $e->getMessage(),LogLevel::Warning, false, 'notify', 'lantra');
            return false;
        }
    }

    /**
     * Notify managers of expiring results
     *
     * @param $manager
     * @param int $futureDays
     * @return null
     * @throws Exception
     */
    function notifyExpiringResults(UserModel $manager, $futureDays = 7) {

        $results = craft()->lantra_results->getManagerExpiringResults($manager->id, $futureDays)->find();
        if (count($results)) {
            return null;
        }

        $subject = "Expiring Results";
        $message = "The following modules results expire in the next " . $futureDays . " days:\n\n";
        foreach($results as $result) {
            $message .= "User: " . $result->author  . "\n\n";
            $message .= "Module: " . $result->resultModule->first()->title  . "\n\n";
            $message .= "Expires: " . date('d/m/y', $result->expiryDate) . "\n\n";
            $message .= "\n\n";
        }

        // send the emails to managers
        $this->notify($manager->email, $subject, $message);
    }

    /**
     * Send a message to a user's team managers
     *
     * @param $user
     * @param $subject
     * @param $body
     * @return bool
     * @throws Exception
     */
    function notifyManagers($user, $subject, $message) {

        $managers = craft()->lantra_users->getTeamMangers($user);

        foreach ($managers as $manager) {
            // in dev mode, all notifications sent to system email
            // @todo $this->notify($manager->email, $subject, $message);
            $toEmail = craft()->systemSettings->getSetting('email', 'emailAddress');
            $this->notify($toEmail, $subject, $message . "\n\n\nNotification sent to: " . $manager->email);
        }
    }
}
