<?php

declare(strict_types=1);

namespace Test\Unit\Components\Musement\Transformer;

use GuzzleHttp\Psr7\Response;
use JagaadTask\Components\Musement\Dto\City;
use JagaadTask\Components\Musement\Factory\CityFactory;
use JagaadTask\Components\Musement\Transformer\ResponseTransformer;
use JsonException;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Test\Unit\Components\Musement\Exception\InvalidResponseException;

class ResponseTransformerTest extends TestCase
{
    use ProphecyTrait;

    private ResponseTransformer $transformer;

    /**
     * @var CityFactory|ObjectProphecy
     */
    private $cityFactory;

    protected function setUp(): void
    {
        $this->cityFactory = $this->prophesize(CityFactory::class);

        $this->transformer = new ResponseTransformer(
            $this->cityFactory->reveal(),
        );
    }

    public function testTransformCitiesThrowsInvalidResponseExceptionWhenResponseIsInvalid(): void
    {
        $this->expectException(InvalidResponseException::class);

        $this->transformer->transformCities(new Response());
    }

    /**
     * @throws JsonException
     */
    public function testTransformCitiesBuildsCitiesWithCorrectDetails(): void
    {
        $response = $this->buildExampleResponse();
        $this->cityFactory->build()->willReturn(new City());

        $this->transformer->transformCities($response);

        $this->cityFactory
            ->build()
            ->shouldHaveBeenCalledTimes(2);
    }

    /**
     * @throws JsonException
     */
    protected function buildExampleResponse(): Response
    {
        return new Response(200, [], json_encode([[], []], JSON_THROW_ON_ERROR));
    }
}
