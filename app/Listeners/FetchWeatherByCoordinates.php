<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Domain\Weather\Contracts\WeatherResolver;
use App\Events\CoordinatesFetched;
use App\Events\WeatherFetched;
use Illuminate\Contracts\Queue\ShouldQueue;
use Psr\Log\LoggerInterface;

class FetchWeatherByCoordinates implements ShouldQueue
{
    private LoggerInterface $logger;
    private WeatherResolver $weatherResolver;

    public function __construct(LoggerInterface $logger, WeatherResolver $weatherResolver)
    {
        $this->logger = $logger;
        $this->weatherResolver = $weatherResolver;
    }

    public function handle(CoordinatesFetched $event): void
    {
        $weather = $this->weatherResolver->resolveCurrent($event->coordinates);

        $this->logger->debug('Weather fetched by {coordinates}', [
            'coordinates' => $event->coordinates,
            'weather' => $weather,
        ]);

        WeatherFetched::dispatch($event->coordinates, $weather);
    }
}
