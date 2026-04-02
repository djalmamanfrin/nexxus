<?php

namespace App\Domain\Documents;

use App\Domain\AbstractValidator;
use App\Domain\ValidatorType;
use Illuminate\Validation\ValidationException;

class CPFDocument extends AbstractValidator
{
    public function __construct(string $value)
    {
        parent::__construct(ValidatorType::CPF, $value);
    }
    public static function matches(string $value): bool
    {
        return strlen($value) === 11;
    }
    public function formatted(): string
    {
        return preg_replace(
            '/(\d{3})(\d{3})(\d{3})(\d{2})/',
            '$1.$2.$3-$4',
            $this->value
        );
    }
    public function type(): ValidatorType
    {
        return ValidatorType::CPF;
    }

    public function mask(): string
    {
        return '999.999.999-99';
    }

    public function validate(string $value): void
    {
        if (strlen($value) !== 11) {
            throw ValidationException::withMessages([
                'document' => 'O CPF deve conter 11 dígitos.',
            ]);
        }

        if (preg_match('/(\d)\1{10}/', $value)) {
            throw ValidationException::withMessages([
                'document' => 'CPF inválido. Não utilize números repetidos.',
            ]);
        }

        for ($t = 9; $t < 11; $t++) {
            $sum = 0;

            for ($i = 0; $i < $t; $i++) {
                $sum += $value[$i] * (($t + 1) - $i);
            }

            $digit = ((10 * $sum) % 11) % 10;

            if ($value[$t] != $digit) {
                throw ValidationException::withMessages([
                    'document' => 'CPF inválido. Verifique os números informados.',
                ]);
            }
        }
    }
}

