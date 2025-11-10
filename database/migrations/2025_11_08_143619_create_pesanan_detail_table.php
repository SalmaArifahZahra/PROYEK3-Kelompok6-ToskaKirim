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
        Schema::create('pesanan_detail', function (Blueprint $table) {
            $table->unsignedBigInteger('id_pesanan');
            $table->unsignedBigInteger('id_varian');
            $table->integer('kuantitas');
            $table->decimal('harga_saat_beli', 10, 2);
            $table->decimal('subtotal_item', 10, 2);
            $table->timestampsTz();

            // Composite Primary Key
            $table->primary(['id_pesanan', 'id_varian']);

            // Foreign Key Constraints
            $table->foreign('id_pesanan')->references('id_pesanan')->on('pesanan')->onDelete('cascade');
            $table->foreign('id_varian')->references('id_varian')->on('produk_detail')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan_detail');
    }
};