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
        Schema::create('expense_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Ex: Pending, Concluído, Cancelled
            $table->string('slug')->unique(); // Ex: pending, done, cancelled
            $table->string('color')->default('gray'); // usado em UI (badge, label)
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expense_statuses');
    }
};
