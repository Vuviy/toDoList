<?php

namespace App\Interface;
interface ValidateInterface
{
    public function setNext(ValidateInterface $handler): ValidateInterface;

    public function handle(string $field, mixed $value, array &$errors): void;
}