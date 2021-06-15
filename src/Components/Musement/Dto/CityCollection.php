<?php

declare(strict_types=1);

namespace JagaadTask\Components\Musement\Dto;

use Illuminate\Support\Collection;

class CityCollection extends Collection
{
    public function __construct(City ...$cities)
    {
        parent::__construct($cities);
    }
}
