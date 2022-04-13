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
     * @param string $search
     * @param int $limit
     * @param string $order
     * @param null $companyId
     * @param null $user
     * @return \craft\elements\db\ElementQueryInterface|\craft\elements\db\EntryQuery
     */
    public function productCriteria($search = '', $limit = 25, $order = 'title', $companyId = null, $user = null)
    {
        $user = Craft::$app->getUser();
        $isAdmin = $user->getIsAdmin() || $user->getIdentity()->isInGroup('schemeManagers');

        $criteria = Entry::find();
        $criteria->section = 'products';
        $criteria->limit = $limit;
        $criteria->orderBy = $order;

        if ($search) {
            $criteria->search = 'title:' . $search;
        }

        if ($companyId) {
            $criteria->relatedTo([
                'targetElement' => [$companyId],
                'field' => 'productCompany'
            ]);
        }
        ## admins see all company products by default
        elseif (!$isAdmin) {
            $criteria->relatedTo([
                'targetElement' => Lantra::$app->users->getCompanyManagerCompanyIds($user->getIdentity()),
                'field' => 'productCompany'
            ]);
        }

        return $criteria;
    }
}