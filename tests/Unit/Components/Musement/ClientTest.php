<?php

declare(strict_types=1);

namespace Test\Unit\Components\Musement;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use JagaadTask\Components\Musement\Client;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class ClientTest extends TestCase
{
    use ProphecyTrait;

    public const CITIES_ENDPOINT = 'https://musement.sandbox/cities';

    public const GET = 'GET';

    private const BASE_URI = 'https://musement.sandbox';

    private Client $client;

    /**
     * @var ClientInterface|ObjectProphecy
     */
    private $http;

    protected function setUp(): void
    {
        $this->http = $this->prophesize(ClientInterface::class);

        $this->client = new Client(
            $this->http->reveal(),
            self::BASE_URI
        );
    }


    public function testShouldSendRequestToCitiesEndpoint(): void
    {
        $this->http
            ->request(self::GET, self::CITIES_ENDPOINT)
            ->shouldBeCalledOnce()
            ->willReturn(new Response());

        $this->client->getCities();
    }
}
