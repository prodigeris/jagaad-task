<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\ArrayNotation\ArraySyntaxFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitStrictFixer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\EasyCodingStandard\ValueObject\Option;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ContainerConfigurator $containerConfigurator): void {
    // A. standalone rule
    $services = $containerConfigurator->services();
    $services->set(ArraySyntaxFixer::class)
        ->call('configure', [[
            'syntax' => 'short',
        ]]);

    $parameters = $containerConfigurator->parameters();

    $parameters->set(Option::PATHS, [
        __DIR__ . '/src',
        __DIR__ . '/bin',
        __DIR__ . '/tests',
    ]);

    $parameters->set(Option::SKIP, [
        PhpUnitStrictFixer::class => [
            __DIR__ . '/tests/Unit/Components/Musement/Transformer/ResponseTransformerTest.php',
            __DIR__ . '/tests/Unit/Components/WeatherApi/Transformer/ResponseTransformerTest.php',
            __DIR__ . '/tests/Unit/Domain/City/UseCase/FetchWeatherForMusementCitiesUseCaseTest.php',
        ],
    ]);

    // B. full sets
    $containerConfigurator->import(SetList::PSR_12);
    $containerConfigurator->import(SetList::SPACES);
    $containerConfigurator->import(SetList::NAMESPACES);
    $containerConfigurator->import(SetList::COMMON);
};
