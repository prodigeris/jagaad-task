<?php

declare(strict_types=1);

namespace Test\Unit\Components\Musement\Transformer;

use GuzzleHttp\Psr7\Response;
use JagaadTask\Components\Musement\Transformer\ResponseTransformer;
use PHPUnit\Framework\TestCase;
use Test\Unit\Components\Musement\Exception\InvalidResponseException;

class ResponseTransformerTest extends TestCase
{
    private ResponseTransformer $transformer;

    protected function setUp(): void
    {
        $this->transformer = new ResponseTransformer();
    }

    public function testTransformCitiesThrowsInvalidResponseExceptionWhenResponseIsInvalid(): void
    {
        $this->expectException(InvalidResponseException::class);

        $this->transformer->transformCities(new Response());
    }
}
