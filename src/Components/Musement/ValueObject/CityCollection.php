<?php

declare(strict_types=1);

namespace JagaadTask\Components\Musement\ValueObject;

use ArrayIterator;
use IteratorAggregate;

/**
 * @implements IteratorAggregate<City>
 */
class CityCollection implements IteratorAggregate
{
    /**
     * @var City[]|array
     */
    private array $cities;

    public function __construct(City ...$cities)
    {
        $this->cities = $cities;
    }

    /**
     * @return ArrayIterator<int, City>
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->cities);
    }
}
