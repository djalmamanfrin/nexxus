<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->ulid()->unique()->index();

            $table->foreignId('work_id')
                ->constrained('works')
                ->cascadeOnDelete();

            $table->foreignId('payment_status_id')
                ->constrained('payment_statuses')
                ->cascadeOnDelete();

            $table->foreignId('payment_type_id')
                ->default(1)
                ->constrained('payment_types')
                ->cascadeOnDelete();

            $table->foreignId('bank_account_id')
                ->nullable()
                ->constrained('bank_accounts')
                ->nullOnDelete();

            $table->decimal('amount', 12, 2)->default(0);
            $table->string('transaction_id')->nullable();
            $table->string('end_to_end_id')->nullable()->unique();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index('created_at');
            $table->index(['work_id', 'paid_at']);
            $table->index(['payment_status_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
