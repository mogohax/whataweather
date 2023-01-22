<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Weather\Services;

use App\Domain\Location\ValueObjects\Coordinates;
use App\Domain\Weather\Exceptions\WeatherResolverException;
use App\Domain\Weather\Services\OpenWeatherMapResolver;
use App\Domain\Weather\ValueObjects\Pressure\Units as PressureUnits;
use App\Domain\Weather\ValueObjects\Temperature\Units as TemperatureUnits;
use App\Libs\OpenWeatherMap\Clients\WeatherClient;
use App\Libs\OpenWeatherMap\Exceptions\OpenWeatherMapClientException;
use App\Libs\OpenWeatherMap\Responses\Weather\CurrentWeather;
use App\Libs\OpenWeatherMap\Responses\Weather\OneCallResponse;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

class OpenWeatherMapResolverTest extends TestCase
{
    private MockObject $weatherClient;
    private OpenWeatherMapResolver $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->weatherClient = $this->createMock(WeatherClient::class);

        $this->service = new OpenWeatherMapResolver($this->weatherClient, new NullLogger());
    }

    public function testResolveCurrentQueriesAndParsesDataFromWeatherClient(): void
    {
        // Data
        $coordinates = new Coordinates(25, 55);
        $weatherResponse = new OneCallResponse(new CurrentWeather(0.0, -3.0, 1021.0));

        // Mocks
        $this
            ->weatherClient
            ->expects($this->once())
            ->method('oneCall')
            ->with(
                25,
                55,
                [
                    WeatherClient::EXCLUDE_MINUTELY,
                    WeatherClient::EXCLUDE_HOURLY,
                    WeatherClient::EXCLUDE_DAILY,
                    WeatherClient::EXCLUDE_ALERTS,
                ]
            )
            ->willReturn($weatherResponse);

        // Action
        $result = $this->service->resolveCurrent($coordinates);

        // Assertions
        $this->assertSame(0.0, $result->temperature->value);
        $this->assertSame(TemperatureUnits::CELSIUS, $result->temperature->units);

        $this->assertSame(-3.0, $result->feelsLike->value);
        $this->assertSame(TemperatureUnits::CELSIUS, $result->feelsLike->units);

        $this->assertSame(1021.0, $result->pressure->value);
        $this->assertSame(PressureUnits::HECTOPASCALS, $result->pressure->units);
    }

    public function testResolveCurrentThrowsDomainExceptionWhenApiRequestFails(): void
    {
        // Mocks
        $this
            ->weatherClient
            ->method('oneCall')
            ->willThrowException(new OpenWeatherMapClientException('API is down'));

        // Assertions
        $this->expectException(WeatherResolverException::class);

        // Action
        $this->service->resolveCurrent(new Coordinates(0, 0));
    }

    public function testResolveCurrentThrowsDomainExceptionWhenApiRespondsInvalidData(): void
    {
        $invalidDataResponse = new OneCallResponse(new CurrentWeather(-999.0, -999.0, -999.0));

        // Mocks
        $this
            ->weatherClient
            ->method('oneCall')
            ->willReturn($invalidDataResponse);

        // Assertions
        $this->expectException(WeatherResolverException::class);

        // Action
        $this->service->resolveCurrent(new Coordinates(0, 0));
    }
}
