<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payee;
use Illuminate\Support\Str;

class PayeeSeeder extends Seeder
{
    public function run(): void
    {
        Payee::insert([
            [
                'uuid' => Str::uuid(),
                'name' => 'Fornecedor de Materiais Ltda',
                'document' => '12345678000190',
                'document_type' => 'cnpj'
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'João da Silva',
                'document' => '12345678900',
                'document_type' => 'cpf'
            ]
        ]);
    }
}
