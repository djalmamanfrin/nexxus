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
            $table->uuid('uuid')->unique()->index();

            $table->foreignId('payment_status_id')
                ->default(1)
                ->constrained('payment_statuses')
                ->cascadeOnDelete();

            $table->foreignId('expense_id')
                ->constrained('expenses')
                ->cascadeOnDelete();

            $table->foreignId('bank_account_id')
                ->constrained('bank_accounts')
                ->cascadeOnDelete();

            $table->decimal('amount', 12, 2);
            $table->string('payment_method'); // pix | ted | boleto | cash
            $table->string('transaction_id')->nullable();
            $table->string('end_to_end_id')->nullable();
            $table->timestamp('paid_at')->nullable(); // pagamento
            $table->timestamps();
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
