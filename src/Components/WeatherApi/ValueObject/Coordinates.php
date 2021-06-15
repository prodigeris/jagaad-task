<?php

declare(strict_types=1);

namespace JagaadTask\Components\WeatherApi\ValueObject;

class Coordinates
{
    private float $latitude;

    private float $longitude;

    public function __construct(float $latitude, float $longitude)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    public function equals(self $coordinates): bool
    {
        return $this->getLatitude() === $coordinates->getLatitude()
            && $this->getLongitude() === $coordinates->getLongitude();
    }
}
