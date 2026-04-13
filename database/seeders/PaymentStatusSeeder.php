<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentStatus;

class PaymentStatusSeeder extends Seeder
{
    public function run(): void
    {
        collect([
            ['name' => 'Pendente', 'slug' => 'pendente', 'color' => 'gray'],
            ['name' => 'Não conciliado', 'slug' => 'nao-conciliado', 'color' => 'yellow'],
            ['name' => 'Parcial', 'slug' => 'parcial', 'color' => 'orange'],
            ['name' => 'Conciliado', 'slug' => 'conciliado', 'color' => 'green'],
            ['name' => 'Excedente', 'slug' => 'excedente', 'color' => 'red'],
            ['name' => 'Cancelado', 'slug' => 'cancelado', 'color' => 'amber'],
        ])->each(fn ($status) => PaymentStatus::create($status));
    }
}
