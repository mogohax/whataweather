<?php

declare(strict_types=1);

namespace App\Libs\OpenWeatherMap\Responses\Weather;

use Spatie\LaravelData\Data;

class OneCallResponse extends Data
{
    public function __construct(readonly public CurrentWeather $current)
    {
    }
}
