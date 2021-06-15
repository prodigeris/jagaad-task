<?php

declare(strict_types=1);

namespace Test\Unit\Domain\City\UseCase;

use JagaadTask\Components\Musement\Client as Musement;
use JagaadTask\Components\Musement\ValueObject\City;
use JagaadTask\Components\Musement\ValueObject\CityCollection;
use JagaadTask\Components\WeatherApi\Client as WeatherApi;
use JagaadTask\Components\WeatherApi\Dto\Forecast;
use JagaadTask\Components\WeatherApi\ValueObject\Coordinates;
use JagaadTask\Domain\City\UseCase\FetchWeatherForMusementCitiesUseCase;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

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
    }

    public function testShouldPassCoordinatesOfCitiesToWeatherApi(): void
    {
        $this->musement
            ->getCities()
            ->willReturn($this->cities);

        $this->weatherApi->getForecast(Argument::cetera())
            ->willReturn(new Forecast());

        $this->useCase->execute();

        $this->shouldCallGetForecastWithCoordinates(self::LATITUDE_1, self::LONGITUDE_1);
        $this->shouldCallGetForecastWithCoordinates(self::LATITUDE_2, self::LONGITUDE_2);
    }

    protected function shouldCallGetForecastWithCoordinates(float $latitude, float $longitude): void
    {
        $this->weatherApi
            ->getForecast(
                Argument::that(
                    fn (Coordinates $xy) => $xy->equals(new Coordinates($latitude, $longitude))
                ),
                self::DAYS
            )->shouldHaveBeenCalledOnce();
    }
}
