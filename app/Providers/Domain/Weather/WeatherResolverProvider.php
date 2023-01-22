<?php

declare(strict_types=1);

namespace App\Providers\Domain\Weather;

use App\Domain\Weather\Contracts\WeatherResolver;
use App\Domain\Weather\Services\OpenWeatherMapResolver;
use Carbon\Laravel\ServiceProvider;

class WeatherResolverProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(WeatherResolver::class, OpenWeatherMapResolver::class);
    }
}
