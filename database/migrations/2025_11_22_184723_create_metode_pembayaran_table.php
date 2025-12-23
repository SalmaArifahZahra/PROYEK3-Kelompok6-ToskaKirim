<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('payment_methods', function (Blueprint $table) {
        $table->id();
        $table->string('nama_bank'); // Misal: BCA, BRI, QRIS
        $table->string('jenis');     // Misal: Transfer Bank, E-Wallet, COD
        $table->string('nomor_rekening')->nullable(); // Boleh kosong jika QRIS atau COD
        $table->string('atas_nama')->nullable();
        $table->string('gambar')->nullable();    // Path untuk logo bank atau foto QRIS, nullable untuk COD
        $table->boolean('is_active')->default(true); // Untuk fitur mata (hide/show)
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
