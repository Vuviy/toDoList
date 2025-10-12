<?php

namespace App\ValueObjects\Task;

class Id
{
    private ?string $value;

    public function __construct(?string $id = null)
    {
        $this->value = $id ?: uniqid();

        if (trim($this->value) === '') {
            throw new \InvalidArgumentException('Task id cannot be empty');
        }
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