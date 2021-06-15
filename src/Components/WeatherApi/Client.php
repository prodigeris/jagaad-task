<?php

declare(strict_types=1);

namespace JagaadTask\Components\WeatherApi;

use GuzzleHttp\ClientInterface;
use JagaadTask\Components\WeatherApi\Dto\Forecast;
use JagaadTask\Components\WeatherApi\ValueObject\Coordinates;

class Client
{
    public const GET = 'GET';

    private ClientInterface $http;

    private string $baseUri;

    private string $key;

    public function __construct(ClientInterface $http, string $baseUri, string $key)
    {
        $this->http = $http;
        $this->baseUri = $baseUri;
        $this->key = $key;
    }

    public function getForecast(Coordinates $coordinates, int $days): Forecast
    {
        $this->http->request(self::GET, $this->getForecastUri($coordinates, $days));
        return new Forecast();
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
}
