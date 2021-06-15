<?php

declare(strict_types=1);

namespace Test\Unit\Components\WeatherApi\Transformer;

use GuzzleHttp\Psr7\Response;
use JagaadTask\Components\WeatherApi\Dto\Forecast;
use JagaadTask\Components\WeatherApi\Dto\ForecastDay;
use JagaadTask\Components\WeatherApi\Transformer\ResponseTransformer;
use JsonException;
use PHPUnit\Framework\TestCase;

class ResponseTransformerTest extends TestCase
{
    private ResponseTransformer $transformer;

    protected function setUp(): void
    {
        $this->transformer = new ResponseTransformer();
    }

    /**
     * @dataProvider forecastDataProvider
     * @throws JsonException
     *
     * @param array|array[] $body
     */
    public function testTransformForecastShouldReturnDtoWithDays(array $body, Forecast $expected): void
    {
        $response = $this->buildResponse($body);

        $actual = $this->transformer->transformForecast($response);

        self::assertEquals($expected, $actual);
    }

    /**
     * @return array[]
     */
    public function forecastDataProvider(): array
    {
        return [
            [
                [
                    'location' => [],
                    'current' => [],
                    'forecast' => [
                        'forecastday' => [
                            [
                                'date' => '2021-06-16',
                                'date_epoch' => 1623715200,
                                'day' => [
                                    'condition' => [
                                        'text' => 'Sunny',
                                    ],
                                ],
                            ],
                            [
                                'date' => '2021-06-17',
                                'date_epoch' => 1623715200,
                                'day' => [
                                    'condition' => [
                                        'text' => 'Windy',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                new Forecast(
                    new ForecastDay('2021-06-16', 'Sunny'),
                    new ForecastDay('2021-06-17', 'Windy'),
                ),
            ],
        ];
    }

    /**
     * @param array[] $body
     */
    protected function buildResponse(array $body): Response
    {
        return new Response(200, [], json_encode($body, JSON_THROW_ON_ERROR));
    }
}
