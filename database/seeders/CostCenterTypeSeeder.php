<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CostCenterType;

class CostCenterTypeSeeder extends Seeder
{
    public function run(): void
    {
        CostCenterType::insert([
            ['name' => 'Outros', 'code' => 'OUT'],
            ['name' => 'Operational', 'code' => 'OP'],
            ['name' => 'Administrative', 'code' => 'ADM'],
            ['name' => 'Investment', 'code' => 'INV'],
        ]);
    }
}
