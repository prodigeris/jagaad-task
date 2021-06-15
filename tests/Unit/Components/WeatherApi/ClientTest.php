<?php

declare(strict_types=1);

namespace Test\Unit\Components\WeatherApi;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use JagaadTask\Components\WeatherApi\Client;
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

    protected function setUp(): void
    {
        $this->http = $this->prophesize(ClientInterface::class);

        $this->client = new Client($this->http->reveal(), self::BASE_URI, self::API_KEY);

        $this->coordinates = new Coordinates(self::LATITUDE, self::LONGITUDE);
    }

    public function testGetForecastShouldRequestCorrectEndpoint(): void
    {
        $this->http
            ->request(Argument::cetera())
            ->willReturn(new Response());

        $this->client->getForecast($this->coordinates, self::DAYS);

        $this
            ->http
            ->request(self::GET, self::FORECAST_ENDPOINT)
            ->shouldHaveBeenCalledOnce();
    }
}
