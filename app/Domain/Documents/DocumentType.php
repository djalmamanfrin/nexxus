<?php

namespace App\Domain\Documents;

enum DocumentType: string
{
    case CPF = 'cpf';
    case CNPJ = 'cnpj';
}
