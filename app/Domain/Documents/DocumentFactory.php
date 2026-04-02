<?php

namespace App\Domain\Documents;

use App\Domain\ValidatorInterface;
use Illuminate\Validation\ValidationException;

class DocumentFactory
{
    private static array $candidates = [
        CNPJDocument::class,
        CPFDocument::class,
    ];
    public static function make(string $value): ValidatorInterface
    {
        foreach (self::$candidates as $class) {
            if ($class::matches($value)) {
                return new $class($value);
            }
        }

        throw ValidationException::withMessages(['document' => 'Documento inválido']);
    }
}
