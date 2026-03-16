<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique()->index();
            $table->foreignId('payment_status_id')
                ->default(1)
                ->constrained('payment_statuses');

            $table->foreignId('payee_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('cost_center_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('expense_category_id')
                ->nullable()
                ->constrained('expense_categories')
                ->nullOnDelete();

            $table->string('reference')->nullable();
            $table->decimal('amount', 12, 2);
            $table->date('due_at')->nullable()->index(); // vencimento
            $table->date('competence_date')->nullable()->index(); // vencimento
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
