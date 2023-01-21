<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Location\Services;

use App\Domain\Location\Contracts\GeocodingService;
use App\Domain\Location\Services\CachingGeocoder;
use App\Domain\Location\ValueObjects\Coordinates;
use Illuminate\Support\Facades\Cache;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

class CachingGeocoderTest extends TestCase
{
    private CachingGeocoder $service;
    private MockObject $decoratedService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->decoratedService = $this->createMock(GeocodingService::class);

        $this->service = new CachingGeocoder($this->decoratedService, new NullLogger());
    }

    public function testGeocodeLocationReturnsKnownValueFromCacheWithoutCallingDecoratedService(): void
    {
        // Data
        $coordinates = new Coordinates(23.3157775, 55.9340823);

        // Mocks
        Cache::shouldReceive('rememberForever')
            ->once()
            ->withSomeOfArgs('siauliai')
            ->andReturn($coordinates);

        // Action
        $result = $this->service->geocodeLocation('Siauliai');

        // Assertions
        $this->decoratedService->expects($this->never())->method('geocodeLocation');
        $this->assertSame($coordinates->latitude, $result->latitude);
        $this->assertSame($coordinates->longitude, $result->longitude);
    }

    /**
     * @dataProvider locationDataProvider
     */
    public function testGeocodeLocationUsesCaseInsensitiveCacheKeys($location): void
    {
        // Data
        $coordinates = new Coordinates(23.3157775, 55.9340823);

        // Mocks
        Cache::shouldReceive('rememberForever')
            ->once()
            ->withSomeOfArgs('siauliai')
            ->andReturn($coordinates);

        // Action
        $this->service->geocodeLocation($location);

        // Assertions
        $this->decoratedService->expects($this->never())->method('geocodeLocation');
    }

    protected function locationDataProvider(): array
    {
        return [['SIAULIAI'], ['siauliai'], ['sIaUlIai'], ['SIAUliai']];
    }
}
