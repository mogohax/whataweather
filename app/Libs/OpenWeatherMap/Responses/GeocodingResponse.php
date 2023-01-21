<?php

declare(strict_types=1);

namespace App\Libs\OpenWeatherMap\Responses;

use Spatie\LaravelData\Data;

class GeocodingResponse extends Data
{
    public function __construct(
        readonly public string $name,
        readonly public float $lon,
        readonly public float $lat,
    ) {
    }
}
