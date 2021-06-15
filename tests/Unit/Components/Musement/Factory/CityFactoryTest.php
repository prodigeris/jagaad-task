<?php

declare(strict_types=1);

namespace Test\Unit\Components\Musement\Factory;

use JagaadTask\Components\Musement\Factory\CityFactory;
use PHPUnit\Framework\TestCase;

class CityFactoryTest extends TestCase
{
    public const ID = 1111;

    private CityFactory $factory;

    protected function setUp(): void
    {
        $this->factory = new CityFactory();
    }

    public function testBuildShouldReturnCityWithId(): void
    {
        $city = $this->factory->build(self::ID);

        self::assertSame(self::ID, $city->getId());
    }
}
