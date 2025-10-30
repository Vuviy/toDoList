<?php

namespace App\Rule;

use App\Base\AdstractValidator;

class RequiredRule extends AdstractValidator
{
    public function handle(string $field, mixed $value, array &$errors): void
    {
        if ($value === null || $value === '') {
            $errors[$field][] = "Field {$field} is required.";
        }
        $this->handle($field, $value, $errors);
    }
    
}