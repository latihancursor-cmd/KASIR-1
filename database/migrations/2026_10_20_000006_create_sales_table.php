<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->unsignedInteger('quantity');
            $table->decimal('total_price', 12, 2);
            $table->timestamp('sold_at')->useCurrent();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sales');
    }
};
