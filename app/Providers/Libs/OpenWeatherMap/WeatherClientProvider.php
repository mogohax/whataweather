<?php

declare(strict_types=1);

namespace App\Providers\Libs\OpenWeatherMap;

use App\Libs\OpenWeatherMap\Clients\WeatherClient;
use GuzzleHttp\Client;
use GuzzleHttp\Command\Guzzle\Description;
use GuzzleHttp\Command\Guzzle\GuzzleClient;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use InvalidArgumentException;

class WeatherClientProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app
            ->when(WeatherClient::class)
            ->needs(GuzzleClient::class)
            ->give(function (Application $app) {
                $config = $app->make('config')->get('openweathermap', []);

                $serviceDescription = data_get($config, 'services.weather.description');
                throw_if(
                    empty($serviceDescription),
                    InvalidArgumentException::class,
                    'OpenWeatherMap Weather service description cannot be empty'
                );

                return new GuzzleClient(new Client(), new Description($serviceDescription));
            });

        $this->app
            ->when(WeatherClient::class)
            ->needs('$apiKey')
            ->giveConfig('openweathermap.api_key');
    }
}
