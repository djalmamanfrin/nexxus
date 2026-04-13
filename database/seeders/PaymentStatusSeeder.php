<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentStatus;

class PaymentStatusSeeder extends Seeder
{
    public function run(): void
    {
        collect([
            ['name' => 'Pendente', 'slug' => 'pendente', 'color' => 'gray', 'sort_order' => 1],
            ['name' => 'Parcial', 'slug' => 'parcial', 'color' => 'yellow', 'sort_order' => 2],
            ['name' => 'Conciliado', 'slug' => 'conciliado', 'color' => 'green', 'sort_order' => 3],
            ['name' => 'Excedente', 'slug' => 'excedente', 'color' => 'orange', 'sort_order' => 4],
        ])->each(fn ($status) => PaymentStatus::create($status));
    }
}
