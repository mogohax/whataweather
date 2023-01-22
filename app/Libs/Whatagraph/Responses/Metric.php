<?php

declare(strict_types=1);

namespace App\Libs\Whatagraph\Responses;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

class Metric extends Data
{
    public function __construct(
        readonly public int $id,
        #[MapInputName('external_id')]
        readonly public string $externalId,
        readonly public string $name,
    ) {
    }
}
