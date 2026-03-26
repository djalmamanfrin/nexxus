<?php

namespace Database\Seeders;

use App\Models\BankAccount;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BankAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BankAccount::create([
            'bank_id' => 1,
            'name' => 'Conta Principal',
            'agency' => '0001',
            'account_number' => '123456',
            'type' => 'checking',
            'document' => '12345678000123',
        ]);
    }
}
