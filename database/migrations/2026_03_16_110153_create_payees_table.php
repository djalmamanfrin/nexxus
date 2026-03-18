<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payees', function (Blueprint $table) {
            $table->id();
            $table->ulid()->unique()->index();
            $table->string('name');
            $table->string('document'); // CPF ou CNPJ
            $table->string('document_type')->nullable(); // cpf | cnpj
            $table->string('pix_key')->nullable();
            $table->string('pix_key_type')->nullable(); // cpf | cnpj | email | phone | random
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payees');
    }
};
