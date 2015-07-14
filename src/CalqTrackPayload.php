<?php
namespace FavoriteEats\CalqHTTP;

use Favoriteeats\CalqHTTP\Exceptions\InvalidCalqActionNamePropertyException;
use FavoriteEats\CalqHTTP\Exceptions\InvalidIPAddressException;


/**
 * Class CalqTrackPayload
 * @package FavoriteEats\CalqHTTP
 *
 * Class representing a payload to be sent to the Calq /track API endpoint. Includes property setters to help ensure sanity and validation. For further documentation see https://calq.io/docs/client/http.
 */
class CalqTrackPayload extends CalqPayload {

    /**
     * @var string $actionName [Required] The name of the action being tracked. This will be shown in the reporting interface.
     */
    protected $actionName;

    /**
     * @var array $specialProperties When specifying custom data under the properties node there are some special properties that you can use. These properties allow you to use additional functionality in Calq reports.
     */
    protected $specialProperties = [
        '$sale_value', //The amount of any sale. Marks this action as containing revenue. Must be specified if $sale_currency is also present.
        '$sale_currency', //The 3 letter currency code for any sale. Must be specified if $sale_value is also present.
        '$device_agent', //The user agent of the browser being used, or information on the device being used.
        '$device_os', //The operating system running on the device (Windows, OSX, iOS, Android etc).
        '$device_resolution', //The screen size of the device being used.
        '$device_mobile', //Whether the device is a mobile device such as a phone or tablet.
        '$country', //The user's current country (Taken from IP address if not specified manually).
        '$region', //The user's region within their country (Taken from IP address if not specified manually).
        '$city', //The user's city (Taken from IP address if not specified manually, though accuracy at city level is varied).
        '$gender', //The gender of the user (as either the string "male" or "female").
        '$age', //The age of the user. Must be an integer.
        '$utm_campaign', //The campaign name that was used to acquire this user (utm_campaign).
        '$utm_source', //The source that was used to acquire this user (utm_source).
        '$utm_medium', //The type of marketing medium that was used to acquire this user (utm_medium).
        '$utm_content', //The content source that was used to acquire this user (utm_content).
        '$utm_term', //The keywords used to acquire this user (utm_term).
        '$is_view', //Flags that this is a view (a page, or screen that contains other actions).
        '$is_within_view', //Flag whether this action occurred within a view (the last set view).
        '$view_url', //The URL of this view if it's a page.
        '$view_name', //The name of this view (e.g. page title, or app screen name).
    ];

    /**
     * @var array List of payload parameters in order of requirement.
     */
    protected static $payloadParams = [
        0 => 'actor',
        1 => 'action_name',
        2 => 'properties'
    ];

    /**
     * @var string $ipAddress [Optional] The IP address of the actor performing this action. To specify an unknown address use the string literal "none".
     */
    protected $ipAddress;

    /**
     * @var string $timestamp [Optional] UTC date & time that this action occurred. Dates can be up to 2 days in the past. Most sane time formats will be accepted, but if you want something explicit then use yyyy-MM-dd HH:mm:ss.SSSZ.
     */
    protected $timestamp;


    /**
     * @return string
     */
    public function getActionName()
    {
        return $this->actionName;
    }

    /**
     * @param string $actionName
     * @throws InvalidCalqActionNamePropertyException
     */
    public function setActionName($actionName)
    {
        if($this->looksLikeSpecialProperty($actionName)) {
            throw new InvalidCalqActionNamePropertyException();
        }

        $this->actionName = $actionName;
    }

    /**
     * @return string
     */
    public function getIpAddress()
    {
        return empty($this->ipAddress) ? null : $this->ipAddress;
    }

    /**
     * @param string $ipAddress
     * @throws InvalidIPAddressException
     */
    public function setIpAddress($ipAddress)
    {
        if ( ! filter_var($ipAddress, FILTER_VALIDATE_IP) )
        {
            throw new InvalidIPAddressException();
        }

        $this->ipAddress = $ipAddress;
    }

    /**
     * @return string
     */
    public function getTimestamp()
    {
        return empty($this->timestamp) ? null : $this->timestamp;
    }

    /**
     * @param string $timestamp
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    public function toArray()
    {
        $baseProperties = parent::toArray();

        return array_merge(
            $baseProperties,
            [
                'action_name' => $this->getActionName(),
                'ip_address' => $this->getIpAddress(),
                'timestamp' => $this->getTimestamp()
            ]
        );
    }

}
