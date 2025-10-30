<?php

namespace App\Rule;

use App\Base\AdstractValidator;

class StringRule extends AdstractValidator
{
    public function handle(string $field, mixed $value, array &$errors): void
    {
        if (gettype($value) !== 'string') {
            $errors[$field][] = "Field {$field} is must be string.";
        }
        $this->handle($field, $value, $errors);
    }
    
}