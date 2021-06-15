<?php

declare(strict_types=1);

namespace Test\Unit\Components\WeatherApi;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use JagaadTask\Components\WeatherApi\Client;
use JagaadTask\Components\WeatherApi\Dto\Forecast;
use JagaadTask\Components\WeatherApi\Transformer\ResponseTransformer;
use JagaadTask\Components\WeatherApi\ValueObject\Coordinates;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class ClientTest extends TestCase
{
    use ProphecyTrait;

    public const LATITUDE = 11.22;

    public const LONGITUDE = 33.44;

    public const DAYS = 2;

    public const BASE_URI = 'http://api.weatherapi.test/v1';

    public const FORECAST_ENDPOINT = 'http://api.weatherapi.test/v1/forecast.json?key=abcd&q=11.22,33.44&days=2';

    public const GET = 'GET';

    private const API_KEY = 'abcd';

    private Client $client;

    /**
     * @var ClientInterface|ObjectProphecy
     */
    private $http;

    private Coordinates $coordinates;

    /**
     * @var ResponseTransformer|ObjectProphecy
     */
    private $transformer;

    private Forecast $forecast;

    protected function setUp(): void
    {
        $this->http = $this->prophesize(ClientInterface::class);
        $this->transformer = $this->prophesize(ResponseTransformer::class);

        $this->buildSut();

        $this->coordinates = new Coordinates(self::LATITUDE, self::LONGITUDE);

        $this->stubMethods();
    }

    public function testGetForecastShouldRequestCorrectEndpoint(): void
    {
        $this->client->getForecast($this->coordinates, self::DAYS);

        $this
            ->http
            ->request(self::GET, self::FORECAST_ENDPOINT)
            ->shouldHaveBeenCalledOnce();
    }

    public function testGetForecastReturnForecast(): void
    {
        $actual = $this->client->getForecast($this->coordinates, self::DAYS);

        self::assertSame($this->forecast, $actual);
    }

    protected function stubMethods(): void
    {
        $this->http
            ->request(Argument::cetera())
            ->willReturn(new Response());

        $this->forecast = new Forecast();
        $this->transformer->transformForecast(Argument::cetera())->willReturn($this->forecast);
    }

    protected function buildSut(): void
    {
        $this->client = new Client(
            $this->http->reveal(),
            $this->transformer->reveal(),
            self::BASE_URI,
            self::API_KEY,
        );
    }
}
