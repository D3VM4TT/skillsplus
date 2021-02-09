<?php
/**
 * Lantra Skills Plus for Craft CMS 3.x
 *
 * @link      https://coffeebean.design
 * @copyright Copyright (c) 2020 Coffee Bean Design
 */

namespace lantra\sp\services;

use Craft;
use craft\base\Component;
use craft\elements\User;
use craft\elements\Entry;
use craft\mail\Message;
use craft\web\View;

use lantra\sp\helpers\LantraHelper;
use lantra\sp\Plugin as Lantra;
use lantra\sp\models\Cycle;
use lantra\sp\models\CyclePeriod;
use lantra\sp\helpers\CycleHelper;
use verbb\supertable\elements\SuperTableBlockElement;

class Notify extends Component
{
    /**
     * @param Entry $package
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\SyntaxError
     */
    function sendAssessment(Entry $package)
    {
        ## notification is disabled
        if (!$this->isEnabled('stepUnassigned')) {
            return;
        }
        $user = $package->author;
        $taskbookLabel = LantraHelper::setting('taskbookLabel', 'taskbook');
        $subject = $this->getNotifySetting('subjectAssessment', ucwords($taskbookLabel) . ' Assessment');
        $variables = [
            'package'           => $package,
            'assessmentText'    => $this->assessmentText($package),
            'moduleGroup'       => $package->moduleGroup,
            'user'              => $user,
        ];
        $template = $this->getNotifySetting('assessment', "Assessment for {{ moduleGroup.title }}. \n\n{{ assessmentText }}");
        $message = Craft::$app->view->renderString($template, $variables);
        $this->notify($user->email, $subject, $message);
    }

    /**
     * @param $package
     * @return string
     */
    private function assessmentText($package)
    {
        $text = "";
        foreach ($package->packageAssessment as $row) {
            $moduleGroup = $row->assessmentModuleGroup->last();
            $text .= $moduleGroup->title . " - " . ($row->assessmentPassed ? 'Passed' : 'Failed') . "\n";
        }
        return $text;
    }

    /**
     * @param $user
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\SyntaxError
     */
    function sendNewMembership($user)
    {
        ## notification is disabled
        if (!$this->isEnabled('newMembership')) {
            return;
        }
        $jobRole = $user->userRole->one();
        $subject = $this->getNotifySetting('subjectNewMembership', 'New Membership');
        $membership = LantraHelper::getMembership($user);
        $variables = [
            'user'          => $user,
            'jobRole'       => $jobRole,
            'membership'    => $membership
        ];
        $template = $this->getNotifySetting('newMembership', "New Membership for {{ user.fullname }} - {{ jobRole.title }} - £{{ membership.cost }}.");
        $message = Craft::$app->view->renderString($template, $variables);
        $schemeManagerEmails = Lantra::$app->users->getSchemeManagersEmails();
        $this->notify($schemeManagerEmails, $subject, $message);
    }

    /**
     * @param Entry $packageEntry
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\SyntaxError
     */
    function sendNewPackage(Entry $packageEntry)
    {
        ## notification is disabled
        if (!$this->isEnabled('newPackage')) {
            return;
        }
        $taskbookLabel = LantraHelper::setting('taskbookLabel', 'taskbook');
        $subject = $this->getNotifySetting('subjectNewPackage', 'New ' . $taskbookLabel);
        $variables = [
            'package'       => $packageEntry,
            'moduleGroup'   => $packageEntry->moduleGroup,
            'user'          => $packageEntry->author,
        ];
        $template = $this->getNotifySetting('newPackage', "New $taskbookLabel for {{ user.fullname }} - {{ moduleGroup.title }}.");
        $message = Craft::$app->view->renderString($template, $variables);
        $schemeManagerEmails = Lantra::$app->users->getSchemeManagersEmails();
        $this->notify($schemeManagerEmails, $subject, $message);
    }

