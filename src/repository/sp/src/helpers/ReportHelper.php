<?php
/**
 * Lantra Skills Plus for Craft CMS 3.x
 *
 * @link      https://coffeebean.design
 * @copyright Copyright (c) 2020 Coffee Bean Design
 */

namespace lantra\sp\helpers;

use lantra\sp\Plugin as Lantra;
use craft\base\Element;
use craft\elements\MatrixBlock;
use craft\elements\Entry;
use craft\elements\User;

class ReportHelper
{
    /**
     * @param $reportType
     * @param null $key
     * @return null
     */
    public static function reportTypeSetting($reportType, $key = null)
    {
        $reports = Lantra::$app->settings->getSetting('reports');
        foreach($reports as $k => $settings) {
            if ($reportType == $k) {
                return $key ? $settings[$key] : $settings;
            }
        }
        return null;
    }

    /**
     * @param $reportEntry
     * @param bool $html
     * @return string
     */
    public static function reportHeader(Entry $reportEntry, $html = true)
    {
        $method = 'reportHeader' . ucwords($reportEntry->reportType);
        $items = self::$method($reportEntry, $html);

        if ($html) {
            return '<th>' . implode('</th><th>', $items) . '</th>';
        }
        return $items;
    }

    /**
     * @param $reportEntry
     * @param $row
     * @param bool $html
     * @return mixed
     */
    static function reportRow(Entry $reportEntry, Element $row, $html = true)
    {
        $method = 'reportRow' . ucwords($reportEntry->reportType);
        $items = self::$method($reportEntry, $row, $html);

        if ($html) {
            return '<td>' . implode('</td><td>', $items) . '</td>';
        }
        return $items;

    }

    /**
     * @param Entry $reportEntry
     * @return array
     */
    static function reportHeaderStandardUsers(Entry $reportEntry)
    {
        return [
            'User ID',
            'Company',
            'User Name',
            'Email',
            'Phone',
            'Role',
            'Start Date',
            'Renewal Date',
            'Last Login Date'
        ];
    }
    /**
     * @param User $user
     * @param bool $html
     * @return array
     */
    public static function reportRowStandardUsers(Entry $reportEntry, User $user, $html = true)
    {
        $dateFormat = LantraHelper::setting('themeDateFormat', 'd-m-Y');
        $userCompany = Lantra::$app->users->userCompany($user);
        return [
            $user->id,
            $userCompany ? $userCompany->title : '~',
            $user->fullName,
            $html ? '<a href="mailto:' . $user->email . '">' . $user->email . '</a>' : $user->email,
            $user->userTelephone ? $user->userTelephone : '~',
            LantraHelper::userRoles($user),
            $user->userStartDate ? $user->userStartDate->format($dateFormat) : '~',
            $user->userExpiryDate ? $user->userExpiryDate->format($dateFormat) : '~',
            $user->lastLoginDate ? $user->lastLoginDate->format($dateFormat) : '~'
        ];
    }

    /**
     * @param Entry $reportEntry
     * @return array
     */
    static function reportHeaderStandardResults(Entry $reportEntry)
    {
        $items = [
            'User ID',
            'Company',
            'User Name',
            'Unit ID',
            'Unit Title',
        ];
        if ($reportEntry->reportResultStandardType == 'endorsed') {
            $items = array_merge($items, ['Endorsed Date', 'Endorsed User', 'Expiry Date']);
        }
        else {
            $items = array_merge($items, ['Expiry Date']);
        }
        return $items;
    }

    /**
     * @param Entry $reportEntry
     * @param Entry $resultEntry
     * @param bool $html
     * @return array
     */
    public static function reportRowStandardResults(Entry $reportEntry, Entry $resultEntry, $html = true)
    {
        $dateFormat = LantraHelper::setting('themeDateFormat', 'd-m-Y');
        $user = $resultEntry->author;
        $userCompany = Lantra::$app->users->userCompany($user);
        $resultUnit = $resultEntry->resultUnit->one();
        $items = [
            $user->id,
            $userCompany ? $userCompany->title : '~',
            $user->fullName,
            $resultUnit->id,
            $resultUnit->title
        ];

        if ($reportEntry->reportResultStandardType == 'endorsed')
        {
            $endorsedUser = $resultEntry->resultEndorsedUser->count() ? $resultEntry->resultEndorsedUser->one() : null;
            $items[] = $endorsedUser ? $resultEntry->resultEndorsedDate->format($dateFormat) : '~';
            $items[] = $endorsedUser ? $endorsedUser->fullName : '~';
            $items[] = $resultEntry->expiryDate ? $resultEntry->expiryDate->format($dateFormat) : '~';
        }
        else {
            $items[] = $resultEntry->expiryDate->format($dateFormat);
        }

        return $items;
    }

