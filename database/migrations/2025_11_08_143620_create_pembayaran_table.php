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
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->bigIncrements('id_pembayaran');
            $table->unsignedBigInteger('id_pesanan')->unique();
            $table->string('bukti_bayar', 255);
            $table->decimal('jumlah_bayar', 10, 2);
            $table->date('tanggal_bayar');
            $table->enum('status_pembayaran', ['menunggu_verifikasi', 'diterima', 'ditolak']);
            $table->text('catatan_admin')->nullable();
            $table->timestampsTz();

            // Foreign Key Constraint
            $table->foreign('id_pesanan')->references('id_pesanan')->on('pesanan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};