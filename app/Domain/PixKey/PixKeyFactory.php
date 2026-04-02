<?php

namespace App\Domain\PixKey;

use App\Domain\ValidatorInterface;
use Illuminate\Validation\ValidationException;

class PixKeyFactory
{
    private static array $candidates = [
        PixEmail::class,
        PixPhone::class,
        PixCpf::class,
        PixCnpj::class,
    ];
    public static function make(string $value): ValidatorInterface
    {
        foreach (self::$candidates as $class) {
            if ($class::matches($value)) {
                return new $class($value);
            }
        }

        throw ValidationException::withMessages(['pix_key' => 'Pix informado não permite ser usado']);
    }
}
