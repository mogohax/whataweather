<?php

declare(strict_types=1);

namespace App\Domain\Location\ValueObjects;

use Assert\Assertion;
use Assert\AssertionFailedException;

readonly class Coordinates
{
    public float $longitude;
    public float $latitude;

    /**
     * @throws AssertionFailedException
     */
    public function __construct(float $longitude, float $latitude)
    {
        Assertion::between($longitude, -180, 180);
        Assertion::between($latitude, -90, 90);

        $this->longitude = $longitude;
        $this->latitude = $latitude;
    }
}
