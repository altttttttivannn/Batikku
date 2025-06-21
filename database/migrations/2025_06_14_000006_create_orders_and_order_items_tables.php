<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Cek jika tabel orders belum ada, baru buat
        if (!Schema::hasTable('orders')) {
            Schema::create('orders', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->decimal('total_amount', 10, 2);
                $table->text('shipping_address');
                $table->decimal('shipping_cost', 10, 2)->default(15000); // Fixed shipping cost
                $table->string('payment_status')->default('pending');
                $table->string('order_status')->default('pending');
                $table->timestamps();
            });
        }
        // Order items tetap dibuat
        if (!Schema::hasTable('order_items')) {
            Schema::create('order_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->constrained()->onDelete('cascade');
                $table->foreignId('barang_id')->constrained()->onDelete('cascade');
                $table->string('ukuran');
                $table->integer('quantity');
                $table->decimal('price', 10, 2);
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('order_items');
        // Jangan drop orders di down jika memang sudah ada sebelum migrasi ini
    }
};
