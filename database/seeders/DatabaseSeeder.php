<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PaymentStatusSeeder::class,
            ExpenseStatusSeeder::class,
            PaymentTypeSeeder::class,
            BankSeeder::class,
            CostCenterStatusSeeder::class,
            CostCenterTypeSeeder::class,
            ExpenseCategorySeeder::class,
        ]);

        if (!app()->isProduction()) {
            $this->call([
                PayeeSeeder::class,
                WorkSeeder::class,
                BankAccountSeeder::class,
            ]);
        }
    }
}
