<?php

declare(strict_types=1);

namespace App\Providers\Libs\Whatagraph;

use App\Libs\Whatagraph\Clients\Whatagraph;
use Illuminate\Support\ServiceProvider;

class WhatagraphClientProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app
            ->when(Whatagraph::class)
            ->needs('$apiKey')
            ->giveConfig('whatagraph.api_key');
    }
}
