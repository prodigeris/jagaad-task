<?php

declare(strict_types=1);

namespace JagaadTask\Components\Musement\Dto;

class City
{
    private int $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }
}
