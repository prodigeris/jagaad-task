<?php

declare(strict_types=1);

namespace JagaadTask\Components\Musement\Transformer;

use JagaadTask\Components\Musement\Dto\City;
use JagaadTask\Components\Musement\Dto\CityCollection;
use JsonException;
use Psr\Http\Message\ResponseInterface;
use Test\Unit\Components\Musement\Exception\InvalidResponseException;

class ResponseTransformer
{
    /**
     * @return CityCollection|City[]
     *
     * @throws InvalidResponseException
     */
    public function transformCities(ResponseInterface $response): CityCollection
    {
        $json = $this->decodeResponse($response);

        return new CityCollection();
    }

    /**
     * @throws InvalidResponseException
     *
     * @return array|string[]
     */
    protected function decodeResponse(ResponseInterface $response): array
    {
        try {
            return json_decode(
                $response->getBody()->getContents(),
                true,
                512,
                JSON_THROW_ON_ERROR
            );
        } catch (JsonException $e) {
            throw new InvalidResponseException($e->getMessage());
        }
    }
}
