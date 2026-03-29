<?php

namespace Database\Seeders;

use App\Models\ExpenseStatus;
use Illuminate\Database\Seeder;

class ExpenseStatusSeeder extends Seeder
{
    public function run(): void
    {
        collect([
            ['name' => 'Pendente', 'slug' => 'pendente', 'color' => 'yellow'],
            ['name' => 'Concluído', 'slug' => 'done', 'color' => 'green'],
        ])->each(fn ($status) => ExpenseStatus::create($status));
    }
}
