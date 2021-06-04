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
use craft\elements\Entry;
use craft\elements\User;

class ReportHelper
{
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
        return implode(',', $items);
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
        return implode(',', $items);

    }

    /**
     * @param Entry $reportEntry
     * @return array
     */
    static function reportHeaderStandardUsers(Entry $reportEntry)
    {
        return [
            'Company',
            'User Name',
            'User ID',
            'Email',
            'Phone',
            'Role',
            'Start Date',
            'Renewal Date'
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
            $userCompany ? $userCompany->title : '~',
            $user->fullName,
            $user->id,
            $html ? '<a href="mailto:' . $user->email . '">' . $user->email . '</a>' : $user->email,
            $user->userTelephone ? $user->userTelephone : '~',
            LantraHelper::userRoles($user),
            $user->userStartDate ? $user->userStartDate->format($dateFormat) : '~',
            $user->userExpiryDate ? $user->userExpiryDate->format($dateFormat) : '~'
        ];
    }

    /**
     * @param Entry $reportEntry
     * @return array
     */
    static function reportHeaderStandardResults(Entry $reportEntry)
    {
        $items = [
            'Company',
            'User Name',
            'User ID',
            'Result Title',
        ];
        if ($reportEntry->reportResultStandardType == 'endorsed') {
            $items = array_merge($items, ['Endorsed Date', 'Endorsed User']);
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
        $items = [
            $userCompany ? $userCompany->title : '~',
            $user->fullName,
            $user->id,
            $resultEntry->title
        ];

        if ($reportEntry->reportResultStandardType == 'endorsed') {

            $endorsedUser = $resultEntry->resultEndorsedUser->count() ? $resultEntry->resultEndorsedUser->one() : null;
            $items[] = $endorsedUser ? $resultEntry->resultEndorsedDate->format($dateFormat) : '~';
            $items[] = $endorsedUser ? $endorsedUser->fullName : '~';
        }

        return $items;
    }

    /**
     * @param Entry $reportEntry
     */
    public static function reportHeaderStandardCpd(Entry $reportEntry)
    {

    }

    /**
     * @param Entry $resultEntry
     * @param bool $html
     * @return array
     */
    public static function reportRowStandardCpd(Entry $resultEntry, $html = true)
    {
        return [];
    }
}