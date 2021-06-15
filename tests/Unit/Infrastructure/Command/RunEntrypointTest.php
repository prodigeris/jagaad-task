<?php

declare(strict_types=1);

namespace Test\Unit\Infrastructure\Command;

use JagaadTask\Domain\City\Dto\CityForecast;
use JagaadTask\Domain\City\UseCase\FetchWeatherForMusementCitiesUseCase;
use JagaadTask\Infrastructure\Command\RunEntrypoint;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class RunEntrypointTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @var FetchWeatherForMusementCitiesUseCase|ObjectProphecy
     */
    private $useCase;

    private RunEntrypoint $command;

    protected function setUp(): void
    {
        $this->useCase = $this->prophesize(FetchWeatherForMusementCitiesUseCase::class);
        $this->command = new RunEntrypoint($this->useCase->reveal());
    }

    public function testReturnsFormattedOutput(): void
    {
        $this->expectOutputString(
            "Processed city Amsterdam | Party Cloudy - Sunny\n"
            . "Processed city Milan | Patchy rain possible - Partly cloudy\n"
        );

        $generator = static function () {
            yield new CityForecast('Amsterdam', 'Party Cloudy', 'Sunny');
            yield new CityForecast('Milan', 'Patchy rain possible', 'Partly cloudy');
        };

        $this->useCase->execute()->willReturn($generator());

        $this->command->run();
    }
}
