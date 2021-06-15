<?php

declare(strict_types=1);

namespace Test\Unit\Components\Musement;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use JagaadTask\Components\Musement\Client;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Prophecy\PhpUnit\ProphecyTrait;

class ClientTest extends TestCase
{
    private const BASE_URI = 'https://musement.sandbox';
    const CITIES_ENDPOINT = 'https://musement.sandbox/cities';
    const GET = 'GET';

    private Client $client;
    /**
     * @var ClientInterface|ObjectProphecy
     */
    private $http;

    use ProphecyTrait;

    public function setUp(): void
    {
        $this->http = $this->prophesize(ClientInterface::class);

        $this->client = new Client(
            $this->http->reveal(),
            self::BASE_URI
        );
    }

    /**
     * @test
     */
    public function shouldSendRequestToCitiesEndpoint(): void
    {
        $this->http
            ->request(self::GET, self::CITIES_ENDPOINT)
            ->shouldBeCalledOnce()
            ->willReturn(new Response());

        $this->client->getCities();
    }
}
