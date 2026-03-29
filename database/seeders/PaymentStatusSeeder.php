<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentStatus;

class PaymentStatusSeeder extends Seeder
{
    public function run(): void
    {
        PaymentStatus::create([
            ['name' => 'Pendente', 'slug' => 'pendente', 'color' => 'yellow'],
            ['name' => 'Concluído', 'slug' => 'done', 'color' => 'green'],
        ]);
    }
}
