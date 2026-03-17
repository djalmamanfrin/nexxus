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

            $table->foreignId('payee_id')
                ->constrained('payees')
                ->cascadeOnDelete();

            $table->foreignId('cost_center_id')
                ->constrained('cost_centers')
                ->cascadeOnDelete();

            $table->foreignId('expense_category_id')
                ->default(1)
                ->constrained('expense_categories')
                ->cascadeOnDelete();

            $table->string('reference')->nullable();
            $table->decimal('amount', 12, 2);
            $table->date('due_at')->nullable()->index(); // vencimento
            $table->date('competence_date')->nullable()->index(); // vencimento
            $table->string('description')->nullable();
            $table->timestamps();
            $table->index('created_at');
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
