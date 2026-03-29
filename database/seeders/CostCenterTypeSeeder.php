<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CostCenterType;

class CostCenterTypeSeeder extends Seeder
{
    public function run(): void
    {
        collect([
            ['name' => 'Outros', 'code' => 'OUT'],
            ['name' => 'Operational', 'code' => 'OP'],
            ['name' => 'Administrative', 'code' => 'ADM'],
            ['name' => 'Investment', 'code' => 'INV'],
        ])->each(fn ($type) => CostCenterType::create($type));
    }
}
