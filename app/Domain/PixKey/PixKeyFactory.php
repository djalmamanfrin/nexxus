<?php

namespace App\Domain\PixKey;

use App\Domain\ValidatorInterface;
use App\Support\Logger;
use Illuminate\Validation\ValidationException;

class PixKeyFactory
{
    private static array $candidates = [
        PixCpf::class,
        PixCnpj::class,
        PixEmail::class,
        PixPhone::class, // fone tem que ser depois de cpf pq ambos tem o mesmo lenght
    ];
    public static function make(string $value): ValidatorInterface
    {
        foreach (self::$candidates as $class) {
            Logger::info("Validando PixKey {$value} com {$class}");
            if ($class::matches($value)) {
                return new $class($value);
            }
        }

        throw ValidationException::withMessages(['pix_key' => 'Pix informado não permite ser usado']);
    }
}
