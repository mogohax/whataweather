<?php

declare(strict_types=1);

namespace App\Domain\Weather\ValueObjects\Temperature;

use Assert\Assertion;
use Assert\AssertionFailedException;

readonly class Temperature
{
    private const ABSOLUTE_ZERO_C = -273.15;

    private function __construct(
        public float $value,
        public Units $units
    ) {
    }

    /**
     * @throws AssertionFailedException
     */
    public static function celsius(float $value): self
    {
        Assertion::min($value, self::ABSOLUTE_ZERO_C);

        return new self($value, Units::CELSIUS);
    }
}
