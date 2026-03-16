<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PaymentStatusSeeder::class,
            BankSeeder::class,
            CostCenterTypeSeeder::class,
            ExpenseCategorySeeder::class,
            PayeeSeeder::class,
            WorkSeeder::class,
        ]);
    }
}
