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
        Schema::create('produk_detail', function (Blueprint $table) {
            $table->bigIncrements('id_varian'); // Sesuai skema Anda
            $table->unsignedBigInteger('id_produk');
            $table->string('nama_varian', 150); // Sesuai skema Anda
            $table->string('foto', 255)->nullable();
            $table->decimal('harga_modal', 10, 2);
            $table->decimal('harga_jual', 10, 2);
            $table->integer('stok')->default(0);
            $table->timestampsTz();

            // Foreign Key Constraint
            $table->foreign('id_produk')->references('id_produk')->on('produk')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk_detail');
    }
};