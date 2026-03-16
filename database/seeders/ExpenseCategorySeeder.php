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
            'Materials',
            'Labor',
            'Equipment',
            'Transport',
            'Food',
            'Services',
            'Taxes'
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
