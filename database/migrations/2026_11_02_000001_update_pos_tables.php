<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // 1. Tambah kolom pada categories jika belum ada
        Schema::table('categories', function (Blueprint $table) {
            if (!Schema::hasColumn('categories', 'code')) {
                $table->string('code', 10)->nullable()->after('name');
            }
        });

        // 2. Tambah kolom pada products jika belum ada
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'barcode')) {
                $table->string('barcode')->nullable()->after('sku');
            }
            if (!Schema::hasColumn('products', 'cost_price')) {
                $table->decimal('cost_price', 12, 2)->default(0)->after('description');
            }
            if (!Schema::hasColumn('products', 'min_stock')) {
                $table->unsignedInteger('min_stock')->default(0)->after('stock');
            }
            if (!Schema::hasColumn('products', 'unit')) {
                $table->string('unit', 20)->default('pcs')->after('min_stock');
            }
            if (!Schema::hasColumn('products', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('unit');
            }
        });

        // 3. Buat tabel store_settings
        if (!Schema::hasTable('store_settings')) {
            Schema::create('store_settings', function (Blueprint $table) {
                $table->id();
                $table->string('store_name')->default('KasirNih');
                $table->text('address')->nullable();
                $table->string('phone')->nullable();
                $table->boolean('closing_reminder')->default(false);
                $table->string('closing_time', 10)->default('21:00');
                $table->timestamps();
            });
        }

        // 4. Update tabel sales untuk menyimpan modal & keuntungan
        Schema::table('sales', function (Blueprint $table) {
            if (!Schema::hasColumn('sales', 'invoice_no')) {
                $table->string('invoice_no', 30)->nullable()->after('id');
            }
            if (!Schema::hasColumn('sales', 'cost_price')) {
                $table->decimal('cost_price', 12, 2)->default(0)->after('quantity');
            }
            if (!Schema::hasColumn('sales', 'profit')) {
                $table->decimal('profit', 12, 2)->default(0)->after('total_price');
            }
            if (!Schema::hasColumn('sales', 'payment_method')) {
                $table->string('payment_method', 20)->default('Tunai')->after('profit');
            }
        });
    }

    public function down()
    {
        // No action needed for down to protect existing fields
    }
};
