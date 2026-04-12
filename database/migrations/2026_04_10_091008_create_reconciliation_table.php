<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reconciliation', function (Blueprint $table) {
            $table->id();

            $table->foreignId('expense_id')
                ->constrained('expenses')
                ->cascadeOnDelete();

            $table->foreignId('payment_id')
                ->constrained('payments')
                ->cascadeOnDelete();


            $table->integer('amount')->default(0);
            $table->timestamp('linked_at')->useCurrent();
            $table->timestamps();

            $table->unique(['expense_id', 'payment_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reconciliation');
    }
};
