<?php

declare(strict_types=1);

namespace App\Domain\Location\Contracts;

use App\Domain\Location\Exceptions\GeocodingException;
use App\Domain\Location\ValueObjects\Coordinates;

interface GeocodingService
{
    /**
     * @throws GeocodingException
     */
    public function geocodeLocation(string $location): Coordinates;
}
