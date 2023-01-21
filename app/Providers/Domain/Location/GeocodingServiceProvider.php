<?php

declare(strict_types=1);

namespace App\Providers\Domain\Location;

use App\Domain\Location\Contracts\GeocodingService;
use App\Domain\Location\Services\CachingGeocoder;
use App\Domain\Location\Services\OpenWeatherMapGeocoder;
use Illuminate\Support\ServiceProvider;
use Psr\Log\LoggerInterface;

class GeocodingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(GeocodingService::class, OpenWeatherMapGeocoder::class);

        $this->app->extend(GeocodingService::class, function ($service, $app) {
            return new CachingGeocoder($service, $this->app->make(LoggerInterface::class));
        });
    }

    public function provides(): array
    {
        return [GeocodingService::class];
    }
}
