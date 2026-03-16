<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Work;
use Illuminate\Support\Str;

class WorkSeeder extends Seeder
{
    public function run(): void
    {
        Work::insert([
            ['uuid' => Str::uuid(), 'name' => 'Construção Residencial Alpha'],
            ['uuid' => Str::uuid(), 'name' => 'Reforma Comercial Beta'],
        ]);
    }
}
