<?php

namespace Database\Seeders;

use App\Models\CostCenterStatus;
use Illuminate\Database\Seeder;
use App\Models\PaymentStatus;

class CostCenterStatusSeeder extends Seeder
{
    public function run(): void
    {
        collect([
            ['name' => 'Pendente', 'slug' => 'pendente', 'color' => 'yellow'],
            ['name' => 'Concluído', 'slug' => 'done', 'color' => 'green'],
        ])->each(fn ($status) => CostCenterStatus::create($status));
    }
}
