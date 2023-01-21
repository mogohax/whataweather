<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Domain\Location\Contracts\GeocodingService;
use App\Events\CoordinatesFetched;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GeolocateCoordinates implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    readonly private string $location;

    public function __construct(string $location)
    {
        $this->location = $location;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function handle(GeocodingService $geocodingService): void
    {
        $coordinates = $geocodingService->geocodeLocation($this->location);

        CoordinatesFetched::dispatch($this->location, $coordinates);
    }
}
