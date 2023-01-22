<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Libs\Whatagraph\Clients\Whatagraph;
use App\Libs\Whatagraph\Enums\MetricAccumulator;
use App\Libs\Whatagraph\Enums\MetricType;
use App\Libs\Whatagraph\Requests\AddMetric;
use Illuminate\Console\Command;

class InitWGMetrics extends Command
{
    protected $signature = 'wg:init-metrics';

    protected $description = 'Initializes WG metrics';
    private Whatagraph $client;

    public function __construct(Whatagraph $client)
    {
        parent::__construct();

        $this->client = $client;
    }

    public function handle(): int
    {
        $this->client->addMetrics(new AddMetric(
            'Temperature',
            'temperature',
            MetricType::FLOAT,
            MetricAccumulator::AVERAGE,
            true
        ));

        $this->client->addMetrics(new AddMetric(
            'Feels Like',
            'feels_like',
            MetricType::FLOAT,
            MetricAccumulator::AVERAGE,
            true
        ));

        $this->client->addMetrics(new AddMetric(
            'Pressure',
            'pressure',
            MetricType::FLOAT,
            MetricAccumulator::AVERAGE,
            true
        ));

        $this->info('Added metrics');

        return Command::SUCCESS;
    }
}
