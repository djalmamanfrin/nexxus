<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('payment_types')->insert([
            [
                'name' => 'Dinheiro',
                'slug' => 'cash',
                'is_active' => true,
            ],
            [
                'name' => 'Cartão de Crédito',
                'slug' => 'credit_card',
                'is_active' => true,
            ],
            [
                'name' => 'Cartão de Débito',
                'slug' => 'debit_card',
                'is_active' => true,
            ],
            [
                'name' => 'PIX',
                'slug' => 'pix',
                'is_active' => true,
            ],
        ]);
    }
}
