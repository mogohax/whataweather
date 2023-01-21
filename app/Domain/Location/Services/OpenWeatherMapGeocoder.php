<?php

declare(strict_types=1);

namespace App\Domain\Location\Services;

use App\Domain\Location\Contracts\GeocodingService;
use App\Domain\Location\Exceptions\GeocodingException;
use App\Domain\Location\ValueObjects\Coordinates;
use App\Libs\OpenWeatherMap\Clients\GeocodingClient;
use App\Libs\OpenWeatherMap\Exceptions\OpenWeatherMapClientException;
use Assert\AssertionFailedException;

class OpenWeatherMapGeocoder implements GeocodingService
{
    private GeocodingClient $client;

    public function __construct(GeocodingClient $client)
    {
        $this->client = $client;
    }

    /**
     * @throws GeocodingException
     */
    public function geocodeLocation(string $location): Coordinates
    {
        try {
            $result = $this->client->getFirstByQuery($location);

            if ($result === null) {
                throw new GeocodingException('Location not found');
            }

            return new Coordinates($result->lon, $result->lat);
        } catch (AssertionFailedException $exception) {
            throw new GeocodingException('Could not parse geocoded data', previous: $exception);
        } catch (OpenWeatherMapClientException $exception) {
            throw new GeocodingException('Error communicating with geocoding service', previous: $exception);
        }
    }
}
