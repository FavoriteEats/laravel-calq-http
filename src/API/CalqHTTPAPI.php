<?php
namespace FavoriteEats\CalqHTTP\API;


/**
 * Class CalqHTTPAPI
 * @package FavoriteEats\CalqHTTP
 *
 * Base API interface class. Should be extended by specific implementation that performs REST requests against Calq API.
 */
abstract class CalqHTTPAPI implements CalqHTTPAPIInterface {


    const BASE_URL = 'api.calq.io';

    const TRACK_ROUTE = '/track';

    const PROFILE_ROUTE = '/profile';

    const TRANSFER_ROUTE = '/transfer';

    /**
     * @var bool Defines if requests should use HTTPS. Defaults to true.
     */
    protected $https = true;


    /**
     * Sets whether or not to use HTTPS in requests.
     * @param bool|true $useHttps
     */
    public function useHttps($useHttps=true)
    {
        $this->https = (bool) $useHttps;
    }

    /**
     * @param $actor
     * @param $action
     * @param array $properties
     * @param $writeKey
     * @param null $ipAddress
     * @param null $timestamp
     * @return mixed
     */
    public function track($actor, $action, array $properties, $writeKey, $ipAddress = null, $timestamp = null)
    {
        $payload = [
            'actor'       => $actor,
            'action_name' => $action,
            'properties'  => (count($properties) > 0 ? $properties : (object) null),
            'write_key'   => $writeKey
        ];

        if( !is_null($ipAddress) )
        {
            array_merge($payload, ['ip_address' => $ipAddress]);
        }

        if( !is_null($timestamp) )
        {
            array_merge($payload, ['timestamp' => $timestamp]);
        }

        return $this->call(static::TRACK_ROUTE, $payload);
    }

    /**
     * @param array $trackBatch
     * @return mixed
     */
    public function batch(array $trackBatch)
    {
        return $this->call(static::TRACK_ROUTE, $trackBatch);
    }

    /**
     * @param $actor
     * @param array $properties
     * @param $writeKey
     * @return mixed
     */
    public function profile($actor, array $properties, $writeKey)
    {
        $payload = [
            'actor'       => $actor,
            'properties'  => $properties,
            'write_key'   => $writeKey
        ];

        return $this->call(static::PROFILE_ROUTE, $payload);
    }

    /**
     * @param $oldActor
     * @param $newActor
     * @param $writeKey
     * @return mixed
     */
    public function transfer($oldActor, $newActor, $writeKey)
    {
        $payload = [
            'old_actor' => $oldActor,
            'new_actor' => $newActor,
            'write_key' => $writeKey
        ];

        return $this->call(static::TRANSFER_ROUTE, $payload);
    }

    /**
     * @return string
     */
    public function getBaseUri()
    {
        return ($this->https ? 'https://' : 'http://') . static::BASE_URL;
    }

    /**
     * @param $route
     * @return string
     */
    public function getUri($route)
    {
        return  $this->getBaseUri() . $route;
    }

}
