<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ukuran_stok', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id')->constrained('barang')->onDelete('cascade');
            $table->enum('ukuran', ['S', 'M', 'L', 'XL']);
            $table->integer('stok');
            $table->timestamps();

            // Menambah unique constraint agar tidak ada duplikasi ukuran per barang
            $table->unique(['barang_id', 'ukuran']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('ukuran_stok');
    }
};
