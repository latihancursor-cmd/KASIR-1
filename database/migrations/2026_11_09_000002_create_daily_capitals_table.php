<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_capitals', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique();
            $table->decimal('initial_capital', 15, 2)->default(0);
            $table->decimal('expenses', 15, 2)->default(0);
            $table->decimal('counted_cash', 15, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_capitals');
    }
};
