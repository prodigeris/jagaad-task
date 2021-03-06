<?php

declare(strict_types=1);

namespace JagaadTask\Components\Musement\Transformer;

use JagaadTask\Components\Musement\Exception\InvalidResponseException;
use JagaadTask\Components\Musement\Factory\CityFactory;
use JagaadTask\Components\Musement\ValueObject\City;
use JagaadTask\Components\Musement\ValueObject\CityCollection;
use JsonException;
use Psr\Http\Message\ResponseInterface;

class ResponseTransformer
{
    private CityFactory $cityFactory;

    public function __construct(CityFactory $cityFactory)
    {
        $this->cityFactory = $cityFactory;
    }

    /**
     * @return CityCollection|City[]
     *
     * @throws InvalidResponseException
     */
    public function transformCities(ResponseInterface $response): CityCollection
    {
        $array = $this->decodeResponse($response);
        $cities = $this->buildCities($array);

        return new CityCollection(...$cities);
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

    /**
     * @param array|string[] $array
     * @return array|City[]
     */
    protected function buildCities(array $array): array
    {
        return array_map(fn (array $city) => $this->cityFactory->build(
            $city['id'],
            $city['name'],
            (float) $city['latitude'],
            (float) $city['longitude']
        ), $array);
    }
}
