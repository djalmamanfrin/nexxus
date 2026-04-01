<?php

namespace App\Rules;

use App\Domain\Documents\DocumentFactory;
use Illuminate\Contracts\Validation\ValidationRule;
use Throwable;

class DocumentRule implements ValidationRule
{
    public function validate($attribute, $value, $fail): void
    {
        try {
            DocumentFactory::make($value);
        } catch (Throwable $e) {
            $fail($e->getMessage());
        }
    }
}
