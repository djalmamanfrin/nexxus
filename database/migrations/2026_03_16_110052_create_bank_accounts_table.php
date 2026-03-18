<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->ulid()->unique()->index();

            $table->foreignId('bank_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('name'); // Conta principal, Conta PJ etc
            $table->string('agency');
            $table->string('account_number');
            // checking (conta corrente), savings (poupança), wallet (carteira digital), cash (dinheiro físico)
            $table->string('type')->default('checking');
            $table->string('document')->nullable(); // cpf/cnpj titular
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bank_accounts');
    }
};
