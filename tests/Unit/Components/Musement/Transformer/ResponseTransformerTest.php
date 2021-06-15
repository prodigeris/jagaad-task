<?php

declare(strict_types=1);

namespace Test\Unit\Components\Musement\Transformer;

use GuzzleHttp\Psr7\Response;
use JagaadTask\Components\Musement\Exception\InvalidResponseException;
use JagaadTask\Components\Musement\Factory\CityFactory;
use JagaadTask\Components\Musement\Transformer\ResponseTransformer;
use JagaadTask\Components\Musement\ValueObject\City;
use JagaadTask\Components\Musement\ValueObject\CityCollection;
use JsonException;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class ResponseTransformerTest extends TestCase
{
    use ProphecyTrait;

    private const ID = 1111;

    private const ID_2 = 2222;

    private const CITY_1 = 'Amsterdam';

    private const CITY_2 = 'Milan';

    private const LATITUDE_1 = 41.898;

    private const LONGITUDE_1 = 4.9;

    private const LATITUDE_2 = 45.459;

    private const LONGITUDE_2 = 9.183;

    private ResponseTransformer $transformer;

    private CityFactory $cityFactory;

    protected function setUp(): void
    {
        $this->cityFactory = new CityFactory();

        $this->transformer = new ResponseTransformer(
            $this->cityFactory,
        );
    }

    public function testTransformCitiesThrowsInvalidResponseExceptionWhenResponseIsInvalid(): void
    {
        $this->expectException(InvalidResponseException::class);

        $this->transformer->transformCities(new Response());
    }

    /**
     * @dataProvider cityArrayDataProvider
     * @param array|array[] $cities
     * @param CityCollection|City[] $collection
     *
     * @throws JsonException
     */
    public function testTransformCitiesBuildsCitiesWithCorrectDetails(array $cities, CityCollection $collection): void
    {
        $response = $this->buildResponse($cities);

        $result = $this->transformer->transformCities($response);

        self::assertSame($collection, $result);
    }

    /**
     * @return array[]|array
     */
    public function cityArrayDataProvider(): array
    {
        return [
            [
                [[
                    'id' => self::ID,
                    'name' => self::CITY_1,
                    'latitude' => self::LATITUDE_1,
                    'longitude' => self::LONGITUDE_1,
                ], [
                    'id' => self::ID_2,
                    'name' => self::CITY_2,
                    'latitude' => self::LATITUDE_2,
                    'longitude' => self::LONGITUDE_2,
                ]],
                new CityCollection(
                    new City(self::ID, self::CITY_1, self::LATITUDE_1, self::LONGITUDE_1),
                    new City(self::ID_2, self::CITY_2, self::LATITUDE_2, self::LONGITUDE_2),
                ),
            ],
        ];
    }

    /**
     * @param array|array[] $cities
     * @throws JsonException
     */
    protected function buildResponse(array $cities): Response
    {
        return new Response(200, [], json_encode($cities, JSON_THROW_ON_ERROR));
    }
}