    /**
     * @param SuperTableBlockElement $step
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\SyntaxError
     */
    function sendStepUnassigned(SuperTableBlockElement $step)
    {
        ## notification is disabled
        if (!$this->isEnabled('stepUnassigned')) {
            return;
        }
        if ($step->reviewUser->one()) {
            return;
        }
        $taskbookLabel = LantraHelper::setting('taskbookLabel', 'taskbook');
        $subject = $this->getNotifySetting('subjectStepUnassigned', ucwords($taskbookLabel) . ' Review Unassigned');
        $package = $step->owner;
        $variables = [
            'step'          => $step,
            'package'       => $package,
            'moduleGroup'   => $package->moduleGroup,
            'user'          => $package->author,
            'type'          => $step->reviewStepType,
        ];
        $template = $this->getNotifySetting('stepUnassigned', "Reviewer not assigned for {{ step.reviewStepName }} ({{ type }}) for {{ user.fullname }} - {{ moduleGroup.title }}.");
        $message = Craft::$app->view->renderString($template, $variables);
        $emails = Lantra::$app->users->getSchemeManagersEmails();
        $managers = Lantra::$app->users->getUserMangers($package->author);
        foreach ($managers as $manager) {
            $emails[] = $manager->email;
        }
        $this->notify($emails, $subject, $message);
    }

    /**
     * @param SuperTableBlockElement $step
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\SyntaxError
     */
    function sendStepRequest(SuperTableBlockElement $step)
    {
        ## notification is disabled
        if (!$this->isEnabled('stepRequest')) {
            return;
        }
        if (null == $manager = $step->reviewUser->one()) {
            return;
        }
        $taskbookLabel = LantraHelper::setting('taskbookLabel', 'taskbook');
        $subject = $this->getNotifySetting('subjectStepRequest' . ucwords($step->reviewStepType), ucwords($taskbookLabel) . ' Review Request');
        $package = $step->owner;
        $variables = [
            'step'          => $step,
            'package'       => $package,
            'moduleGroup'   => $package->moduleGroup,
            'user'          => $package->author,
            'type'          => $step->reviewStepType,
        ];
        $template = $this->getNotifySetting('stepRequest', "Request for {{ step.reviewStepName }} ({{ type }}) for {{ user.fullname }} - {{ moduleGroup.title }}.");
        $message = Craft::$app->view->renderString($template, $variables);
        $this->notify($manager->email, $subject, $message);
    }

    /**
     * @param SuperTableBlockElement $step
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\SyntaxError
     */
    function sendStepAssign(SuperTableBlockElement $step)
    {
        ## notification is disabled
        if (!$this->isEnabled('stepAssign')) {
            return;
        }
        if (null == $manager = $step->reviewUser->one()) {
            return;
        }
        $taskbookLabel = LantraHelper::setting('taskbookLabel', 'taskbook');
        $subject = $this->getNotifySetting('subjectStepAssign' . ucwords($step->reviewStepType), ucwords($taskbookLabel) . ' Review Assignment');
        $package = $step->owner;
        $variables = [
            'step'          => $step,
            'package'       => $package,
            'moduleGroup'   => $package->moduleGroup,
            'user'          => $package->author,
            'type'          => $step->reviewStepType,
        ];
        $template = $this->getNotifySetting('stepAssign', "You have been assigned for {{ step.reviewStepName }} ({{ type }}) for {{ user.fullname }} - {{ moduleGroup.title }}.");
        $message = Craft::$app->view->renderString($template, $variables);
        $this->notify($manager->email, $subject, $message);
    }

