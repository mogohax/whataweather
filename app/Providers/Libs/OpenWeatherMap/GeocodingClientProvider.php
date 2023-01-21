<?php

declare(strict_types=1);

namespace App\Providers\Libs\OpenWeatherMap;

use App\Libs\OpenWeatherMap\Clients\GeocodingClient;
use GuzzleHttp\Client;
use GuzzleHttp\Command\Guzzle\Description;
use GuzzleHttp\Command\Guzzle\GuzzleClient;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use InvalidArgumentException;

class GeocodingClientProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app
            ->when(GeocodingClient::class)
            ->needs(GuzzleClient::class)
            ->give(function (Application $app) {
                $config = $app->make('config')->get('openweathermap', []);

                $serviceDescription = data_get($config, 'services.geocoding.description');
                throw_if(
                    empty($serviceDescription),
                    InvalidArgumentException::class,
                    'OpenWeatherMap Geocoding service description cannot be empty'
                );

                return new GuzzleClient(new Client(), new Description($serviceDescription));
            });

        $this->app
            ->when(GeocodingClient::class)
            ->needs('$apiKey')
            ->giveConfig('openweathermap.api_key');
    }
}
