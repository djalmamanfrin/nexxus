<?php

namespace App\Enums;

enum PixKeyType: string
{
    case EMAIL = 'email';
    case PHONE = 'phone';
    case CPF = 'cpf';
    case CNPJ = 'cnpj';
}
