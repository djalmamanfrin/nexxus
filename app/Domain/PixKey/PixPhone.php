<?php

namespace App\Domain\PixKey;

use App\Enums\PixKeyType;
use Illuminate\Validation\ValidationException;

final class PixPhone extends AbstractPixKey
{
    public function __construct(string $value)
    {
        parent::__construct(PixKeyType::PHONE, $value);
    }

    public static function matches(string $value): bool
    {
        if (!str_starts_with($value, '55')) {
            $value = '55' . $value;
        }
        return strlen($value) === 13;
    }

    public function formatted(): string
    {
        $number = substr($this->value, 2); // remove 55

        if (strlen($number) === 11) {
            return preg_replace(
                '/(\d{2})(\d{5})(\d{4})/',
                '($1) $2-$3',
                $number
            );
        }

        return preg_replace(
            '/(\d{2})(\d{4})(\d{4})/',
            '($1) $2-$3',
            $number
        );
    }

    public function mask(): string
    {
        return '(99) 99999-9999';
    }

    protected function sanitize(string $value): string
    {
        $numbers = preg_replace('/\D/', '', $value);
        // adiciona 55 se não tiver
        if (!str_starts_with($numbers, '55')) {
            $numbers = '55' . $numbers;
        }

        return $numbers;
    }

    protected function isValid(string $value): void
    {
        if (!str_starts_with($value, '55')) {
            throw ValidationException::withMessages(['pix_key' => 'Telefone deve começar com 55']);
        }

        $number = substr($value, 2); // remove DDI
        $hasElevenDigits = strlen($number) === 11;
        if ($hasElevenDigits && $number[2] !== '9') {
            throw ValidationException::withMessages(['pix_key' => 'Telefone precisa ter 11 dígitos e ter o 9 adicional']);
        }

        $ddd = substr($number, 0, 2);
        if ($ddd[0] === '0' || $ddd === '00') {
            throw ValidationException::withMessages(['pix_key' => 'Telefone DDD não começa com 0 e não pode ser 00']);
        }
    }
}
