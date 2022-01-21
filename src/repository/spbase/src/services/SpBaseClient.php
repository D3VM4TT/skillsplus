<?php
/**
 * Lantra Skills Plus for Craft CMS 3.x
 *
 * @link      https://coffeebean.design
 * @copyright Copyright (c) 2020 Coffee Bean Design
 */

namespace lantra\spbase\services;

use Craft;
use GuzzleHttp\Exception\GuzzleException;
use lantra\spbase\Module as SpBase;

use GuzzleHttp\Client as GuzzleClient;
use lantra\spbase\models\Licence;
use lantra\spbase\models\Company;
use lantra\spbase\models\Site;
use lantra\spbase\services\gql\exceptions\GraphQLResponseError;
use lantra\spbase\services\gql\Response;

class SpBaseClient
{
    /**
     * @var $endpoint
     */
    protected $endpoint;

    /**
     * @var $guzzle
     */
    protected $guzzle;

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
        $this->endpoint = Craft::getAlias('@spBaseUrl');

        $this->subdomain = Craft::getAlias('@site');

        $this->guzzle = new GuzzleClient();
    }

    /**
     * @return Site
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

        $response = $this->query($query, $variables);

        $attributes = $response->entry ?? [];

        return new Site($attributes);
    }

    /**
     * @param $username
     * @return int|mixed
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

        $response = $this->query($query, $variables);

        return $response->user ?? (int) $response->user->id;
    }

    /**
     * @param null $entryId
     * @param array $data
     * @return bool
     */
    public function saveLicence($entryId = null, $data = [])
    {
        $query = 'mutation saveEntry($entryId: ID, $authorId: ID, $siteId: Int, $userId: Number, $enabled: Boolean) {
            save_licences_licence_Entry(id: $entryId, authorId: $authorId, relatedSite: [$siteId], userId: $userId, enabled: $enabled) {
                id
            }
        }';

        $variables = [
            'siteId' => $this->getSiteId(),
            'authorId' => $this->getAuthorId(),
            'entryId' => $entryId
        ];

        $variables = array_merge($variables, $data);

        $response = $this->query($query, $variables);

        return !$response->hasErrors();
    }

    /**
     * @param $model
     * @param $meta
     * @return bool
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

        $attributes = $response->entry ?? [];

        return new Company($attributes);
    }

    /**
     * @param $companyId
     * @param null $entryId
     * @return bool
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
     * @return int
     */
    private function getSiteId()
    {
        if (!$this->siteId) {
            ## siteId is cached for infinity
            $this->siteId = Craft::$app->cache->getOrSet('spBaseSiteId', function () {
                $site = $this->getSite();
                return $site->id;
            }, 0);
        }

        if (null == $siteId = (int) $this->siteId) {
            SpBase::error('Invalid Site ID');
        }

        return $siteId;
    }

    /**
     * @return int
     */
    private function getAuthorId()
    {
        if (!$this->authorId) {
            ## authorId is cached for infinity
            $this->authorId = Craft::$app->cache->getOrSet('spBaseAuthorId', function () {
                return $this->getUserId('graphql');
            }, 0);
        }

        if (null == $authorId = (int) $this->authorId) {
            SpBase::error('Invalid GraphQL API User ID');
        }

        return $authorId;
    }

    /**
     * @param $query
     * @param array $variables
     * @param array $headers
     * @return Response
     * @throws GuzzleException
     */
    private function query($query, array $variables = [], array $headers = []): Response
    {
        $response = new Response();

        try {
            $guzzleResponse = $this->guzzle->request('POST', $this->endpoint, [
                'json' => [
                    'query' => $query,
                    'variables' => $variables
                ],
                'headers' => $headers
            ]);

            $json = json_decode($guzzleResponse->getBody()->getContents(), false);

            if ($json === null) {
                throw new GraphQLResponseError("Invalid GraphQL json.");
            }

            $response->load($guzzleResponse);

            if ($response->hasErrors()) {
                throw new GraphQLResponseError($response->errors()[0]->message);
            }

        } catch (\Exception $e) {
            SpBase::error($e->getMessage());
        }

        return $response;
    }
}