<?php

namespace App\Rules;

use App\Domain\PixKey\PixKeyFactory;
use Illuminate\Contracts\Validation\ValidationRule;
use Throwable;

class PixKeyRule implements ValidationRule
{
    public function validate($attribute, $value, $fail): void
    {

        dd($attribute, $value);
        try {
            PixKeyFactory::make($value);
        } catch (Throwable $e) {
            dd($e->getMessage());
            $fail($e->getMessage());
        }
    }
}
