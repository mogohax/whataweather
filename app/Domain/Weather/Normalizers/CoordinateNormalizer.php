<?php

declare(strict_types=1);

namespace App\Domain\Weather\Normalizers;

use App\Domain\Location\ValueObjects\Coordinates;

class CoordinateNormalizer
{
    /**
     * @param Coordinates $coordinates
     * @param positive-int $decimals
     *
     * @return string
     */
    public function asCacheKey(Coordinates $coordinates, int $decimals = 5): string
    {
        $lon = $this->formatWithoutRounding($coordinates->longitude, $decimals);
        $lat = $this->formatWithoutRounding($coordinates->latitude, $decimals);

        return "{$lon}_{$lat}";
    }

    private function formatWithoutRounding($number, int $decimals): string
    {
        $multiplier = 10**$decimals;

        return number_format(floor($number* $multiplier)/ $multiplier, $decimals, '.', '');
    }
}
