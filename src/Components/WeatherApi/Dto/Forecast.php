<?php

declare(strict_types=1);

namespace JagaadTask\Components\WeatherApi\Dto;

use ArrayIterator;
use IteratorAggregate;

/**
 * @implements IteratorAggregate<ForecastDay>
 */
class Forecast implements IteratorAggregate
{
    /**
     * @var ForecastDay[]|array
     */
    private array $days;

    public function __construct(ForecastDay ...$days)
    {
        $this->days = $days;
    }

    /**
     * @return ArrayIterator<int, ForecastDay>
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->days);
    }
}
