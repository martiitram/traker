<?php

namespace App\Domain;

class TaskName
{
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    private string $name;

    public function name(): string
    {
        return $this->name;
    }
}