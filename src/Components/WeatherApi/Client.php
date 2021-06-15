<?php

declare(strict_types=1);

namespace JagaadTask\Components\WeatherApi;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use JagaadTask\Components\WeatherApi\Dto\Forecast;
use JagaadTask\Components\WeatherApi\Exception\RequestFailedException;
use JagaadTask\Components\WeatherApi\Transformer\ResponseTransformer;
use JagaadTask\Components\WeatherApi\ValueObject\Coordinates;
use Psr\Http\Message\ResponseInterface;

class Client
{
    public const GET = 'GET';

    private ClientInterface $http;

    private ResponseTransformer $transformer;

    private string $baseUri;

    private string $key;

    public function __construct(ClientInterface $http, ResponseTransformer $transformer, string $baseUri, string $key)
    {
        $this->http = $http;
        $this->transformer = $transformer;
        $this->baseUri = $baseUri;
        $this->key = $key;
    }

    public function getForecast(Coordinates $coordinates, int $days): Forecast
    {
        $response = $this->getResponse($coordinates, $days);
        return $this->transformer->transformForecast($response);
    }

    protected function getForecastUri(Coordinates $coordinates, int $days): string
    {
        return sprintf(
            '%s/%s?key=%s&q=%s,%s&days=%u',
            $this->baseUri,
            'forecast.json',
            $this->key,
            $coordinates->getLatitude(),
            $coordinates->getLongitude(),
            $days
        );
    }

    protected function getResponse(Coordinates $coordinates, int $days): ResponseInterface
    {
        try {
            return $this->http->request(self::GET, $this->getForecastUri($coordinates, $days));
        } catch (GuzzleException $e) {
            throw new RequestFailedException($e->getMessage());
        }
    }
}
