<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentStatus;

class PaymentStatusSeeder extends Seeder
{
    public function run(): void
    {
        PaymentStatus::insert([
            ['name' => 'Pending', 'slug' => 'pending', 'color' => 'yellow'],
            ['name' => 'Paid', 'slug' => 'paid', 'color' => 'green'],
            ['name' => 'Cancelled', 'slug' => 'cancelled', 'color' => 'gray'],
            ['name' => 'Failed', 'slug' => 'failed', 'color' => 'red'],
        ]);
    }
}
