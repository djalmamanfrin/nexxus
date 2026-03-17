<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentStatus;

class PaymentStatusSeeder extends Seeder
{
    public function run(): void
    {
        PaymentStatus::insert([
            ['name' => 'Pendente', 'slug' => 'pendente', 'color' => 'yellow'],
            ['name' => 'Pago', 'slug' => 'pago', 'color' => 'green'],
        ]);
    }
}
