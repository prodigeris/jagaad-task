<?php

declare(strict_types=1);

namespace JagaadTask\Components\Musement\Factory;

use JagaadTask\Components\Musement\ValueObject\City;

class CityFactory
{
    public function build(int $id, string $name, float $latitude, float $longitude): City
    {
        return new City($id, $name, $latitude, $longitude);
    }
}
