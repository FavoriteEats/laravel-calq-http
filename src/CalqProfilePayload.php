<?php
namespace FavoriteEats\CalqHTTP;

/**
 * Class CalqProfilePayload
 * @package FavoriteEats\CalqHTTP
 *
 * Class representing a payload to be sent to the Calq /profile API endpoint. For further documentation see https://calq.io/docs/client/http.
 */
class CalqProfilePayload extends CalqPayload {

    /**
     * @var array $specialProperties When specifying custom data under the properties node there are some special properties that you can use. These properties allow you to use additional functionality in Calq reports.
     */
    protected $specialProperties = [
        '$full_name', //The full name of this user.
        '$image_url', //URL to an image to display in Calq's UI when examining this user. Use a square image for best results.
        '$country', //The user's current country.
        '$region', //The user's region within their country.
        '$city', //The user's city.
        '$gender', //The gender of the user (as either the string "male" or "female").
        '$age', //The age of the user. Must be an integer.
        '$email', //The email address used to contact this user
        '$phone', //A phone number contact this user
        '$sms', //A mobile number that can be used to deliver SMS messages to this user.
        '$utm_campaign', //The campaign name that was used to acquire this user (utm_campaign).
        '$utm_source', //The source that was used to acquire this user (utm_source).
        '$utm_medium', //The type of marketing medium that was used to acquire this user (utm_medium).
        '$utm_content', //The content source that was used to acquire this user (utm_content).
        '$utm_term', //The keywords used to acquire this user (utm_term).
    ];

    /**
     * @var array List of payload parameters in order of requirement.
     */
    protected static $payloadParams = [
        0 => 'actor',
        1 => 'properties'
    ];

}
