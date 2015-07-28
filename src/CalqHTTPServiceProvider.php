<?php
namespace FavoriteEats\CalqHTTP;

use Illuminate\Support\ServiceProvider;
use FavoriteEats\CalqHTTP\API\GuzzleCalqHTTPAPI;

class CalqHTTPServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->setupConfig();
    }

    protected function setupConfig()
    {
        $source = __DIR__.'/../config/config.php';

        if (class_exists('Illuminate\Foundation\Application', false)) {
            $this->publishes([
                $source => config_path('calqhttp.php'),
            ]);
        }

        // use the vendor configuration file as fallback
        $this->mergeConfigFrom(
            $source, 'calqhttp'
        );
    }


    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerPackage();

        config([
            'config/calqhttp.php',
        ]);
    }

    private function registerPackage()
    {
        $this->app->singleton('FavoriteEats\CalqHTTP\CalqHTTP', function($app) {

            $calqHTTPApi = new GuzzleCalqHTTPAPI();

            return new CalqHTTP($calqHTTPApi, config('calqhttp.write_key'));
        });
    }
}