    /**
     * @param SuperTableBlockElement $step
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\SyntaxError
     */
    function sendStepUpdate(SuperTableBlockElement $step, $user)
    {
        ## notification is disabled
        if (!$this->isEnabled('stepUpdate')) {
            return;
        }
        $result = $step->reviewPassed ? 'passed' : 'failed';
        $taskbookLabel = LantraHelper::setting('taskbookLabel', 'taskbook');
        $subject = $this->getNotifySetting('subjectStepUpdate', ucwords($taskbookLabel) . ' Update ' . $step->reviewStepName . ' (' . $result . ')');
        $manager = $step->reviewUser->one();
        $package = $step->owner;
        $variables = [
            'step'          => $step,
            'package'       => $package,
            'moduleGroup'   => $package->moduleGroup,
            'user'          => $package->author,
            'manager'       => $manager,
            'result'        => $result
        ];
        $template = $this->getNotifySetting('stepUpdate', "Status update for {{ moduleGroup.title }}: result is {{ result }}. {{ step.reviewComment }}");
        $message = Craft::$app->view->renderString($template, $variables);
        $this->notify($user->email, $subject, $message);
    }

    /**
     * @param Entry $resultEntry
     * @return null
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\SyntaxError
     */
    function sendCycleStart(Entry $resultEntry)
    {
        ## notification is disabled
        if (!$this->isEnabled('cycleStart')) {
            return;
        }
        $moduleEntry = $resultEntry->resultModule->one();
        if (!$moduleEntry || $moduleEntry->type != 'cpd') {
            return null;
        }
        $cycle = CycleHelper::getModuleCurrentCycle($moduleEntry);

        if (!$cycle->startsToday()) {
            return null;
        }

        $subject = $this->getNotifySetting('subjectCycleStart', 'CPD Cycle Start');
        $variables = [
            'module'    => $moduleEntry,
            'cycle'     => $cycle
        ];
        $template = $this->getNotifySetting('cycleStart', "{{ module.title }} {{ cycle.name }} starts today.");
        $message = Craft::$app->view->renderString($template, $variables);
        $this->notify($resultEntry->author->email, $subject, $message);
    }

    /**
     * @param Entry $resultEntry
     * @param Entry $moduleEntry
     * @param CyclePeriod $cycle
     * @return null
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\SyntaxError
     */
    function sendCycleEnd(Entry $resultEntry, $moduleEntry, $cycle)
    {
        ## notification is disabled
        if (!$this->isEnabled('cycleEnd')) {
            return;
        }
        if ($moduleEntry->type != 'cpd') {
            return null;
        }

        if (!$cycle->endsYesterday()) {
            return null;
        }

        $subject = $this->getNotifySetting('subjectCycleEnd', 'CPD Cycle End');
        $variables = [
            'module'    => $moduleEntry,
            'result'    => $resultEntry,
            'cycle'     => $cycle
        ];
        $template = $this->getNotifySetting('cycleStart', "{{ module.title }} {{ cycle.name }} has ended.");
        $message = Craft::$app->view->renderString($template, $variables);
        $this->notify($resultEntry->author->email, $subject, $message);
    }

    /**
     * @param Entry $resultEntry
     * @return null
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\SyntaxError
     */
    function sendCycleComplete(Entry $resultEntry)
    {
        ## notification is disabled
        if (!$this->isEnabled('cycleComplete')) {
            return;
        }
        if ($resultEntry->resultStatus != 'complete') {
            return null;
        }
        $moduleEntry = $resultEntry->resultModule->one();
        if (!$moduleEntry || $moduleEntry->type != 'cpd') {
            return null;
        }
        $cycle = CycleHelper::getModuleCurrentCycle($moduleEntry);

        $subject = $this->getNotifySetting('subjectCycleComplete', 'CPD Cycle Complete');
        $variables = [
            'module'    => $moduleEntry,
            'result'    => $resultEntry,
            'cycle'     => $cycle
        ];
        $template = $this->getNotifySetting('cycleComplete', "{{ module.title }} {{ cycle.name }} has been completed.");
        $message = Craft::$app->view->renderString($template, $variables);
        $this->notify($resultEntry->author->email, $subject, $message);
    }

