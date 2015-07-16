<?php
namespace FavoriteEats\CalqHTTP;

use FavoriteEats\CalqHTTP\Payloads\CalqTrackPayload;
use FavoriteEats\CalqHTTP\Payloads\CalqProfilePayload;
use FavoriteEats\CalqHTTP\Payloads\CalqIdentityPayload;

interface CalqHTTPInterface
{

    /**
     * Sends a CalqTrackPayload or batch of CalqTrackPayloads to the Calq track API
     * @param CalqTrackPayload|null $payload
     * @return mixed
     */
    public function track(CalqTrackPayload $payload=null);

    /**
     * Add a CalqTrackPayload to the batch for tracking through the Calq track API.
     * @param CalqTrackPayload $payload
     */
    public function batch(CalqTrackPayload $payload);

    /**
     * Sends a CalqProfilePayload to the Calq profile API
     * @param CalqProfilePayload $payload
     * @return mixed
     */
    public function profile(CalqProfilePayload $payload);

    /**
     * Sends a CalqIdentityPayload to the Calq transfer API
     * @param CalqIdentityPayload $payload
     * @return mixed
     */
    public function transfer(CalqIdentityPayload $payload);

}