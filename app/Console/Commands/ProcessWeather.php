<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\GeolocateCoordinates;
use Illuminate\Console\Command;

class ProcessWeather extends Command
{
    protected $signature = 'weather:process {locations* : List of location names}';
    protected $description = 'Process weather forecasts of given location names';

    public function handle(): int
    {
        $locations = $this->argument('locations');

        foreach ($locations as $location) {
            $this->info("Processing weather for $location");

            GeolocateCoordinates::dispatch($location);
        }

        return self::SUCCESS;
    }
}
