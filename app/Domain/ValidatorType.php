<?php

namespace App\Domain;

enum ValidatorType: string
{
    case EMAIL = 'email';
    case PHONE = 'phone';
    case CPF = 'cpf';
    case CNPJ = 'cnpj';
}
