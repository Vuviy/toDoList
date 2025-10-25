<?php

namespace App\ValueObjects\Task;

class Priority
{
    private ?int $value;

    public function __construct(?string $priority = null)
    {
        if(null === $priority){
            $priority = 1;
        }
        $this->value = $priority;

    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

}