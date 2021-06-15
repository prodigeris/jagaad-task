<?php

declare(strict_types=1);

namespace JagaadTask\Components\Musement\Transformer;

use JagaadTask\Components\Musement\Dto\City;
use JagaadTask\Components\Musement\Dto\CityCollection;
use Psr\Http\Message\ResponseInterface;

class ResponseTransformer
{
    /**
     * @return CityCollection|City[]
     */
    public function transformCities(ResponseInterface $response): CityCollection
    {
        return new CityCollection();
    }
}
