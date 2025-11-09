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
        Schema::create('pesanan', function (Blueprint $table) {
            $table->bigIncrements('id_pesanan');
            $table->unsignedBigInteger('id_user')->nullable();
            $table->unsignedBigInteger('id_ongkir')->unique();
            $table->timestampTz('waktu_pesanan')->useCurrent();
            $table->decimal('subtotal_produk', 10, 2);
            $table->decimal('grand_total', 10, 2);
            $table->enum('status_pesanan', [
                'menunggu_pembayaran',
                'menunggu_verifikasi',
                'diproses',
                'dikirim',
                'selesai',
                'dibatalkan'
            ]);
            
            // Snapshot Alamat
            $table->string('penerima_nama', 255);
            $table->string('penerima_telepon', 20);
            $table->text('alamat_lengkap');
            
            $table->timestampsTz();

            // Foreign Key Constraints
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('set null');
            $table->foreign('id_ongkir')->references('id_ongkir')->on('ongkir')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};