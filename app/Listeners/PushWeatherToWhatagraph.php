<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\WeatherFetched;
use App\Libs\Whatagraph\Clients\Whatagraph;
use App\Libs\Whatagraph\Requests\AddData;
use Illuminate\Contracts\Queue\ShouldQueue;
use Psr\Log\LoggerInterface;

class PushWeatherToWhatagraph implements ShouldQueue
{
    private LoggerInterface $logger;
    private Whatagraph $whatagraph;

    public function __construct(LoggerInterface $logger, Whatagraph $whatagraph)
    {
        $this->logger = $logger;
        $this->whatagraph = $whatagraph;
    }

    public function handle(WeatherFetched $event): void
    {
        $weather = $event->weather;
        $date = now();

        $this->logger->debug('Pushing weather data of {coordinates} to WG on {date}', [
            'coordinates' => $event->coordinates,
            'date' => $date,
        ]);

        $response = $this->whatagraph->addData([
            new AddData('temperature', (string) $weather->temperature->value, $date->toImmutable()),
            new AddData('feels_like', (string) $weather->feelsLike->value, $date->toImmutable()),
            new AddData('pressure', (string) $weather->pressure->value, $date->toImmutable()),
        ]);

        $this->logger->debug('WG push success');
    }
}
