<?php
namespace FavoriteEats\CalqHTTP;

use FavoriteEats\CalqHTTP\API\CalqHTTPAPIInterface;

class CalqHTTP implements CalqHTTPInterface
{

    /**
     * @var CalqHTTPAPIInterface The library used to communicate directly with the Calq API.
     */
    protected $api;

    /**
     * @var string $writeKey [Required] The write key for the project to save data for. This is found within the Calq reporting interface.
     */
    protected $writeKey;

    /**
     * @var array $batch Array of CalqTrackPayload(s) to be sent to Calq for tracking.
     */
    protected $batch = [];

    /**
     * @param CalqHTTPAPIInterface $api
     * @param $writeKey
     */
    public function __construct(CalqHTTPAPIInterface $api, $writeKey)
    {
        $this->api = $api;
        $this->writeKey = $writeKey;
    }

    /**
     * Sends a CalqTrackPayload or batch of CalqTrackPayloads to the Calq track API
     * @param CalqTrackPayload|null $payload
     * @return mixed
     */
    public function track(CalqTrackPayload $payload=null)
    {
        if(!empty($payload)) {
            return $this->api->track(
                $payload->getActor(),
                $payload->getActionName(),
                $payload->getProperties(),
                $this->writeKey
            );
        }

        if(count($this->batch) > 0) {
            $response = $this->api->batch($this->batch);
            $this->batch = [];

            return $response;
        }

        return null;
    }

    /**
     * Add a CalqTrackPayload to the batch for tracking through the Calq track API.
     * @param CalqTrackPayload $payload
     */
    public function batch(CalqTrackPayload $payload)
    {
        $this->batch[] = array_merge(
            $payload->toArray(),
            ['write_key' => $this->writeKey]
        );
    }

    /**
     * Sends a CalqProfilePayload to the Calq profile API
     * @param CalqProfilePayload $payload
     * @return mixed
     */
    public function profile(CalqProfilePayload $payload)
    {
        return $this->api->profile(
            $payload->getActor(),
            $payload->getProperties(),
            $this->writeKey
        );
    }

    /**
     * Sends a CalqIdentityPayload to the Calq transfer API
     * @param CalqIdentityPayload $payload
     * @return mixed
     */
    public function transfer(CalqIdentityPayload $payload)
    {
        return $this->api->transfer(
            $payload->getOldActor(),
            $payload->getNewActor(),
            $this->writeKey
        );
    }

}