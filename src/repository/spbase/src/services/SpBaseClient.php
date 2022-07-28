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
use GuzzleHttp\Client as GuzzleClient;
use craft\helpers\Json;

use lantra\sp\helpers\LantraHelper;
use lantra\spbase\Module;
use lantra\spbase\Module as SpBase;
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
     * @var bool
     */
    public $enabled;

    /**
     * @param array $config
     */
    public function __construct()
    {
        $this->endpoint = Craft::getAlias('@spBaseUrl') . '/api';

        $this->subdomain = Craft::getAlias('@site');

        $this->guzzle = new GuzzleClient();
    }

    /**
     * @param true $cache
     * @return Site
     * @throws GuzzleException
     */
    public function getSite($cache = true)
    {
        if (!$this->isEnabled()) {
            return new Site();
        }

        if ($cache) {
            $attributes = (object)Craft::$app->cache->getOrSet('spBaseSiteLicence', function () {
                return $this->getSiteAttributes();
            }, (86400));
        }
        else {
            $attributes = $this->getSiteAttributes();
        }

        if (!isset($attributes->id)) {
            SpBase::error('Invalid Site ID [' . $this->subdomain . ']');
        }

        return new Site($attributes);
    }

    /**
     * @return object
     * @throws GuzzleException
     */
    protected function getSiteAttributes()
    {
        $query = 'query getSite($subdomain: [QueryArgument!]) {  
            entry (section:"sites", subdomain: $subdomain limit: 1, orderBy: "dateCreated DESC") {
                ... on sites_site_Entry {
                    __typename
                    id         
                    dateCreated @formatDateTime (format: "Y-m-d")
                    siteExpiryDate @formatDateTime (format: "Y-m-d")
                    subdomain
                    licenceTypeSite
                    licenceTypeUser
                    hasCompanyLicences
                    totalActive
                    totalRemaining
              }              
            }
        }';

        $variables = [
            'subdomain' => $this->subdomain
        ];

        $response = $this->query($query, $variables);

        return $response->entry ?? new \stdClass();
    }

    /**
     * @param $username
     * @return int
     * @throws GuzzleException
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

        return $response->user ? $response->user->id : 0;
    }

    /**
     * @param $userId
     * @param string $month
     * @param string|null $postDate
     * @param array $meta
     * @param null $entryId
     * @return bool
     * @throws GuzzleException
     */
    public function saveLicence($userId, string $month = '01', string $postDate = null, array $meta = [], $entryId = null)
    {
        $query = 'mutation saveEntry($entryId: ID, $postDate: DateTime, $authorId: ID, $siteId: Int, $userId: String, $month: String, $meta: String) {
            save_licences_licence_Entry(
                id: $entryId,
                postDate: $postDate,
                authorId: $authorId,                
                relatedSite: [$siteId], 
                userId: $userId,
                renewalMonth: $month,
                meta: $meta
            ) {
                id
            }
        }';

        ## force month to avoid error
        if (!in_array($month, ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'])) {
            $month = '01';
        }

        $variables = [
            'entryId' => $entryId,
            'postDate' => $postDate,
            'siteId' => $this->getSiteId(),
            'authorId' => $this->getAuthorId(),
            'userId' => (string) $userId,
            'month' => $month,
            'meta' => Json::encode($meta)
        ];

        $response = $this->query($query, $variables);

        return !$response->hasErrors();
    }

    /**
     * @param $model
     * @return bool
     * @throws GuzzleException
     */
    public function suspendEntry($model)
    {
        $query = 'mutation saveEntry($entryId: ID) {
            save_' . $model->__typename . '(id: $entryId, enabled: false) {
                id
            }
        }';

        $variables = [
            'entryId' => $model->id
        ];

        $response = $this->query($query, $variables);

        return !$response->hasErrors();
    }

    /**
     * @param $model
     * @param $process
     * @return bool
     */
    public function saveProcess($model, $process)
    {
        $query = 'mutation saveProcess($entryId: ID, $process: String) {
            save_' . $model->__typename . '(id: $entryId, process: $process) {
                id
            }
        }';

        $variables = [
            'entryId' => $model->id,
            'process' => Json::encode($process),
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
                        id                        
                        dateCreated @formatDateTime (format: "Y-m-d")
                        method
                        code
                        amount                        
                        reference
                        meta
                        isPaid
                        isProcessed
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
        $query = 'mutation saveEntry($entryId: ID, $authorId: ID, $siteId: Int, $companyId: String) {
            save_companies_company_Entry(
            id: $entryId, authorId: $authorId, relatedSite: [$siteId], companyId: $companyId) {
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
     * Bypass GQL and call api directly (i.e. get button html)
     *
     * @param $method
     * @param array $params
     * @return \Psr\Http\Message\ResponseInterface|string
     * @throws GuzzleException
     */
    public function request($method, array $params = [])
    {
        $response = '[[ empty response ]]';

        $endpoint = Craft::getAlias('@spBaseUrl') . '/actions/' . $method;

        try {
            $guzzleResponse = $this->guzzle->request('POST', $endpoint, ['form_params' => $params]);
            $response = $guzzleResponse->getBody()->getContents();
        } catch (\Exception $e) {
            $response = $e->getMessage();
            Module::error($e->getMessage());
        }

        return $response;
    }

    /**
     * @return int
     */
    private function getSiteId()
    {
        if (!$this->isEnabled()) {
            return $this->siteId;
        }

        if (!$this->siteId) {
            ## siteId is cached for infinity
            $this->siteId = (int) Craft::$app->cache->getOrSet('spBaseSiteId', function () {
                $site = $this->getSite();
                return $site->id;
            }, 0);
        }

        if (!$this->siteId) {
            SpBase::error('Invalid Site ID');
            Craft::$app->cache->delete('spBaseSiteId');
        }

        return $this->siteId;
    }

    /**
     * @return int
     */
    private function getAuthorId()
    {
        if (!$this->isEnabled()) {
            return $this->authorId;
        }

        if (!$this->authorId) {
            ## authorId is cached for infinity
            $this->authorId = (int) Craft::$app->cache->getOrSet('spBaseAuthorId', function () {
                return $this->getUserId('graphql');
            }, 0);
        }

        if (!$this->authorId) {
            SpBase::error('Invalid GraphQL API User ID');
            Craft::$app->cache->delete('spBaseAuthorId');
        }

        return $this->authorId;
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

        if (!$this->isEnabled()) {
            return $response;
        }

        try {
            $guzzleResponse = $this->guzzle->request('POST', $this->endpoint, [
                'json' => [
                    'query' => $query,
                    'variables' => $variables
                ],
                'headers' => $headers
            ]);

            $json = Json::decodeIfJson($guzzleResponse->getBody()->getContents(), false);

            if ($json === null) {
                throw new GraphQLResponseError("Invalid GraphQL json.");
            }

            $response->load($json);

            if ($response->hasErrors()) {
                throw new GraphQLResponseError($response->errors()[0]->message);
            }

        } catch (\Exception $e) {
            SpBase::error($e->getMessage() . "\n\n" . $e->getTraceAsString());
        }
        return $response;
    }

    /**
     * @return bool
     */
    private function isEnabled()
    {
        if (is_null($this->enabled)) {
            $this->enabled = LantraHelper::enableBase();
        }

        return $this->enabled;
    }

}