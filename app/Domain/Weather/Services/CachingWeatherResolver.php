<?php

declare(strict_types=1);

namespace App\Domain\Weather\Services;

use App\Domain\Location\ValueObjects\Coordinates;
use App\Domain\Weather\Contracts\WeatherResolver;
use App\Domain\Weather\Normalizers\CoordinateNormalizer;
use App\Domain\Weather\ValueObjects\WeatherData;
use Illuminate\Support\Facades\Cache;
use Psr\Log\LoggerInterface;

class CachingWeatherResolver implements WeatherResolver
{
    private const TTL_SECONDS = 600;

    // Limit coordinate precision to ~1km to improve cache hits
    // https://en.wikipedia.org/wiki/Decimal_degrees
    private const COORDINATE_DECIMALS_TOWN = 2;

    private WeatherResolver $decoratedResolver;
    private CoordinateNormalizer $normalizer;
    private LoggerInterface $logger;

    public function __construct(
        WeatherResolver $decoratedResolver,
        CoordinateNormalizer $normalizer,
        LoggerInterface $logger
    )
    {
        $this->decoratedResolver = $decoratedResolver;
        $this->normalizer = $normalizer;
        $this->logger = $logger;
    }

    public function resolveCurrent(Coordinates $coordinates): WeatherData
    {
        $this
            ->logger
            ->debug('Resolving weather from cache for {coordinates}', ['coordinates' => $coordinates]);

        return Cache::remember(
            $this->normalizer->asCacheKey($coordinates, self::COORDINATE_DECIMALS_TOWN),
            self::TTL_SECONDS,
            fn () => $this->decoratedResolver->resolveCurrent($coordinates)
        );
    }
}
