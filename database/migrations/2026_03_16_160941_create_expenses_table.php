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
            $table->ulid()->unique()->index();

            $table->foreignId('work_id')
                ->constrained('works')
                ->cascadeOnDelete();

            $table->foreignId('expense_status_id')
                ->constrained('expense_statuses')
                ->cascadeOnDelete();

            $table->foreignId('payee_id')
                ->nullable()
                ->constrained('payees')
                ->nullOnDelete();

            $table->foreignId('cost_center_id')
                ->nullable()
                ->constrained('cost_centers')
                ->nullOnDelete();

            $table->foreignId('expense_category_id')
                ->default(1)
                ->constrained('expense_categories')
                ->cascadeOnDelete();

            $table->string('reference')->nullable();
            $table->integer('amount')->default(0);
            $table->date('due_at')->nullable()->index(); // vencimento
            $table->date('competence_date')->nullable()->index(); // vencimento
            $table->string('description')->nullable();
            $table->timestamps();

            $table->index('created_at');
            $table->index(['work_id', 'due_at']);
            $table->index(['work_id', 'expense_status_id']);
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