    /**
     * @param Entry $reportEntry
     * @return array
     */
    public static function reportHeaderStandardCpd(Entry $reportEntry)
    {
        return [
            'User ID',
            'Company',
            'User Name',
            'Module ID',
            'Module Title',
            'Cycle Start',
            'Cycle Finish',
            'Target Hours',
            'Result Hours',
            'Target Points',
            'Result Points',
            'Module Components'
        ];
    }

    /**
     * @param Entry $reportEntry
     * @param Entry $resultEntry
     * @param bool $html
     * @return array
     */
    public static function reportRowStandardCpd(Entry $reportEntry, Entry $resultEntry, $html = true)
    {
        $dateFormat = LantraHelper::setting('themeDateFormat', 'd-m-Y');
        $user = $resultEntry->author;
        $userCompany = Lantra::$app->users->userCompany($user);
        $resultModule = $resultEntry->resultModule->one();

        $componentText = '';
        foreach ($resultEntry->resultComponentResults as $componentRow) {
            $componentText .= $componentRow['title'] . ' ';
            if ($componentRow['targetHours']) {
                $componentText .= $componentRow['endorsedHours'] . '/' . $componentRow['targetHours'] . ' hours ';
            }
            if ($componentRow['targetHours'] && $componentRow['targetPoints']) {
                $componentText .= " ";
            }
            if ($componentRow['targetPoints']) {
                $componentText .= $componentRow['endorsedPoints'] . '/' . $componentRow['targetPoints'] . ' points ';
            }
        }

        $items = [
            $user->id,
            $userCompany ? $userCompany->title : '~',
            $user->fullName,
            $resultModule->id,
            $resultModule->title,
            $resultModule->cycleStartDate ? $resultModule->cycleStartDate->format($dateFormat) : '~',
            $resultModule->cycleFinishDate ? $resultModule->cycleFinishDate->format($dateFormat) : '~',
            $resultModule->targetHours ? $resultModule->targetHours : 0,
            $resultEntry->resultHours ? $resultEntry->resultHours : 0,
            $resultModule->targetPoints ? $resultModule->targetPoints : 0,
            $resultEntry->resultPoints ? $resultEntry->resultPoints : 0,
            $componentText
        ];

        return $items;
    }

    /**
     * @param Entry $reportEntry
     * @return array
     */
    public static function reportHeaderStandardPayments(Entry $reportEntry)
    {
        return [
            'User ID',
            'Company',
            'User Name',
            'Email',
            'Transaction Date',
            'Transaction Total',
            'Transaction ID'
        ];
    }

    /**
     * @param Entry $reportEntry
     * @param MatrixBlock $paymentBlock
     * @param bool $html
     * @return array
     */
    public static function reportRowStandardPayments(Entry $reportEntry, MatrixBlock $paymentBlock, $html = true)
    {
        $dateFormat = LantraHelper::setting('themeDateFormat', 'd-m-Y');
        if (get_class($paymentBlock->owner) == 'craft\elements\User') {
            $user = $paymentBlock->owner;
        }
        else {
            $user = $paymentBlock->owner->author;
        }
        $userCompany = Lantra::$app->users->userCompany($user);
        $items = [
            $user->id,
            $userCompany ? $userCompany->title : '~',
            $user->fullName,
            $user->email,
            $paymentBlock->dateCreated->format($dateFormat),
            $paymentBlock->mc_gross,
            $paymentBlock->txn_id
        ];

        return $items;
    }

    /**
     * @param $reportEntry
     * @return bool
     */
    public static function isStandardReport($reportEntry)
    {
        return substr($reportEntry->reportType, 0, 8) === 'standard';
    }
}