<?php

namespace App\Domain\Documents;

use Illuminate\Validation\ValidationException;
use InvalidArgumentException;

final class CNPJ extends AbstractDocument
{
    public function __construct(string $value)
    {
        parent::__construct(DocumentType::CNPJ, $value);
    }
    public static function matches(string $value): bool
    {
        return strlen($value) === 14;
    }
    public function formatted(): string
    {
        return preg_replace(
            '/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/',
            '$1.$2.$3/$4-$5',
            $this->value
        );
    }
    public function type(): DocumentType
    {
        return DocumentType::CNPJ;
    }
    public function mask(): string
    {
        return '99.999.999/9999-99';
    }
    public function validate(string $value): void
    {
        $value = preg_replace('/\D/', '', $value);

        if (strlen($value) !== 14) {
            throw ValidationException::withMessages([
                'document' => 'O CNPJ deve conter 14 dígitos.',
            ]);
        }

        if (preg_match('/(\d)\1{13}/', $value)) {
            throw ValidationException::withMessages([
                'document' => 'CNPJ inválido. Não utilize números repetidos.',
            ]);
        }

        $weights = [
            [5,4,3,2,9,8,7,6,5,4,3,2],
            [6,5,4,3,2,9,8,7,6,5,4,3,2]
        ];

        for ($t = 12; $t < 14; $t++) {
            $sum = 0;

            for ($i = 0; $i < $t; $i++) {
                $sum += $value[$i] * $weights[$t - 12][$i];
            }

            $digit = $sum % 11 < 2 ? 0 : 11 - ($sum % 11);

            if ($value[$t] != $digit) {
                throw ValidationException::withMessages([
                    'document' => 'CNPJ inválido. Verifique os números informados.',
                ]);
            }
        }
    }
}
