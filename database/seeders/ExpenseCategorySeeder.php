<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExpenseCategory;
use Illuminate\Support\Str;

class ExpenseCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Material',
            'Equipamento',
            'Transporte',
            'Alimentação',
            'Funcionários',
            'Impostos',
            'Tecnologia'
        ];

        foreach ($categories as $name) {
            ExpenseCategory::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'active' => true
            ]);
        }
    }
}
