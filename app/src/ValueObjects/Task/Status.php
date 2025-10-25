<?php

namespace App\ValueObjects\Task;

class Status
{
    private ?string $value;

    public function __construct(?string $status = null)
    {
        if(null === $status){
            $status = 'new';
        }
        $this->value = $status;
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