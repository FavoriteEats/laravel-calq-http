<?php

namespace FavoriteEats\CalqHTTP;

/**
 * Class CalqPayload
 * @package Favoriteeats\CalqHTTP
 *
 * Abstract class representing a payload to be sent to Calq. Includes property setters to help ensure sanity and validation. For further documentation see https://calq.io/docs/client/http.
 */

use Illuminate\Support\Str;
use FavoriteEats\CalqHTTP\Exceptions\InvalidCalqSpecialPropertyException;

abstract class CalqPayload {

    /**
     * @var string $actor [Required] The unique Id of the actor (user) performing this action. Normally this is a user Id generated by your application
     */
    protected $actor;

    /**
     * @var array $properties [Required] An array describing any additional custom data you want to send with this action. This is the data that you will analyze within Calq. This property is required but can be empty. It will be converted to a JSON object before sending to Calq.
     */
    protected $properties;

    /**
     * @var array $specialProperties When specifying custom data under the properties node there are some special properties that you can use. These properties allow you to use additional functionality in Calq reports.
     */
    protected $specialProperties = [];

    /**
     * @var array List of payload parameters in order of requirement.
     */
    protected static $payloadParams = [];


    public function __construct(array $payloadData = []) {
        if(!empty($payloadData)) {
            $this->hydratePayload($payloadData);
        }
    }


    /**
     * Takes numerically indexed or associative array of payload parameters and sets values on payload object via setters.
     * @param array $payloadData
     */
    protected function hydratePayload(array $payloadData)
    {
        foreach ($payloadData as $key => $value) {
            $param = $this->getParamName($key);

            $this->setParamValue($param, $value);
        }
    }

    /**
     * @param $key
     * @return string
     */
    protected function getParamName($key)
    {
        if(is_int($key)) {
            $param = '';
            if(isset(static::$payloadParams[$key])) {
                $param = static::$payloadParams[$key];
            }
        } else {
            $param = $key;
        }

        return $param;
    }

    /**
     * @param $param
     * @param $value
     */
    protected function setParamValue($param, $value)
    {
        if(!empty($param)) {
            $method = 'set' . Str::studly($param);

            if (method_exists($this, $method)) {
                $this->{$method}($value);
            }
        }
    }

    /**
     * @param array $array
     * @return bool
     */
    protected function isAssociative(array $array)
    {
        $keys = array_keys($array);

        return array_keys($keys) !== $keys;
    }

    /**
     * @return string
     */
    public function getActor()
    {
        return $this->actor;
    }

    /**
     * @param string $actor
     */
    public function setActor($actor)
    {
        $this->actor = $actor;
    }

    /**
     * @return array
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @param array $properties
     */
    public function setProperties(array $properties)
    {
        $this->validateProperties($properties);

        $this->properties = $properties;
    }

    /**
     * @param array $properties
     * @throws InvalidCalqSpecialPropertyException
     */
    protected function validateProperties(array $properties)
    {
        foreach($properties as $property) {

            if( $this->looksLikeSpecialProperty($property) && !$this->isSpecialProperty($property) )
            {
                throw new InvalidCalqSpecialPropertyException();
            }

        }
    }

    /**
     * @param $property
     * @return int
     */
    protected function looksLikeSpecialProperty($property)
    {
        return preg_match('/^\$.*$/', $property);
    }

    /**
     * @param $property
     * @return bool
     */
    protected function isSpecialProperty($property)
    {
        return array_key_exists($property, $this->specialProperties);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'actor' => $this->getActor(),
            'properties' => $this->getProperties(),
        ];
    }

}
