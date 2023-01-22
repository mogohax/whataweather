<?php

declare(strict_types=1);

namespace App\Libs\OpenWeatherMap\Clients;

use App\Libs\OpenWeatherMap\Exceptions\OpenWeatherMapClientException;
use App\Libs\OpenWeatherMap\Responses\Weather\OneCallResponse;
use GuzzleHttp\Command\Guzzle\GuzzleClient;
use Psr\Log\LoggerInterface;
use Throwable;

class WeatherClient
{
    public const EXCLUDE_MINUTELY = 'minutely';
    public const EXCLUDE_HOURLY = 'hourly';
    public const EXCLUDE_DAILY = 'daily';
    public const EXCLUDE_ALERTS = 'alerts';

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
     */
    public function oneCall(float $longitude, float $latitude, array $exclude = []): OneCallResponse
    {
        try {
            $command = $this->client->getCommand('oneCall', [
                'lat' => $latitude,
                'lon' => $longitude,
                'exclude' => implode(',', $exclude),
                'units' => 'metric',
                'appid' => $this->apiKey,
            ]);

            $result = $this->client->execute($command);
        } catch (Throwable $exception) {
            $this->logger->error('Could not execute oneCall command', [
                'exception' => $exception,
            ]);

            throw new OpenWeatherMapClientException('OneCall request failed', previous: $exception);
        }

        return OneCallResponse::from($result->toArray());
    }
}
