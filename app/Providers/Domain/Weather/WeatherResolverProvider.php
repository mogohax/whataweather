<?php

declare(strict_types=1);

namespace App\Providers\Domain\Weather;

use App\Domain\Weather\Contracts\WeatherResolver;
use App\Domain\Weather\Normalizers\CoordinateNormalizer;
use App\Domain\Weather\Services\CachingWeatherResolver;
use App\Domain\Weather\Services\OpenWeatherMapResolver;
use Carbon\Laravel\ServiceProvider;
use Psr\Log\LoggerInterface;

class WeatherResolverProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(WeatherResolver::class, OpenWeatherMapResolver::class);

        $this->app->extend(WeatherResolver::class, function ($service) {
            return new CachingWeatherResolver(
                $service,
                $this->app->make(CoordinateNormalizer::class),
                $this->app->make(LoggerInterface::class)
            );
        });
    }
}
