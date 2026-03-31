<?php

namespace App\Domain\Documents;

use InvalidArgumentException;

final class CPF
{
    private string $value;

    public function __construct(string $value)
    {
        $value = preg_replace('/\D/', '', $value);

        if (!self::isValid($value)) {
            throw new InvalidArgumentException('CPF inválido');
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
            '/(\d{3})(\d{3})(\d{3})(\d{2})/',
            '$1.$2.$3-$4',
            $this->value
        );
    }
    public function type(): DocumentType
    {
        return DocumentType::CPF;
    }

    public function mask(): string
    {
        return '999.999.999-99';
    }

    public function __toString(): string
    {
        return $this->formatted();
    }

    public static function isValid(string $cpf): bool
    {
        if (strlen($cpf) !== 11 || preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        for ($t = 9; $t < 11; $t++) {
            $sum = 0;

            for ($i = 0; $i < $t; $i++) {
                $sum += $cpf[$i] * (($t + 1) - $i);
            }

            $digit = ((10 * $sum) % 11) % 10;

            if ($cpf[$t] != $digit) {
                return false;
            }
        }

        return true;
    }
}

