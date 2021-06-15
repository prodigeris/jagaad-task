<?php

declare(strict_types=1);

namespace Test\Unit\Domain\City\UseCase;

use JagaadTask\Components\Musement\Client as Musement;
use JagaadTask\Components\Musement\ValueObject\City;
use JagaadTask\Components\Musement\ValueObject\CityCollection;
use JagaadTask\Components\WeatherApi\Client as WeatherApi;
use JagaadTask\Components\WeatherApi\Dto\Forecast;
use JagaadTask\Components\WeatherApi\Dto\ForecastDay;
use JagaadTask\Components\WeatherApi\ValueObject\Coordinates;
use JagaadTask\Domain\City\UseCase\FetchWeatherForMusementCitiesUseCase;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Test\Unit\Domain\City\Dto\CityForecast;

class FetchWeatherForMusementCitiesUseCaseTest extends TestCase
{
    use ProphecyTrait;

    public const CITY_1 = 'Amsterdam';

    public const CITY_2 = 'Milan';

    public const ID_1 = 1;

    public const LATITUDE_1 = 11.22;

    public const LONGITUDE_1 = 33.44;

    public const ID_2 = 2;

    public const LATITUDE_2 = 1.1;

    public const LONGITUDE_2 = 2.2;

    public const DAYS = 2;

    public const DATE_1 = '2012-01-01';

    public const DATE_2 = '2012-01-02';

    public const CONDITION_1 = 'Sunny';

    public const CONDITION_2 = 'Party cloudy';

    private FetchWeatherForMusementCitiesUseCase $useCase;

    /**
     * @var WeatherApi|ObjectProphecy
     */
    private $weatherApi;

    /**
     * @var Musement|ObjectProphecy
     */
    private $musement;

    private CityCollection $cities;

    /**
     * @var Forecast[]
     */
    private array $forecast;

    /**
     * @var CityForecast[]
     */
    private array $cityForecasts;

    protected function setUp(): void
    {
        $this->musement = $this->prophesize(Musement::class);
        $this->weatherApi = $this->prophesize(WeatherApi::class);

        $this->useCase = new FetchWeatherForMusementCitiesUseCase(
            $this->musement->reveal(),
            $this->weatherApi->reveal(),
        );

        $this->cities = new CityCollection(
            new City(self::ID_1, self::CITY_1, self::LATITUDE_1, self::LONGITUDE_1),
            new City(self::ID_2, self::CITY_2, self::LATITUDE_2, self::LONGITUDE_2),
        );

        $this->forecast = [
            new Forecast(new ForecastDay(self::DATE_1, self::CONDITION_1), new ForecastDay(self::DATE_2, self::CONDITION_2)),
            new Forecast(new ForecastDay(self::DATE_1, self::CONDITION_2), new ForecastDay(self::DATE_2, self::CONDITION_1)),
        ];

        $this->cityForecasts = [
            new CityForecast(self::CITY_1, self::CONDITION_1, self::CONDITION_2),
            new CityForecast(self::CITY_2, self::CONDITION_2, self::CONDITION_1),
        ];

        $this->musement
            ->getCities()
            ->willReturn($this->cities);
    }

    public function testShouldReturnGeneratorOfCityWeather(): void
    {
        $this->stubGetForecast(self::LATITUDE_1, self::LONGITUDE_1, $this->forecast[0]);
        $this->stubGetForecast(self::LATITUDE_2, self::LONGITUDE_2, $this->forecast[1]);

        foreach ($this->useCase->execute() as $i => $actual) {
            self::assertEquals($this->cityForecasts[$i], $actual);
        }
    }

    protected function stubGetForecast(float $latitude, float $longitude, Forecast $forecast): void
    {
        $this->weatherApi
            ->getForecast(
                Argument::that(fn (Coordinates $xy) => $xy->equals(new Coordinates(
                    $latitude,
                    $longitude
                ))),
                Argument::cetera()
            )
            ->willReturn($forecast);
    }
}
