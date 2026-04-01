<?php

namespace App\Domain\PixKey;

enum PixKeyType: string
{
    case EMAIL = 'email';
    case PHONE = 'phone';
    case CPF = 'cpf';
    case CNPJ = 'cnpj';
}
