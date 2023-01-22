<?php

declare(strict_types=1);

namespace App\Libs\Whatagraph\Clients;

use App\Libs\Whatagraph\Requests\AddData as AddDataRequest;
use App\Libs\Whatagraph\Requests\AddMetric as AddMetricRequest;
use App\Libs\Whatagraph\Responses\AddData as AddDataResponse;
use App\Libs\Whatagraph\Responses\AddMetric as AddMetricResponse;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Spatie\LaravelData\DataCollection;

class Whatagraph
{
    private const BASE_URL = 'https://api.whatagraph.com/v1/';

    private string $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @throws RequestException
     */
    public function addMetrics(AddMetricRequest $metric): AddMetricResponse
    {
        $response = $this->makeRequest()->post('integration-metrics', $metric->toArray());

        return AddMetricResponse::from($response->json());
    }

    /**
     * @param AddDataRequest[] $items
     *
     * @throws RequestException
     *
     * @return DataCollection
     */
    public function addData(array $items): DataCollection
    {
        $formattedData = array_map(fn ($item) => [
            $item->metricName => $item->metricData,
            'date' => $item->date->toDateString(),
        ], $items);

        $response = $this->makeRequest()->post('integration-source-data', [
            'data' => $formattedData,
        ]);

        return AddDataResponse::collection($response->json('data'));
    }

    private function makeRequest(): PendingRequest
    {
        return Http::withHeaders([
            'Authorization' => "Bearer $this->apiKey"
        ])
            ->baseUrl(self::BASE_URL)
            ->throw();
    }
}
