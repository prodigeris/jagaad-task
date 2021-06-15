<?php

declare(strict_types=1);

namespace JagaadTask\Domain\City\UseCase;

use JagaadTask\Components\Musement\Client as Musement;
use JagaadTask\Components\WeatherApi\Client as WeatherApi;
use JagaadTask\Components\WeatherApi\ValueObject\Coordinates;

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

    public function execute()
    {
        $cities = $this->musement->getCities();
        foreach ($cities as $city) {
            $this->weatherApi->getForecast(new Coordinates($city->getLatitude(), $city->getLongitude()), self::DAYS);
        }
    }
}
