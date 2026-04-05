<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cost_center_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Ex: Pending, Done - A respeito se todos os campos necessários foram preenchidos
            $table->string('slug')->unique(); // Ex: pending, done
            $table->string('color')->default('gray'); // usado em UI (badge, label)
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cost_center_statuses');
    }
};
