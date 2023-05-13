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
    static function reportHeaderStandardProducts(Entry $reportEntry)
    {
        return [
            'Type',
            'Reference',
            'Company',
            'Result Date',
            'Expiry Date',
            'Expiry Status'
        ];
    }

    /**
     * @param $date
     * @return string
     */
    public static function expiryStatus($date)
    {
        $now = new \DateTime();
        $d30 = new \DateTime("+30 days");
        $d60 = new \DateTime("+60 days");

        if ($date < $now) {
            $expiryStatus = 'Expired';
        }
        elseif ($date > $now && $date < $d30) {
            $expiryStatus = 'Expiring in 30 days';
        }
        elseif ($date > $now && $date < $d60) {
            $expiryStatus = 'Expiring in 60 days';
        }
        else {
            $expiryStatus = 'Valid';
        }

        return $expiryStatus;
    }

    /**
     * @param User $user
     * @param bool $html
     * @return array
     */
    public static function reportRowStandardProducts(Entry $reportEntry, Entry $productEntry, $html = true)
    {
        $dateFormat = LantraHelper::setting('themeDateFormat', 'd-m-Y');
        $productType = $productEntry->productType->one();
        $productCompany = $productEntry->productCompany->one();

        return [
            $productType->title,
            $productEntry->title,
            $productCompany ? $productCompany->title : '~',
            $productEntry->productResultStartDate ? $productEntry->productResultStartDate->format($dateFormat) : '~',
            $productEntry->productResultExpiryDate ? $productEntry->productResultExpiryDate->format($dateFormat) : '~',
            self::expiryStatus($productEntry->productResultExpiryDate)
        ];
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
    static function reportHeaderStandardAnnualResults(Entry $reportEntry)
    {
        return [
            'User ID',
            'User Name',
            'Company ID',
            'Company Label',
            'User Job Title',
            'Start Date',
            'Total Job Role Units',
            'Total Endorsed Units',
            'Total Unexpired Units',
            'Total Required Units',
            'Total Annual Units (12 months)'
        ];
    }

    /**
     * @param Entry $reportEntry
     * @param User $user
     * @param bool $html
     * @return array
     */
    public static function reportRowStandardAnnualResults(Entry $reportEntry, User $user, $html = true)
    {
        $dateFormat = LantraHelper::setting('themeDateFormat', 'd-m-Y');

        if (null == $role = $user->userRole->one()) {
            return [];
        }
        $company = Lantra::$app->users->userCompany($user);
        $roleUnitIds = $role && $role->linkedData ? json_decode($role->linkedData) : [];
        $totalRole = count($roleUnitIds);

        $criteria = Lantra::$app->results->getUserUnitResults($user->id, $roleUnitIds);
        $criteria->status(['live', 'pending', 'expired']);
        $criteria->resultStatus = 'endorsed';
        $totalCompleted = $criteria->count();

        $criteria = Lantra::$app->results->getUserUnitResults($user->id, $roleUnitIds);
        $criteria->resultStatus = 'endorsed';
        $totalUnexpired = $criteria->count();

        $criteria = Lantra::$app->results->getUserUnitResults($user->id, $roleUnitIds);
        $criteria->resultStatus = 'endorsed';
        $date = new \DateTime();
        $date->modify('-1 year');
        $criteria->resultFinishDate = '>= '. $date->format('Y-m-d');
        $totalAnnual = $criteria->count();

        $items = [
            $user->id,
            $html ? '<a href="/cpd/' . $user->id . '">' . $user->fullName . ' </a>' : $user->fullName,
            $company ? $company->id : 'unknown',
            $company ? $company->companyLabel : 'unknown',
            $role ? $role->title : 'unknown',
            $user->userStartDate ? $user->userStartDate->format($dateFormat) : '~',
            $totalRole,
            $totalCompleted,
            $totalUnexpired,
            $totalRole - $totalUnexpired,
            $totalAnnual
        ];

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
            'Unendorsed Hours',
            'Target Points',
            'Result Points',
            'Unendorsed Points',
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
        if (is_iterable($resultEntry->resultComponentResults)) {
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
        }

        $items = [
            $user->id,
            $userCompany ? $userCompany->title : '~',
            $user->fullName,
            $resultModule->id,
            $resultModule->title,
            $resultEntry->cycleStartDate ? $resultEntry->cycleStartDate->format($dateFormat) : '~',
            $resultEntry->cycleFinishDate ? $resultEntry->cycleFinishDate->format($dateFormat) : '~',
            $resultModule->targetHours ?: 0,
            $resultEntry->resultHours ?: 0,
            $resultEntry->resultUnendorsedHours ?: 0,
            $resultModule->targetPoints ?: 0,
            $resultEntry->resultPoints ?: 0,
            $resultEntry->resultUnendorsedPoints ?: 0,
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
            'Transaction Amount',
            'Transaction Method',
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
            $paymentBlock->amount,
            $paymentBlock->method,
            $paymentBlock->paymentId,
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