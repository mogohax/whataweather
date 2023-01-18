<?php

declare(strict_types=1);

namespace Tests\Unit\Console\Commands;

use App\Jobs\GeolocateCoordinates;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class ProcessWeatherCommandTest extends TestCase
{

    public function testDispatchesGeolocateCoordinatesJobForEachLocation(): void
    {
        // Data
        Bus::fake();

        // Action
        $this->artisan('weather:process Siauliai Vilnius Kaunas');

        // Assertions
        Bus::assertDispatched(fn (GeolocateCoordinates $job) => $job->getLocation() === 'Siauliai');
        Bus::assertDispatched(fn (GeolocateCoordinates $job) => $job->getLocation() === 'Vilnius');
        Bus::assertDispatched(fn (GeolocateCoordinates $job) => $job->getLocation() === 'Kaunas');
    }
}
