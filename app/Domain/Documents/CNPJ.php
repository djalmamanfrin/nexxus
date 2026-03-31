<?php

namespace App\Domain\Documents;

use InvalidArgumentException;

final class CNPJ
{
    private string $value;

    public function __construct(string $value)
    {
        $value = preg_replace('/\D/', '', $value);

        if (!self::isValid($value)) {
            throw new InvalidArgumentException('CNPJ inválido');
        }

        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
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

    public function __toString(): string
    {
        return $this->formatted();
    }

    public static function isValid(string $cnpj): bool
    {
        if (strlen($cnpj) !== 14 || preg_match('/(\d)\1{13}/', $cnpj)) {
            return false;
        }

        $weights = [
            [5,4,3,2,9,8,7,6,5,4,3,2],
            [6,5,4,3,2,9,8,7,6,5,4,3,2]
        ];

        for ($t = 12; $t < 14; $t++) {
            $sum = 0;

            for ($i = 0; $i < $t; $i++) {
                $sum += $cnpj[$i] * $weights[$t - 12][$i];
            }

            $digit = $sum % 11 < 2 ? 0 : 11 - ($sum % 11);

            if ($cnpj[$t] != $digit) {
                return false;
            }
        }

        return true;
    }
}
