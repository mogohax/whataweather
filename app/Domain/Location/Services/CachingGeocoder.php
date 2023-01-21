<?php

declare(strict_types=1);

namespace App\Domain\Location\Services;

use App\Domain\Location\Contracts\GeocodingService;
use App\Domain\Location\ValueObjects\Coordinates;
use Illuminate\Support\Facades\Cache;
use Psr\Log\LoggerInterface;

class CachingGeocoder implements GeocodingService
{
    private GeocodingService $geocodingService;
    private LoggerInterface $logger;

    public function __construct(GeocodingService $geocodingService, LoggerInterface $logger)
    {
        $this->geocodingService = $geocodingService;
        $this->logger = $logger;
    }

    public function geocodeLocation(string $location): Coordinates
    {
        return Cache::rememberForever(
            mb_strtolower($location),
            function () use ($location) {
                $this->logger->debug('No coordinates of {location} in cache, pulling from decorated service', [
                    'location' => $location,
                ]);

                return $this->geocodingService->geocodeLocation($location);
            }
        );
    }
}
