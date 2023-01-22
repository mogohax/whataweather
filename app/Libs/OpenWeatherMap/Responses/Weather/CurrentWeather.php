<?php

declare(strict_types=1);

namespace App\Libs\OpenWeatherMap\Responses\Weather;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

class CurrentWeather extends Data
{
    public function __construct(
        readonly public float $temp,
        #[MapInputName('feels_like')]
        readonly public float $feelsLike,
        readonly public float $pressure,
    ) {
    }
}
