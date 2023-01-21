<?php

declare(strict_types=1);

namespace App\Events;

use App\Domain\Location\ValueObjects\Coordinates;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CoordinatesFetched
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        readonly public string $location,
        readonly public Coordinates $coordinates,
    ) {
    }
}
