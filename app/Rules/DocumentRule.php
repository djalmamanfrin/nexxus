<?php

namespace App\Rules;

use App\Domain\Documents\DocumentFactory;
use Illuminate\Contracts\Validation\ValidationRule;

class DocumentRule implements ValidationRule
{
    public function validate($attribute, $value, $fail): void
    {
        try {
            DocumentFactory::make($value);
        } catch (\InvalidArgumentException $e) {
            $fail($e->getMessage());
        }
    }
}
