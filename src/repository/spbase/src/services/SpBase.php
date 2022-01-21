<?php
/**
 * Lantra Skills Plus for Craft CMS 3.x
 *
 * @link      https://coffeebean.design
 * @copyright Copyright (c) 2020 Coffee Bean Design
 */

namespace lantra\spbase\services;

class SpBase
{
    /**
     * @var
     */
    public $client;

    /**
     *
     */
    public function __construct()
    {
        $this->client = new SpBaseClient();
    }

    /**
     *
     */
    public function getSite()
    {
        $site = $this->client->getSite();
        return $site;
    }

    /**
     *
     */
    public function log($element, $action, $comment = '')
    {
        $log = [
            'action' => $action,
            'comment' => $comment
        ];
        return $this->client->saveMeta($element, ['log' => $log]);
    }

    /**
     * @param $userId
     * @return bool
     */
    public function validateLicence($userId): bool
    {
        $licence = $this->getLicence($userId);
        return $licence->valid;
    }

    /**
     * @param $userId
     * @param bool $create
     * @return \lantra\spbase\models\Licence
     */
    public function getLicence($userId, $create = true)
    {
        $licence = $this->client->getLicence($userId);

        ## create new licence
        if ($create && !$licence->valid) {
            $this->client->saveLicence(null, ['userId' => $userId]);
            $licence = $this->getLicence($userId, false);
            $this->log($licence,'created');
            return $licence;
        }

        return $licence;
    }

    /**
     * @param $userId
     */
    public function cancelLicence($userId)
    {
        $licence = $this->client->getLicence($userId);

        if ($licence->id) {
            $this->client->saveLicence($licence->id, ['enabled' => false]);
            $this->log($licence, 'cancelled');
        }
    }

    /**
     * @param $userId
     * @param array $data
     */
    public function updateLicence($userId, $data = [])
    {
        $licence = $this->client->getLicence($userId);

        if ($licence->valid) {
            $this->client->saveLicence($licence->id, $data);
        }
    }

    /**
     * @param $userId
     * @return array
     */
    public function getPayments($userId)
    {
        $licence = $this->getLicence($userId);

        return $licence->valid ? $licence->payments : [];
    }

    /**
     *
     */
    public function getCompany($companyId, $create = true)
    {
        $company = $this->client->getCompany($companyId);

        if (!$company) {
            ## create new company
            $this->client->saveCompany(null, ['companyId' => $companyId]);
            return $this->getCompany($companyId, false);
        }

        return $company;
    }

    /**
     *
     */
    public function updateCompany($companyId, $data = [])
    {
        $company = $this->client->getLicence($companyId);
        if ($company->valid) {
            $this->client->saveCompany($company->id, $data);
        }
    }
}