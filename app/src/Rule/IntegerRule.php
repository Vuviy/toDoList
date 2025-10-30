<?php

namespace App\Rule;

use App\Base\AdstractValidator;

class IntegerRule extends AdstractValidator
{
    public function handle(string $field, mixed $value, array &$errors): void
    {
        if (gettype($value) !== 'integer') {
            $errors[$field][] = "Field {$field} is must be integer.";
        }
        $this->handle($field, $value, $errors);
    }
    
}