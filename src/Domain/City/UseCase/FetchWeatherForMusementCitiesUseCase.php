<?php

declare(strict_types=1);

namespace JagaadTask\Domain\City\UseCase;

use Generator;
use JagaadTask\Components\Musement\Client as Musement;
use JagaadTask\Components\WeatherApi\Client as WeatherApi;
use JagaadTask\Components\WeatherApi\Dto\ForecastDay;
use JagaadTask\Components\WeatherApi\ValueObject\Coordinates;
use JagaadTask\Domain\City\Dto\CityForecast;

class FetchWeatherForMusementCitiesUseCase
{
    public const DAYS = 2;

    private Musement $musement;

    private WeatherApi $weatherApi;

    public function __construct(Musement $musement, WeatherApi $weatherApi)
    {
        $this->musement = $musement;
        $this->weatherApi = $weatherApi;
    }

    /**
     * @return Generator<CityForecast>
     */
    public function execute(): Generator
    {
        $cities = $this->musement->getCities();
        foreach ($cities as $city) {
            $forecast = $this->weatherApi->getForecast(new Coordinates($city->getLatitude(), $city->getLongitude()), self::DAYS);
            /**
             * @var ForecastDay[] $days
             */
            $days = $forecast->getIterator()->getArrayCopy();

            yield new CityForecast($city->getName(), $days[0]->getCondition(), $days[1]->getCondition());
        }
    }
}
