<?php

declare(strict_types=1);

namespace App\Domain\Weather\ValueObjects;

use App\Domain\Weather\ValueObjects\Pressure\Pressure;
use App\Domain\Weather\ValueObjects\Temperature\Temperature;

readonly class WeatherData
{
    public function __construct(
        public Temperature $temperature,
        public Temperature $feelsLike,
        public Pressure $pressure,
    ) {
    }
}
