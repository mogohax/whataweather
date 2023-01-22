<?php

declare(strict_types=1);

namespace App\Libs\Whatagraph\Responses;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

class AddData extends Data
{
    public function __construct(
        readonly public string $id,
        readonly public string $date,
        #[MapInputName('integration_data')]
        readonly public array $integrationData,
    ) {
    }
}