    /**
     * @param Entry $resultEntry
     * @return null
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\SyntaxError
     */
    function sendCycleReminder(Entry $resultEntry)
    {
        ## notification is disabled
        if (!$this->isEnabled('cycleReminder')) {
            return;
        }
        if ($resultEntry->resultStatus == 'complete') {
            return null;
        }
        $moduleEntry = $resultEntry->resultModule->one();
        if (!$moduleEntry || $moduleEntry->type != 'cpd') {
            return null;
        }
        $cycle = CycleHelper::getModuleCurrentCycle($moduleEntry);

        $subject = $this->getNotifySetting('subjectCycleReminder', 'CPD Cycle Reminder');
        $variables = [
            'module'    => $moduleEntry,
            'result'    => $resultEntry,
            'remaining' => Lantra::$app->results->remaining($resultEntry),
            'cycle'     => $cycle
        ];
        $template = $this->getNotifySetting('cycleReminder', "{{ module.title }} {{ cycle.name }} {{ remaining.text }}");
        $message = Craft::$app->view->renderString($template, $variables);
        $this->notify($resultEntry->author->email, $subject, $message);
    }

    /**
     * @param $expiryDate
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\SyntaxError
     */
    function sendSchemeExpiry($expiryDate)
    {
        ## notification is disabled
        if (!$this->isEnabled('schemeExpiry')) {
            return;
        }
        ## send scheme managers remaining scheme licences
        $criteria = User::find();
        $criteria->groupId = 1;
        $criteria->limit = null;
        $subject = $this->getNotifySetting('subjectSchemeExpiry', 'Scheme Expiry Date');
        $variables = ['expiryDate' => $expiryDate];
        $template = $this->getNotifySetting('schemeExpiry', "Your scheme expires on  {{ expiryDate|date('d-m-Y') }}.");
        $message = Craft::$app->view->renderString($template, $variables);
        foreach ($criteria->all() as $manager) {
            $this->notify($manager->email, $subject, $message);
        }
    }
    /**
     * Notify users of user expiry
     *
     * @param $expiryDate
     * @throws mixed
     */
    function sendUserExpiry($expiryDate)
    {
        ## notification is disabled
        if (!$this->isEnabled('userExpiry')) {
            return;
        }
       $criteria = Lantra::$app->users->getExpiringUsers($expiryDate);
       if ($criteria->count()) {
           $subject = $this->getNotifySetting('subjectUserExpiry', 'User Expiry Date');
           foreach ($criteria->all() as $user) {
               $variables = ['user' => $user];
               $template = $this->getNotifySetting('userExpiry', "Your individual licence expires on {{ user.userExpiryDate|date('d-m-Y') }}.");
               $message = Craft::$app->view->renderString($template, $variables);
               $this->notify($user->email, $subject, $message);
           }
       }
    }

    /**
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\SyntaxError
     */
    function sendLicencesRemaining()
    {
        ## notification is disabled
        if (!$this->isEnabled('licencesRemaining')) {
            return;
        }
        ## send scheme managers remaining scheme licences
        $criteria = User::find();
        $criteria->groupId = 1;
        $criteria->limit = null;
        $subject = $this->getNotifySetting('subjectLicencesRemaining', 'Licences Remaining');
        foreach ($criteria->all() as $manager) {
            $remainingLicences = Lantra::$app->licences->getSchemeLicences();
            if ($remainingLicences <= 10) {
                $variables = ['title' =>  Craft::$app->config->general->siteName, 'licences' => Lantra::$app->licences->getSchemeLicences()];
                $template = $this->getNotifySetting('licencesRemaining', "{{ title }} has {{ licences}} remaining.");
                $message = Craft::$app->view->renderString($template, $variables);
                $this->notify($manager->email, $subject, $message);
            }
        }
        ## send company managers remaining company licences
        $criteria = Entry::find();
        $criteria->section = 'companies';
        $criteria->limit = null;
        foreach ($criteria->all() as $company) {
            $remainingLicences = $company->companyRemainingLicences;
            if ($remainingLicences <= 10 && $company->companyPrimaryManagers->count()) {
                $emails = [];
                foreach($company->companyPrimaryManagers as $primaryManager) {
                    $emails[] = $primaryManager->email;
                }
                $variables = ['title' =>  $company->title, 'licences' => $remainingLicences];
                $template = $this->getNotifySetting('licencesRemaining', "{{ title }} has {{ licences}} remaining.");
                $message = Craft::$app->view->renderString($template, $variables);
                $this->notify($emails, $subject, $message);
            }
        }
    }

