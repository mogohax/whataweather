<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Location\Services;

use App\Domain\Location\Exceptions\GeocodingException;
use App\Domain\Location\Services\OpenWeatherMapGeocoder;
use App\Libs\OpenWeatherMap\Clients\GeocodingClient;
use App\Libs\OpenWeatherMap\Exceptions\OpenWeatherMapClientException;
use App\Libs\OpenWeatherMap\Responses\GeocodingResponse;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Log\NullLogger;
use Tests\TestCase;

class OpenWeatherMapGeocoderTest extends TestCase
{
    protected MockObject $geocodingClient;
    protected OpenWeatherMapGeocoder $service;

    protected function setUp(): void
    {
        $this->geocodingClient = $this->createMock(GeocodingClient::class);

        $this->service = new OpenWeatherMapGeocoder($this->geocodingClient, new NullLogger());

        parent::setUp();
    }

    public function testGeocodeLocationReturnsCoordinatesWhenClientSucceeds(): void
    {
        // Data
        $clientResponse = new GeocodingResponse('Vilnius', 25.2829111, 54.6870458);

        // Mocks
        $this
            ->geocodingClient
            ->method('getFirstByQuery')
            ->willReturn($clientResponse);

        // Action
        $coordinates = $this->service->geocodeLocation('Vilnius');

        // Assertions
        $this->assertSame($clientResponse->lat, $coordinates->latitude);
        $this->assertSame($clientResponse->lon, $coordinates->longitude);
    }

    public function testGeocodeLocationThrowsExceptionWhenThereAreNoResults(): void
    {
        // Mocks
        $this
            ->geocodingClient
            ->method('getFirstByQuery')
            ->willReturn(null);

        // Assertions
        $this->expectException(GeocodingException::class);

        // Action
        $this->service->geocodeLocation('Narnia');
    }

    public function testGeocodeLocationConvertsClientExceptionIntoDomainException(): void
    {
        // Mocks
        $this
            ->geocodingClient
            ->method('getFirstByQuery')
            ->willThrowException(new OpenWeatherMapClientException());

        // Assertions
        $this->expectException(GeocodingException::class);

        // Action
        $this->service->geocodeLocation('Vilnius');
    }
}
