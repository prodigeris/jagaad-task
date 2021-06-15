<?php

declare(strict_types=1);

namespace JagaadTask\Components\Musement\Factory;

use JagaadTask\Components\Musement\Dto\City;

class CityFactory
{
    public function build(int $id): City
    {
        return new City($id);
    }
}
