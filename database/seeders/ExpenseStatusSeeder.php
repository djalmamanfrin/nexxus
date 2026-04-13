<?php

namespace Database\Seeders;

use App\Models\ExpenseStatus;
use Illuminate\Database\Seeder;

class ExpenseStatusSeeder extends Seeder
{
    public function run(): void
    {
        collect([
            ['name' => 'Pendente', 'slug' => 'pendente', 'color' => 'gray', 'sort_order' => 1],
            ['name' => 'Parcial', 'slug' => 'parcial', 'color' => 'yellow', 'sort_order' => 2],
            ['name' => 'Conciliado', 'slug' => 'conciliado', 'color' => 'green', 'sort_order' => 3],
            ['name' => 'Excedente', 'slug' => 'excedente', 'color' => 'orange', 'sort_order' => 4],
        ])->each(fn ($status) => ExpenseStatus::create($status));
    }
}
