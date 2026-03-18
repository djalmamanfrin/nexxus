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
            ['ulid' => Str::ulid(), 'name' => 'Construção Residencial Alpha'],
            ['ulid' => Str::ulid(), 'name' => 'Reforma Comercial Beta'],
        ]);
    }
}
