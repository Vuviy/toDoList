<?php

namespace App\ValueObjects\Task;

class Date
{
    private ?string $value;

    public function __construct(?string $date = null)
    {
        if(null === $date){
            $now = new \DateTime();
            $date = $now->format('Y-m-d');
        }
        $this->value = $date;

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