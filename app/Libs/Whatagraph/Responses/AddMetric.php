<?php

declare(strict_types=1);

namespace App\Libs\Whatagraph\Responses;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

class AddMetric extends Data
{
    public function __construct(
        #[MapInputName('data')]
        readonly public Metric $metric
    ) {
    }
}
