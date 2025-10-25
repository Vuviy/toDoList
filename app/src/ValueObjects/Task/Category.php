<?php

namespace App\ValueObjects\Task;

class Category
{
    private ?int $value;

    public function __construct(?string $category = null)
    {
        if(null === $category){
            $category = 1;
        }
        $this->value = $category;
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