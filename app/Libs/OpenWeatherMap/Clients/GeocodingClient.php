<?php

declare(strict_types=1);

namespace App\Libs\OpenWeatherMap\Clients;

use App\Libs\OpenWeatherMap\Exceptions\OpenWeatherMapClientException;
use App\Libs\OpenWeatherMap\Responses\GeocodingResponse;
use GuzzleHttp\Command\Guzzle\GuzzleClient;
use Psr\Log\LoggerInterface;
use Spatie\LaravelData\DataCollection;
use Throwable;

class GeocodingClient
{
    private GuzzleClient $client;
    private LoggerInterface $logger;
    private string $apiKey;

    public function __construct(
        GuzzleClient $client,
        LoggerInterface $logger,
        string $apiKey
    ) {
        $this->client = $client;
        $this->logger = $logger;
        $this->apiKey = $apiKey;
    }

    /**
     * @throws OpenWeatherMapClientException
     *
     * @return DataCollection<int, GeocodingResponse>
     */
    public function getByQuery(string $query, int $limit = null): DataCollection
    {
        try {
            $command = $this->client->getCommand('getDirect', [
                'q' => $query,
                'limit' => $limit,
                'appid' => $this->apiKey,
            ]);

            $result = $this->client->execute($command);
        } catch (Throwable $exception) {
            $this->logger->error('Could not execute getDirect command', [
                'exception' => $exception,
            ]);

            throw new OpenWeatherMapClientException('Geocoding request failed', previous: $exception);
        }

        return GeocodingResponse::collection($result->toArray());
    }

    /**
     * @throws OpenWeatherMapClientException
     */
    public function getFirstByQuery(string $query): ?GeocodingResponse
    {
        return $this->getByQuery($query, 1)->first();
    }
}
