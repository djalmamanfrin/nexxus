<?php

namespace App\Domain\Documents;


use Illuminate\Validation\ValidationException;

class DocumentFactory
{
    public static function make(string $value): CPF|CNPJ
    {
        $numbers = preg_replace('/\D/', '', $value);

        return match (strlen($numbers)) {
            11 => new CPF($numbers),
            14 => new CNPJ($numbers),
            default => throw ValidationException::withMessages(['document' => 'Documento inválido']),
        };
    }
}
