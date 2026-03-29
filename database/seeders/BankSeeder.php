<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bank;

class BankSeeder extends Seeder
{
    public function run(): void
    {
        Bank::create([
            ['code' => '001', 'name' => 'Banco do Brasil'],
            ['code' => '237', 'name' => 'Bradesco'],
            ['code' => '341', 'name' => 'Itaú'],
            ['code' => '033', 'name' => 'Santander'],
            ['code' => '260', 'name' => 'Nubank'],
            ['code' => '077', 'name' => 'Inter'],
        ]);
    }
}
