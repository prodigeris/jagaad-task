<?php

declare(strict_types=1);

namespace Test\Unit\Components\Musement;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Response;
use JagaadTask\Components\Musement\Client;
use JagaadTask\Components\Musement\Exception\RequestFailedException;
use JagaadTask\Components\Musement\Transformer\ResponseTransformer;
use JagaadTask\Components\Musement\ValueObject\City;
use JagaadTask\Components\Musement\ValueObject\CityCollection;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class ClientTest extends TestCase
{
    use ProphecyTrait;

    public const CITIES_ENDPOINT = 'https://musement.sandbox/cities.json';

    public const GET = 'GET';

    private const BASE_URI = 'https://musement.sandbox';

    private Client $client;

    /**
     * @var ClientInterface|ObjectProphecy
     */
    private $http;

    /**
     * @var ResponseTransformer|ObjectProphecy
     */
    private $transformer;

    /**
     * @var CityCollection|City[]
     */
    private CityCollection $cities;

    protected function setUp(): void
    {
        $this->http = $this->prophesize(ClientInterface::class);
        $this->transformer = $this->prophesize(ResponseTransformer::class);

        $this->client = new Client(
            $this->http->reveal(),
            $this->transformer->reveal(),
            self::BASE_URI,
        );

        $this->cities = new CityCollection();

        $this->stubDefaults();
    }

    public function testGetCitiesShouldSendRequestToCitiesEndpoint(): void
    {
        $this->client->getCities();

        $this->http
            ->request(self::GET, self::CITIES_ENDPOINT)
            ->shouldHaveBeenCalledOnce()
            ->willReturn(new Response());
    }

    public function testGetCitiesShouldReturnCollectionOfCities(): void
    {
        $result = $this->client->getCities();

        self::assertSame($this->cities, $result);
    }

    public function testGetCitiesShouldThrowRequestFailedExceptionWhenHttpClientFails(): void
    {
        $this->http
            ->request(Argument::cetera())
            ->willThrow(RequestException::class);

        $this->expectException(RequestFailedException::class);

        $this->client->getCities();
    }

    protected function stubDefaults(): void
    {
        $this->http
            ->request(Argument::cetera())
            ->willReturn(new Response());

        $this->transformer
            ->transformCities(Argument::any())
            ->willReturn($this->cities);
    }
}
