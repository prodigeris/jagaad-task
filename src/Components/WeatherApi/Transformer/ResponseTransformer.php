<?php

declare(strict_types=1);

namespace JagaadTask\Components\WeatherApi\Transformer;

use JagaadTask\Components\WeatherApi\Dto\Forecast;
use JagaadTask\Components\WeatherApi\Dto\ForecastDay;
use JagaadTask\Components\WeatherApi\Exception\InvalidResponseException;
use JsonException;
use Psr\Http\Message\ResponseInterface;

class ResponseTransformer
{
    public function transformForecast(ResponseInterface $response): Forecast
    {
        $array = $this->getBody($response);
        /**
         * @var array|ForecastDay[] $days
         */
        $days = [];

        foreach ($array['forecast']['forecastday'] as $forecastDay) {
            $date = $forecastDay['date'];
            $condition = $forecastDay['day']['condition']['text'];
            $days[] = new ForecastDay($date, $condition);
        }

        return new Forecast(...$days);
    }

    /**
     * @return array|array[]
     */
    protected function getBody(ResponseInterface $response): array
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
}
