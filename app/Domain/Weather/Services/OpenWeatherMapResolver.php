<?php

declare(strict_types=1);

namespace App\Domain\Weather\Services;

use App\Domain\Location\ValueObjects\Coordinates;
use App\Domain\Weather\Contracts\WeatherResolver;
use App\Domain\Weather\Exceptions\WeatherResolverException;
use App\Domain\Weather\ValueObjects\Pressure\Pressure;
use App\Domain\Weather\ValueObjects\Temperature\Temperature;
use App\Domain\Weather\ValueObjects\WeatherData;
use App\Libs\OpenWeatherMap\Clients\WeatherClient;
use App\Libs\OpenWeatherMap\Exceptions\OpenWeatherMapClientException;
use Assert\AssertionFailedException;
use Psr\Log\LoggerInterface;

class OpenWeatherMapResolver implements WeatherResolver
{
    private WeatherClient $weatherClient;
    private LoggerInterface $logger;

    public function __construct(WeatherClient $weatherClient, LoggerInterface $logger)
    {
        $this->weatherClient = $weatherClient;
        $this->logger = $logger;
    }

    public function resolveCurrent(Coordinates $coordinates): WeatherData
    {
        $this
            ->logger
            ->debug('Resolving weather from OpenWeatherMap for {coordinates}', ['coordinates' => $coordinates]);

        try {
            $response = $this->weatherClient->oneCall(
                $coordinates->longitude,
                $coordinates->latitude,
                [
                    WeatherClient::EXCLUDE_MINUTELY,
                    WeatherClient::EXCLUDE_HOURLY,
                    WeatherClient::EXCLUDE_DAILY,
                    WeatherClient::EXCLUDE_ALERTS,
                ]
            );

            return new WeatherData(
                temperature: Temperature::celsius($response->current->temp),
                feelsLike: Temperature::celsius($response->current->feelsLike),
                pressure: Pressure::hPa($response->current->pressure)
            );
        } catch (OpenWeatherMapClientException $exception) {
            throw new WeatherResolverException('Could not get weather data from API', previous: $exception);
        } catch (AssertionFailedException $exception) {
            throw new WeatherResolverException('Could not parse response from API', previous: $exception);
        }
    }
}
