<?php

declare(strict_types=1);

namespace JagaadTask\Components\Musement;

use GuzzleHttp\ClientInterface;
use JagaadTask\Components\Musement\Dto\City;
use JagaadTask\Components\Musement\Dto\CityCollection;
use JagaadTask\Components\Musement\Transformer\ResponseTransformer;
use Psr\Http\Message\ResponseInterface;

class Client
{
    private ClientInterface $http;

    private ResponseTransformer $transformer;

    private string $baseUri;

    public function __construct(ClientInterface $http, ResponseTransformer $transformer, string $baseUri)
    {
        $this->http = $http;
        $this->transformer = $transformer;
        $this->baseUri = $baseUri;
    }

    /**
     * @return CityCollection|City[]
     */
    public function getCities(): CityCollection
    {
        return $this->transformer->transformCities(
            $this->sendRequest('GET', 'cities.json')
        );
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
