<?php

declare(strict_types=1);

namespace App\Domain\Weather\Contracts;

use App\Domain\Location\ValueObjects\Coordinates;
use App\Domain\Weather\Exceptions\WeatherResolverException;
use App\Domain\Weather\ValueObjects\WeatherData;

interface WeatherResolver
{
    /**
     * @throws WeatherResolverException
     */
    public function resolveCurrent(Coordinates $coordinates): WeatherData;
}
