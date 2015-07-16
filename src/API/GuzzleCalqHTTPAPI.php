<?php
namespace FavoriteEats\CalqHTTP\API;

use GuzzleHttp\Client as Guzzle;


/**
 * Class GuzzleCalqHTTPAPI
 * @package FavoriteEats\CalqHTTP
 */
class GuzzleCalqHTTPAPI extends CalqHTTPAPI {

    protected $client;


    public function __construct(Guzzle $client=null)
    {
        if(is_null($client)) {
            $client = new Guzzle([
                'base_uri' => $this->getBaseUri()
            ]);
        }
        $this->setClient($client);
    }

    /**
     * @param Guzzle $client
     */
    public function setClient(Guzzle $client)
    {
        $this->client = $client;
    }


    /**
     * @param $route
     * @param array $payload
     * @return null
     */
    public function call($route, array $payload)
    {
        $response = null;

        try {
            $response = $this->client->post(
                $this->getUri($route),
                [
                    'json' => $payload
                ]
            );
        } catch(\Exception $e) {
            \Log::error('GuzzleCalqHTTPAPI Error: [' . $e->getCode() . '] ' . $e->getMessage());
        }

        return $response;
    }

}
