<?php

declare(strict_types=1);

namespace JagaadTask\Components\Musement;

use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Test\Unit\Components\Musement\Dto\City;
use Test\Unit\Components\Musement\Dto\CityCollection;

class Client
{
    private ClientInterface $http;

    private string $baseUri;

    public function __construct(ClientInterface $http, string $baseUri)
    {
        $this->http = $http;
        $this->baseUri = $baseUri;
    }

    /**
     * @return CityCollection|City[]
     */
    public function getCities(): CityCollection
    {
        $this->sendRequest('GET', 'cities');

        return new CityCollection();
    }

    private function getPath(string $path): string
    {
        return sprintf('%s/%s', $this->baseUri, $path);
    }

    private function sendRequest(string $method, string $path): ResponseInterface
    {
        return $this->http->request($method, $this->getPath($path));
    }
}
