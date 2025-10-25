<?php

namespace App\ValueObjects\Task;

class Title
{
    private ?string $value;

    public function __construct(?string $title = null)
    {
        $this->value = $title;

        if (trim($this->value) === '') {
            throw new \InvalidArgumentException('Task title cannot be empty');
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