<?php

namespace App\Dto;

final class ThingInput
{
    private array $dynamicProperties = [];

    public function __get($name)
    {
        return $this->dynamicProperties[$name] ?? null;
    }

    public function __set($name, $value)
    {
        $this->dynamicProperties[$name] = $value;
    }
}