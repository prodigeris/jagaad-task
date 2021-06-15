<?php

declare(strict_types=1);

namespace JagaadTask\Infrastructure\Command;

use JagaadTask\Domain\City\UseCase\FetchWeatherForMusementCitiesUseCase;

class RunEntrypoint
{
    private FetchWeatherForMusementCitiesUseCase $useCase;

    public function __construct(FetchWeatherForMusementCitiesUseCase $useCase)
    {
        $this->useCase = $useCase;
    }

    public function run(): void
    {
        $forecasts = $this->useCase->execute();

        foreach ($forecasts as $forecast) {
            printf("Processed city %s | %s - %s\n", $forecast->getName(), $forecast->getToday(), $forecast->getTomorrow());
        }
    }
}
