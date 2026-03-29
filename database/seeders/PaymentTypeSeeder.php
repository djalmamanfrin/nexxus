<?php

namespace Database\Seeders;

use App\Models\PaymentType;
use Illuminate\Database\Seeder;

class PaymentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaymentType::create([
            [
                'name' => 'Não especificado',
                'slug' => 'not_specified',
                'is_active' => true,
            ],
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
