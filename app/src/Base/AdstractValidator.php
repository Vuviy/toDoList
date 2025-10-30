<?php

namespace App\Base;

use App\Interface\ValidateInterface;

abstract class AdstractValidator implements ValidateInterface
{

    private $nextHandler;
    public function setNext(ValidateInterface $handler): ValidateInterface
    {
        $this->nextHandler = $handler;
        return $handler;
    }

    public function handle(string $field, mixed $value, array &$errors): void
    {
        if ($this->nextHandler) {
            $this->nextHandler->handle($field, $value, $errors);
        }

    }
}