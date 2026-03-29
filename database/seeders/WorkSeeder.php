<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Work;
use Illuminate\Support\Str;

class WorkSeeder extends Seeder
{
    public function run(): void
    {
        Work::create([
            ['ulid' => Str::ulid(), 'name' => 'Construção Residencial Alpha', 'is_active' => true],
            ['ulid' => Str::ulid(), 'name' => 'Papelaria Mega', 'is_active' => true],
            ['ulid' => Str::ulid(), 'name' => 'Reforma Comercial Beta', 'is_active' => false],
        ]);
    }
}
