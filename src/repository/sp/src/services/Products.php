<?php
/**
 * Lantra Skills Plus for Craft CMS 3.x
 *
 * @link      https:##coffeebean.design
 * @copyright Copyright (c) 2020 Coffee Bean Design
 */

namespace lantra\sp\services;

use Craft;
use craft\base\Component;
use craft\elements\Entry;
use craft\elements\User;
use lantra\sp\Plugin as Lantra;
use lantra\sp\helpers\LantraHelper;


class Products extends Component
{
    /**
     * @param null $userId
     * @param string $days
     * @param int $limit
     * @return \craft\elements\db\ElementQueryInterface|\craft\elements\db\EntryQuery
     */
    public function getProductResults($userId = null, $days = 'all', $limit = 10, $filter = [])
    {
        $manager = is_null($userId) ? Craft::$app->getUser() : Craft::$app->users->getUserById($userId);

        ## @todo should this be in reports service?
        if ($filter['reportIncludeHierarchy']) {
            $filter['reportCompanies'] = Lantra::$app->structure->appendCompanyDescendants($filter['reportCompanies']);
        }

        $criteria = $this->productCriteria('', $limit, 'productResultExpiryDate desc', $filter['reportCompanies'], $manager);

        if ($days == 'all') {
            $expiryDate = '<' . time();
        }
        else {
            $expiryDate = '<' . (time() + ($days * 86400));
            if (!$filter['reportIncludeExpired']) {
                $expiryDate = 'and, >' . time() . ', ' . $expiryDate;
            }
        }

        $criteria->productResultExpiryDate = $expiryDate;
        return $criteria;
    }

    /**
     * @param null $user
     * @param null $limit
     * @param string $order
     * @return array|\craft\base\ElementInterface[]|Entry[]
     */
    public function getUserProducts($user = null, $limit = null, $order = 'title')
    {
        $user = LantraHelper::getUser($user);
        return $this->productCriteria('', $limit, $order, null, $user)->all();
    }

    /**
     * @param string $search
     * @param int $limit
     * @param string $order
     * @param null $companyId
     * @param null $user
     * @return \craft\elements\db\ElementQueryInterface|\craft\elements\db\EntryQuery
     */
    public function productCriteria($search = '', $limit = 25, $order = 'title', $companyId = null, $user = null)
    {
        $user = LantraHelper::getUser($user);
        $isAdmin = $user->admin || $user->isInGroup('schemeManagers');

        $criteria = Entry::find();
        $criteria->section = 'products';
        $criteria->limit = $limit;
        $criteria->orderBy = $order;

        if ($search) {
            $criteria->search = 'title:' . $search;
        }

        if ($companyId) {
            $criteria->relatedTo([
                'targetElement' => is_array($companyId) ? $companyId : [$companyId],
                'field' => 'productCompany'
            ]);
        }
        ## admins see all company products by default
        elseif (!$isAdmin) {
            $criteria->relatedTo([
                'targetElement' => Lantra::$app->users->getCompanyManagerCompanyIds($user),
                'field' => 'productCompany'
            ]);
        }

        return $criteria;
    }
}