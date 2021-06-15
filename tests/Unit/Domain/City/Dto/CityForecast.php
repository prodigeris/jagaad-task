<?php

declare(strict_types=1);

namespace Test\Unit\Domain\City\Dto;

class CityForecast
{
    private string $name;

    private string $today;

    private string $tomorrow;

    public function __construct(string $name, string $today, string $tomorrow)
    {
        $this->name = $name;
        $this->today = $today;
        $this->tomorrow = $tomorrow;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getToday(): string
    {
        return $this->today;
    }

    public function getTomorrow(): string
    {
        return $this->tomorrow;
    }
}
