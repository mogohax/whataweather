<?php

declare(strict_types=1);

namespace App\Libs\Whatagraph\Requests;

use Carbon\CarbonImmutable;
use Spatie\LaravelData\Data;

class AddData extends Data
{
    public function __construct(
        readonly public string $metricName,
        readonly public string $metricData,
        readonly public CarbonImmutable $date,
    ) {
    }
}
