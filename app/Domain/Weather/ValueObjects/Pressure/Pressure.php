<?php

declare(strict_types=1);

namespace App\Domain\Weather\ValueObjects\Pressure;

readonly class Pressure
{
    private function __construct(
        public float $value,
        public Units $units
    ) {
    }

    public static function hPa(float $value): self
    {
        return new self($value, Units::HECTOPASCALS);
    }
}
