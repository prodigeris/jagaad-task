<?php

declare(strict_types=1);

namespace JagaadTask\Components\WeatherApi\Dto;

class ForecastDay
{
    private string $date;

    private string $condition;

    public function __construct(string $date, string $condition)
    {
        $this->date = $date;
        $this->condition = $condition;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function getCondition(): string
    {
        return $this->condition;
    }
}
