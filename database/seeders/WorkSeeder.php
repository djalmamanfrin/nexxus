<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Work;
use Illuminate\Support\Str;

class WorkSeeder extends Seeder
{
    public function run(): void
    {
        collect([
            ['ulid' => Str::ulid(), 'name' => 'Papelaria Mega', 'code' => 'PAPMG', 'is_active' => true],
            ['ulid' => Str::ulid(), 'name' => 'Reforma Comercial Beta', 'code' => 'RCBET', 'is_active' => false],
        ])->each(fn ($work) => Work::create($work));
    }
}
