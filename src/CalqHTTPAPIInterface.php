<?php

namespace FavoriteEats\CalqHTTP;

interface CalqHTTPAPIInterface {

    public function useHttps($useHttps=true);

    public function track($actor, $action, array $properties, $writeKey, $ipAddress=null, $timestamp=null);

    public function batch(array $trackBatch);

    public function profile($actor, array $properties, $writeKey);

    public function transfer($oldActor, $newActor, $writeKey);

    public function call($route, array $payload);

    public function getBaseUri();

    public function getUri($route);

}