    /**
     * @param Entry $entry
     * @param $comment
     * @param $userId
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\SyntaxError
     * @throws \yii\base\InvalidConfigException
     */
    function sendCommentUpdate(Entry $entry, $comment, $userId)
    {
        ## notification is disabled
        if (!$this->isEnabled('comment')) {
            return;
        }
        $user = Craft::$app->users->getUserById($userId);
        $variables = ['entry' => $entry, 'user' => $user, 'comment' => $comment];
        $subject = $this->getNotifySetting('subjectComment', 'New Comment');
        $template = $this->getNotifySetting('comment', "{{ entry.title }} - {{ user.fullName}}: {{ comment }}");
        $message = Craft::$app->view->renderString($template, $variables);
        ## manager commenting - notify user
        if ($userId != $entry->authorId) {
            $this->notify($entry->getAuthor()->email, $subject, $message);
        }
        ## user commenting - notify managers
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
    function sendModuleResult(Entry $entry)
    {
        ## ignore endorsement notifications in CP
        if (Craft::$app->request->isCpRequest){
            return;
        }
        ## notification is disabled
        if (!$this->isEnabled('moduleResult')) {
            return;
        }
        $moduleEntry = $entry->resultModule->one();
        $user = $entry->getAuthor();
        $subject = $this->getNotifySetting('subjectModuleResult', 'Module Completed');
        $variables = ['entry' => $moduleEntry, 'user' => $user];
        $template = $this->getNotifySetting('moduleResult', "{{ user.fullName}} has completed {{ entry.title }}.");
        $message = Craft::$app->view->renderString($template, $variables);

        ## send the emails to managers
        $this->notify($user->email, $subject, $message);
        $this->notifyManagers($user, $subject, $message);
    }

    /**
     * @param Entry $resultEntry
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\SyntaxError
     * @throws \yii\base\InvalidConfigException
     */
    function sendManagerBlockedResult(Entry $resultEntry)
    {
        ## notification is disabled
        if (!$this->isEnabled('blockedResult')) {
            return;
        }
        $unitEntry = $resultEntry->resultUnit->one();
        $user = $resultEntry->getAuthor();
        $subject = $this->getNotifySetting('subjectBlockedResult', 'Result Blocked');
        $variables = ['entry' => $unitEntry, 'user' => $user];
        $template = $this->getNotifySetting('blockedResult', "{{ user.fullName}} has run out of attempts for unit {{ entry.title }} and the result is blocked.");
        $message = Craft::$app->view->renderString($template, $variables);
        ## send the emails to managers
        $this->notifyManagers($user, $subject, $message);
    }

    /**
     *
     */
    function sendResultExpiry()
    {
        $this->executeResultExpiry('One');
        $this->executeResultExpiry('Two');
    }

    /**
     * @param string $number
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\SyntaxError
     * @throws \yii\base\InvalidConfigException
     */
    private function executeResultExpiry($number = 'One')
    {
        ## notification is disabled
        if (!$this->isEnabled('resultExpiry' . $number)) {
            return;
        }
        $when = $this->getNotifySetting('resultExpiryWhen' . $number, '-');
        $days = (int) $this->getNotifySetting('resultExpiryDays' . $number, 0);

        $start = new \DateTime();
        if ($days) {
            $start->modify($when . $days . ' days');
        }
        $start->setTime(00, 00, 00);

        $end = new \DateTime($start->format('Y-m-d'));
        $end->modify('+ 1 day');

        $startAtom = $start->format(\DateTime::ATOM);
        $endAtom = $end->format(\DateTime::ATOM);

        $resultEntries = Entry::find()
            ->anyStatus()
            ->expiryDate(['and', ">= $startAtom", "< $endAtom"])
            ->all();

        foreach ($resultEntries as $resultEntry) {
            $user = $resultEntry->getAuthor();
            $subject = $this->getNotifySetting('subjectResultExpiry' . $number, 'Result Expiry');
            $variables = ['entry' => $resultEntry, 'user' => $user];
            $template = $this->getNotifySetting('resultExpiry' . $number, "{{ entry.title}} " . ($when == '-' ? 'expired' : 'expires'). " on {{ entry.expiryDate|date('d-m-Y') }}.");
            $message = Craft::$app->view->renderString($template, $variables);
            $this->notify($user->email, $subject, $message);
        }
    }

    /**
     * @param Entry $resultEntry
     * @param int $level
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\SyntaxError
     * @throws \yii\base\InvalidConfigException
     */
    function sendManagerEndorsementResult(Entry $resultEntry, $level = 1)
    {
        ## ignore endorsement notifications in CP
        if (Craft::$app->request->isCpRequest){
            return;
        }
        ## notification is disabled
        if (!$this->isEnabled('endorsementResult')) {
            return;
        }
        $user = $resultEntry->getAuthor();
        $subject = $this->getNotifySetting('subjectEndorsementResult', 'Endorsement Required');
        $variables = ['entry' => $resultEntry, 'user' => $user];
        $template = $this->getNotifySetting('endorsementResult', "{{ user.fullName}} has submitted a result {{ entry.title }}.");
        $message = Craft::$app->view->renderString($template, $variables);
        ## send the emails to managers
        $manager = Lantra::$app->users->getUserManagerByLevel($user, $level);
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
    function sendManagerSummary(User $manager, $days = 7)
    {
        ## notification is disabled
        if (!$this->isEnabled('managerSummary')) {
            return;
        }
        $subject = $this->getNotifySetting('subjectManagerSummary', 'Manager Summary');
        $criteria = Lantra::$app->results->getManagerModuleExpiringResults($manager->id, $days, null);
        if ($criteria && $criteria->count()) {
            $message = "The following user results expire in the next " . $days . " days:\n\n";
            foreach ($criteria->all() as $result) {
                $moduleEntry = $result->resultModule->one();
                $message .= "User: " . $result->author->getFullName() . "\n\n";
                $message .= "Team: " . $result->author->userTeam->one()->title . "\n\n";
                $message .= "Module: " . ($moduleEntry ? $moduleEntry->title : '~'). "\n\n";
                $message .= "Expires: " . $result->expiryDate . "\n\n";
                $message .= "\n##########################\n\n";
            }
        }
        else {
            $message = "There are no expiring results in the next " . $days . " days:\n\n";
        }

        $criteria = Lantra::$app->results->getManagerModuleCompletedResults($manager->id, $days, null);
        if ($criteria && $criteria->count()) {
            $message .= "The following modules have been completed in the past " . $days . " days:\n\n";
            foreach ($criteria->all() as $result) {
                $moduleEntry = $result->resultModule->one();
                $message .= "User: " . $result->author->getFullName() . "\n\n";
                $message .= "Team: " . ($result->author->userTeam->count() ? $result->author->userTeam->one()->title : '~') . "\n\n";
                $message .= "Module: " . ($moduleEntry ? $moduleEntry->title : '~') . "\n\n";
                $message .= "Expires: " . $result->expiryDate . "\n\n";
                $message .= "\n##########################\n\n";
            }
        }
        else {
            $message = "There are no expiring results in the next " . $days . " days:\n\n";
        }
        ## send the emails to managers
        $this->notify($manager->email, $subject, $message);
    }

    /**
     * Send a message to a user's managers
     *
     * @param $user
     * @param $subject
     * @param $message
     * @throws mixed
     */
    function notifyManagers(User $user, $subject, $message)
    {
        $managers = Lantra::$app->users->getUserMangers($user);
        if ($managers && count($managers)) {
            foreach ($managers as $manager) {
                $this->notify($manager->email, $subject, $message);
            }
        }
    }

    /** Get notification global (return default if empty)
     *
     * @param string
     * @param string
     * @return string
     */
    public function getNotifySetting($key, $default = '')
    {
        $setting = Lantra::$app->settings->getSetting('notify'.ucwords($key));
        return $setting ? $setting : $default;
    }

    /**
     * @param $subject
     * @param $body
     * @param array $attachments
     * @return mixed
     */
    function notifyAdmin($subject, $body, $attachments = [])
    {
        $adminEmail = $this->getNotifySetting('adminEmail');
        return $this->notify($adminEmail, $subject, $body, $attachments);
    }

    /**
     * Send a message to a user
     *
     * @param $toEmail
     * @param $subject
     * @param $body
     * @param $attachments
     * @return mixed
     * @throws mixed
     */
    function notify($toEmail, $subject, $body, $attachments = [])
    {
        ## all notifications are disabled
        if (Lantra::$app->settings->getSetting('disableAllNotifications')) {
            return;
        }

        if (!is_array($toEmail)) {
            $toEmail = [$toEmail];
        }
        ## all notifications sent to test email address
        $server = getenv('ENVIRONMENT');
        if ($server != 'prod') {
            $subject = '[' . $server . '] ' . $subject;
            $body .= "\n\n\nNotification for: " . implode(', ', $toEmail);
            $schemeTestEmails = explode(',', Lantra::$app->settings->getSetting('schemeTestEmailAddress'));
            $siteEmailAddress = Craft::$app->getProjectConfig()->get('email', 'emailAddress');
            $toEmail = count($schemeTestEmails) ? $schemeTestEmails : [$siteEmailAddress];
        }
        ## add notification footer
        $body .= $this->getNotifySetting('footer');
        $body = $this->renderTemplate('sp/emails/default', ['message' => $body]);

        $message = (new Message())
            ->setSubject($subject)
            ->setTextBody($body);

        foreach($toEmail as $address) {
            $message->setTo(trim($address));
            try {
                if (count($attachments)) {
                    foreach($attachments as $attachment) {
                        $message->attach($attachment['path'], [
                            'fileName'      => $attachment['filename'],
                            'contentType'   => 'base64',
                        ]);
                    }
                }
                if ($message->send()) {
                    Craft::info('notify(' . $address . ') ' . $subject . ' sent.', __METHOD__);
                }
                else {
                    Craft::error('notify(' . $address . ') ' . $subject . ' not sent!' , __METHOD__);
                }
            } catch (\Exception $e) {
                $this->notifyAdmin('Notify error (' . $address. ')', $e->getMessage());
                Craft::error('notify(' .  $address. ') ' . $e->getMessage(),__METHOD__);
                return false;
            }
        }
        return true;
    }

    /**
     * @param $template
     * @param $variables
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @throws \yii\base\Exception
     */
    private function renderTemplate($template, $variables)
    {
        $oldMode = Craft::$app->view->getTemplateMode();
        Craft::$app->view->setTemplateMode(View::TEMPLATE_MODE_CP);
        $html = Craft::$app->view->renderTemplate($template, $variables);
        Craft::$app->view->setTemplateMode($oldMode);
        return $html;
    }

    /**
     * @param $name
     * @return mixed|null
     */
    private function isEnabled($name)
    {
        return (bool) Lantra::$app->settings->getSetting('notifyEnable' . ucwords($name));
    }
}
