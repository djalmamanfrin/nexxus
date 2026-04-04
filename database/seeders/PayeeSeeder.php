<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payee;
use Illuminate\Support\Str;

class PayeeSeeder extends Seeder
{
    public function run(): void
    {
        collect([
            [
                'ulid' => Str::ulid(),
                'name' => 'Fornecedor de Materiais Ltda',
                'document' => '99005029000123',
                'document_type' => 'cnpj'
            ],
            [
                'ulid' => Str::ulid(),
                'name' => 'João da Silva',
                'document' => '90509006078',
                'document_type' => 'cpf'
            ]
        ])->each(fn ($payee) => Payee::create($payee));
    }
}
