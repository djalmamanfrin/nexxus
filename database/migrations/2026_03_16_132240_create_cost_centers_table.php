<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cost_centers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('work_id')
                ->constrained('works')
                ->cascadeOnDelete();

            $table->foreignId('cost_center_status_id')
                ->default(1)
                ->constrained('cost_center_statuses')
                ->cascadeOnDelete();

            $table->foreignId('cost_center_type_id')
                ->default(1)
                ->constrained('cost_center_types')
                ->cascadeOnDelete();

            $table->date('start_date')->nullable();
            $table->date('expected_end_date')->nullable();
            $table->decimal('budget', 14, 2)->nullable();
            $table->timestamps();

            $table->unique(['work_id', 'cost_center_type_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cost_centers');
    }
};
