<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Weather\Normalizers;

use App\Domain\Location\ValueObjects\Coordinates;
use App\Domain\Weather\Normalizers\CoordinateNormalizer;
use PHPUnit\Framework\TestCase;

class CoordinateNormalizerTest extends TestCase
{
    private CoordinateNormalizer $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new CoordinateNormalizer();
    }

    /**
     * @dataProvider coordinatesProvider
     */
    public function testAsCacheKeyNormalizesCoordinates(
        Coordinates $coordinates,
        int $decimals,
        string $expectedResult
    ): void {
        // Action
        $result = $this->service->asCacheKey($coordinates, $decimals);

        // Assertions
        $this->assertSame($expectedResult, $result);
    }

    protected function coordinatesProvider(): array
    {
        return [
            'limits decimals to 1 place' => [
                'coordinates' => new Coordinates(11.12345, 22.23456),
                'decimals' => 1,
                'expected_result' => '11.1_22.2',
            ],
            'fills decimals with zeros to 1 place' => [
                'coordinates' => new Coordinates(11, 22),
                'decimals' => 1,
                'expected_result' => '11.0_22.0',
            ],

            'limits decimals to 2 places' => [
                'coordinates' => new Coordinates(11.12345, 22.23456),
                'decimals' => 2,
                'expected_result' => '11.12_22.23',
            ],
            'fills decimals with zeros to 2 places' => [
                'coordinates' => new Coordinates(11, 22),
                'decimals' => 2,
                'expected_result' => '11.00_22.00',
            ],

            'limits decimals to 3 places' => [
                'coordinates' => new Coordinates(11.12345, 22.23456),
                'decimals' => 3,
                'expected_result' => '11.123_22.234',
            ],
            'fills decimals with zeros to 3 places' => [
                'coordinates' => new Coordinates(11, 22),
                'decimals' => 3,
                'expected_result' => '11.000_22.000',
            ],

            'limits decimals without rounding up' => [
                'coordinates' => new Coordinates(11.12999, 22.23999),
                'decimals' => 2,
                'expected_result' => '11.12_22.23',
            ],
        ];
    }
}
