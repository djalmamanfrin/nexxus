<?php

namespace Database\Seeders;

use App\Models\ExpenseStatus;
use Illuminate\Database\Seeder;

class ExpenseStatusSeeder extends Seeder
{
    public function run(): void
    {
        ExpenseStatus::insert([
            ['name' => 'Pendente', 'slug' => 'pendente', 'color' => 'yellow'],
            ['name' => 'Concluído', 'slug' => 'done', 'color' => 'green'],
        ]);
    }
}
