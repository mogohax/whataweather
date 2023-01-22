<?php

declare(strict_types=1);

namespace App\Events;

use App\Domain\Location\ValueObjects\Coordinates;
use App\Domain\Weather\ValueObjects\WeatherData;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WeatherFetched
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        readonly public Coordinates $coordinates,
        readonly public WeatherData $weather
    ) {
    }
}
