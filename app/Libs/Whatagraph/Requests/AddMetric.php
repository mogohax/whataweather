<?php

declare(strict_types=1);

namespace App\Libs\Whatagraph\Requests;

use App\Libs\Whatagraph\Enums\MetricAccumulator;
use App\Libs\Whatagraph\Enums\MetricType;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;

class AddMetric extends Data
{
    public function __construct(
        readonly public string $name,
        #[MapOutputName('external_id')]
        readonly public string $externalId,
        readonly public MetricType $type,
        readonly public MetricAccumulator $accumulator,
        #[MapOutputName('negative_ratio')]
        readonly public bool $negativeRatio,
    ) {
    }
}
