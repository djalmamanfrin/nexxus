<?php

namespace Database\Seeders;

use App\Models\ExpenseStatus;
use Illuminate\Database\Seeder;

class ExpenseStatusSeeder extends Seeder
{
    public function run(): void
    {
        collect([
            ['name' => 'Pendente', 'slug' => 'pendente', 'color' => 'gray'],
            ['name' => 'Não conciliado', 'slug' => 'nao-conciliado', 'color' => 'yellow'],
            ['name' => 'Parcial', 'slug' => 'parcial', 'color' => 'orange'],
            ['name' => 'Conciliado', 'slug' => 'conciliado', 'color' => 'green'],
            ['name' => 'Excedente', 'slug' => 'excedente', 'color' => 'red'],
            ['name' => 'Cancelado', 'slug' => 'cancelado', 'color' => 'amber'],
        ])->each(fn ($status) => ExpenseStatus::create($status));
    }
}
