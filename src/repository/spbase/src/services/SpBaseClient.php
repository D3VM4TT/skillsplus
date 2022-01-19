<?php
/**
 * Lantra Skills Plus for Craft CMS 3.x
 *
 * @link      https://coffeebean.design
 * @copyright Copyright (c) 2020 Coffee Bean Design
 */

namespace lantra\spbase\services;

use Craft;
use lantra\sp\Plugin as Lantra;

use lantra\spbase\services\gql\Client;
use lantra\spbase\models\Licence;
use lantra\spbase\models\Company;
use lantra\spbase\models\Site;

class SpBaseClient
{
    /**
     * @var $gql
     */
    protected $gql;

    /**
     * @var
     */
    protected $subdomain;

    /**
     * @var
     */
    protected $siteId;

    /**
     * @var
     */
    protected $authorId;

    /**
     * @param array $config
     */
    public function __construct()
    {
        $endpoint = Craft::getAlias('@spBaseUrl');

        $this->subdomain = Craft::getAlias('@site');

        $this->gql = new Client($endpoint);
    }

    /**
     * @return Site
     * @throws gql\exceptions\GraphQLError
     * @throws gql\exceptions\GraphQLResponseError
     */
    public function getSite()
    {
        $query = 'query getSite($subdomain: [QueryArgument!]) {  
            entry (section:"sites", subdomain: $subdomain limit: 1, orderBy: "dateCreated DESC") {
                ... on sites_site_Entry {
                    __typename
                    id         
                    dateCreated @formatDateTime (format: "Y-m-d")
                    expiryDate @formatDateTime (format: "Y-m-d")
                    subdomain          
              }
            }
        }';

        $variables = [
            'subdomain' => $this->subdomain
        ];

        $response = $this->query($query, $variables, true);

        return new Site($response->entry);
    }

    /**
     * @return int
     * @throws gql\exceptions\GraphQLError
     * @throws gql\exceptions\GraphQLResponseError
     */
    public function getUserId($username)
    {
        $query = 'query getUser($username: [String!]) { 
          user (username: $username) { 
            ... on User {      
              id 
            } 
          } 
        }';

        $variables = [
            'username' => $username
        ];

        $response = $this->query($query, $variables, true);

        return (int)$response->user->id;
    }

    /**
     * @param $userId
     * @param null $entryId
     * @return bool
     * @throws \yii\db\Exception
     * @throws gql\exceptions\GraphQLError
     * @throws gql\exceptions\GraphQLResponseError
     */
    public function saveLicence($entryId = null, $data = [])
    {
        $query = 'mutation saveEntry($entryId: ID, $authorId: ID, $siteId: Int, $userId: Number) {
            save_licences_licence_Entry(id: $entryId, authorId: $authorId, relatedSite: [$siteId], userId: $userId) {
                id
            }
        }';

        $variables = [
            'siteId' => $this->getSiteId(),
            'authorId' => $this->getAuthorId(),
            'entryId' => $entryId,
            'userId' => $data['userId']
        ];

        $response = $this->query($query, $variables);

        return !$response->hasErrors();
    }

    /**
     * @param $model
     * @param $meta
     * @return bool
     * @throws gql\exceptions\GraphQLError
     * @throws gql\exceptions\GraphQLResponseError
     */
    public function saveMeta($model, $meta)
    {
        $query = 'mutation saveMeta($entryId: ID, $meta: String) {
            save_' . $model->__typename . '(id: $entryId, meta: $meta) {
                id    
            }  
        }';

        $variables = [
            'entryId' => $model->id,
            'meta' => json_encode($meta),
        ];

        $response = $this->query($query, $variables);

        return !$response->hasErrors();
    }

    /**
     * @param $userId
     * @return Licence
     * @throws \yii\db\Exception
     */
    public function getLicence($userId)
    {
        $query = 'query getLicence($siteId: [QueryArgument!], $userId: [QueryArgument!]) {  
          entry (section:"licences", userId: $userId, relatedTo: $siteId) {
              ... on licences_licence_Entry {
                  __typename
                  id
                  userId
                  valid
                  dateCreated @formatDateTime (format: "Y-m-d")
                  expiryDate @formatDateTime (format: "Y-m-d")
                  relatedSite {
                    id
                  }
                  payments {
                    ...on payments_BlockType {
                        date @formatDateTime (format: "Y-m-d")
                        amount 
                        paid
                        reference
                    }
                  }       
              }
          }
        }';

        $variables = [
            'siteId' => $this->getSiteId(),
            'userId' => $userId
        ];

        $response = $this->query($query, $variables);

        $attributes = $response->entry ?? [];

        return new Licence($attributes);
    }

    /**
     * @param $companyId
     * @return Company
     * @throws \yii\db\Exception
     */
    public function getCompany($companyId)
    {
        $query = 'query getCompany($siteId: [QueryArgument!], $companyId: [QueryArgument!]) {  
          entry (section:"companies" relatedTo: $siteId companyId: $companyId limit: 1 orderBy: "dateCreated DESC") {
              ... on companies_company_Entry {
                  __typename
                  id
                  dateCreated @formatDateTime (format: "Y-m-d")
                  expiryDate @formatDateTime (format: "Y-m-d")
                  relatedSite {
                    id
                  }
              }
          }
        }';

        $variables = [
            'siteId' => $this->getSiteId(),
            'companyId' => $companyId
        ];

        $response = $this->query($query, $variables);

        return new Company($response->entry);
    }

    /**
     * @param $userId
     * @param null $entryId
     * @return bool
     * @throws \yii\db\Exception
     * @throws gql\exceptions\GraphQLError
     * @throws gql\exceptions\GraphQLResponseError
     */
    public function saveCompany($companyId, $entryId = null)
    {
        $query = 'mutation saveEntry($entryId: ID, $authorId: ID, $siteId: Int, $companyId: Number) {
            save_companies_company_Entry(
            id: $entryId, authorId: $authorId, relatedSite: [$siteId], userId: $userId) {
                id
            }
        }';

        $variables = [
            'siteId' => $this->getSiteId(),
            'authorId' => $this->getAuthorId(),
            'entryId' => $entryId,
            'companyId' => $companyId
        ];

        $response = $this->query($query, $variables);

        return !$response->hasErrors();
    }

    /**
     * @param $query
     * @param array $variables
     * @param false $critical
     * @return gql\Response
     * @throws gql\exceptions\GraphQLError
     * @throws gql\exceptions\GraphQLResponseError
     */
    protected function query($query, $variables = [], $critical = false)
    {
        $response = $this->gql->response($query, $variables, $critical);

        return $response;
    }

    /**
     * @return mixed|null
     * @throws \yii\db\Exception
     */
    protected function getSiteId()
    {
        if (!$this->siteId) {
            ## siteId is cached for infinity
            $this->siteId = Craft::$app->cache->getOrSet('spBaseSiteId', function () {
                $site = $this->getSite();
                return $site->id;
            }, 0);
        }

        return (int)$this->siteId;
    }

    /**
     * @return mixed
     */
    protected function getAuthorId()
    {
        if (!$this->authorId) {
            ## authorId is cached for infinity
            $this->authorId = Craft::$app->cache->getOrSet('spBaseAuthorId', function () {
                return $this->getUserId('graphql');
            }, 0);
        }

        return (int)$this->authorId;
    }
}