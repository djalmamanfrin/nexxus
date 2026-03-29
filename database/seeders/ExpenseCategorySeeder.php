<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExpenseCategory;
use Illuminate\Support\Str;

class ExpenseCategorySeeder extends Seeder
{
    public function run(): void
    {
        collect([
            'Material',
            'Equipamento',
            'Transporte',
            'Alimentação',
            'Funcionários',
            'Impostos',
            'Tecnologia'
        ])
            ->map(fn ($name) => ['name' => $name, 'slug' => Str::slug($name), 'active' => true])
            ->each(fn ($category) => ExpenseCategory::create($category));
    }
}
