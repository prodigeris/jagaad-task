<?php

declare(strict_types=1);

namespace JagaadTask\Components\Musement;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use JagaadTask\Components\Musement\Exception\InvalidResponseException;
use JagaadTask\Components\Musement\Exception\RequestFailedException;
use JagaadTask\Components\Musement\Transformer\ResponseTransformer;
use JagaadTask\Components\Musement\ValueObject\City;
use JagaadTask\Components\Musement\ValueObject\CityCollection;
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
     *
     * @throws InvalidResponseException
     * @throws RequestFailedException
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

    /**
     * @throws RequestFailedException
     */
    private function sendRequest(string $method, string $path): ResponseInterface
    {
        try {
            return $this->http->request($method, $this->getPath($path));
        } catch (GuzzleException $e) {
            throw new RequestFailedException($e->getMessage());
        }
    }
}
