# Laravel-Calq-HTTP

Laravel-Calq-HTTP is an API wrapper for the [Calq.io HTTP API](https://calq.io/docs/client/http). This package is useful in cases where you don't need or want client-side integration with the Calq Javascript client. Where client-side integration is desired, please consider using the official [Calq PHP client](https://calq.io/docs/client/php).  


##Prerequisites

- PHP >= 5.5.9
- Laravel ~5.1
- Composer (recommended, not required)
- Calq account and application write_key


## Install

To install Laravel Calq HTTP:

- `composer require favoriteeats/laravel-calq-http` or add `"favoriteeats/laravel-calq-http": "dev-master"` to the require section of your composer.json file
- Add `FavoriteEats\CalqHTTP\CalqHTTPServiceProvider::class` to your config/app.php "providers" array
- Then, run `composer install` or `composer update`
- Optionally, export the config file with `php artisan vendor:publish --provider=FavoriteEats\Laravel-Calq-HTTP\CalqHTTPServiceProvider` and edit config/calqhttp.php, or (better) add the expected config values to your .env file


## Usage

See [Calq HTTP API documentation](https://calq.io/docs/client/http) for full information.

```php
//First, specify the HTTP client you want to use. The service provider defaults to Guzzle,
// and Guzzle is the only client currently supported.

$calqHTTPApi = new GuzzleCalqHTTPAPI();

//Then, instantiate the main Calq class.
$calq = new CalqHTTP($calqHTTPApi, '[Calq write key]');

//If the CalqHTTP instance is resolved through the IoC container then the Guzzle client and write key are
// automatically composed with the object as a singleton for you

use FavoriteEats\CalqHTTP\CalqHTTP;

class SomeController extends Controller {

    protected $calq;
    
    public function __construct(CalcHTTP $calq)
    {
        $this->$calq = $calq;
    }
    
}
```

 
### Using the /track endpoint

```php    
//Now create a payload corresponding to the type of request you want to make. Payloads include:
// CalqTrackPayload (/track endpoint), CalqProfilePayload (/profile endpoint),
// and CalqIdentityPayload (/transfer endpoint). An array of CalqTrackPayloads
// is used in /batch endpoint operations.

$payload = new FavoriteEats\CalqHTTP\CalqTrackPayload([
    12345,                                             //actor (required); unique identifier for the user 
    'some_action_name',                                //action_name (required); name of the action you're tracking
    [                                                  //properties (required), [] allowed; custom or special properties
        'time_on_page' => 65.2,
        '$view_url' => 'http://www.example.com/somepage',
        '$device_mobile' => true
    ],
    '192.168.10.100',                                  //ip_address (optional); actor's ip address
    Carbon::now()->tz('utc')->format('Y-m-d\TH:i:s\Z') //timestamp (optional); timestamp of event, example using Carbon
]);

$response = $calq->track($payload);

echo $response->getBody(); //response is a GuzzleHttp\Psr7\Response object

//Payload attributes may also be set individually.
$payload = new FavoriteEAts\CalqHTTP\CalqTrackPayload();
$payload->setActor(12345);
$payload->setActionName('some_action_name');
$payload->setProperties(['test'=>true]);
```


#### Sending a multiple payloads in one batch

```php
//Send multiple payloads together as follows...

foreach($userAction as $action) {
    $payload = new FavoriteEats\CalqHTTP\CalqTrackPayload([
        'actor' => $action->user->id, 
        'action_name' => $action->name,
        'properties' => [
            '$view_url' => $visit->pageUrl
        ]
    ]);
    
    $calq->batch($payload);
}

$response = $calq->track() //sends all batched CalqTrackPayload payloads
```


### Using the /profile endpoint

```php
$payload = new FavoriteEats\CalqHTTP\CalqProfilePayload([
    12345,
    [
        'height' => '5ft 11in',
        'weight' => '185lbs',
        'favorite_color' => 'blue'
        '$full_name' => 'Snoopy Dawg',
        '$gender' => 'male',
        '$age' => 29
    ]
]);
$response = $calq->profile($payload);
```

### Using the /transfer endpoint

```php
$payload = new FavoriteEats\CalqHTTP\CalqIdentityPayload([12346, 12345]);
$response = $calq->transfer($payload);
```


## Contributing

Please report any issues on the issues page. Pull requests are welcome.


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.