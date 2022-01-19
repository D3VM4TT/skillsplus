<?php
/**
 * Lantra Skills+ Base for Craft CMS 3.x
 *
 * @link      https://coffeebean.design
 * @copyright Copyright (c) 2022 Coffee Bean Design
 */

namespace lantra\spbase\services\gql;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use lantra\spbase\services\gql\exceptions\GraphQLError;
use lantra\spbase\services\gql\exceptions\GraphQLResponseError;

class Client
{
    /**
     * @var $endpoint
     */
    protected $endpoint;

    /**
     * @var GuzzleClient
     */
    protected $guzzle;

    /**
     * @param array $config
     */
    public function __construct($endpoint)
    {
        $this->endpoint = $endpoint;
        $this->guzzle = new GuzzleClient();
    }

    /**
     * @param $query
     * @param array $variables
     * @param false $critical
     * @return Response
     * @throws GraphQLError
     * @throws GraphQLResponseError
     */
    public function response($query, array $variables = [], bool $critical = false): Response
    {
        $response = $this->request($query, $variables);

        $contents = $response->getBody()->getContents();

        $json = json_decode($contents, false);

        if ($json === null) {
            throw new GraphQLResponseError("Invalid GraphQL json. \n" . $contents, $critical);
        }

        $response = new Response($json);

        if ($response->hasErrors()) {
            throw new GraphQLResponseError($response->errors()[0]->message . "\n" . $contents, $critical);
        }

        return $response;
    }

    /**
     * @param $query
     * @param array $variables
     * @param array $headers
     * @return \Psr\Http\Message\ResponseInterface
     * @throws GraphQLError
     */
    private function request($query, $variables = [], $headers = [])
    {
        try {
            return $this->guzzle->request('POST', $this->endpoint, [
                'json' => [
                    'query' => $query,
                    'variables' => $variables
                ],
                'headers' => $headers
            ]);
        } catch (GuzzleException $e) {
            throw new GraphQLError($e->getMessage(), true);
        }
    }
}