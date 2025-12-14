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
        // 1. Tabel Layanan
        Schema::create('layanan_pengiriman', function (Blueprint $table) {
            $table->id();
            $table->string('nama_layanan'); 
            $table->integer('tarif_per_km'); 
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Tabel Promo Ongkir
        Schema::create('promo_ongkir', function (Blueprint $table) {
            $table->id();
            $table->string('nama_promo');
            $table->integer('min_belanja'); 
            $table->integer('potongan_jarak');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 3. Tabel Wilayah 
        Schema::create('wilayah_pengiriman', function (Blueprint $table) {
            $table->id();
            $table->string('kota_kabupaten')->default('Kota Bandung'); // Default
            $table->string('kecamatan');
            $table->string('kelurahan');
            $table->decimal('jarak_km', 8, 3)->default(0); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wilayah_pengiriman');
        Schema::dropIfExists('promo_ongkir');
        Schema::dropIfExists('layanan_pengiriman');
    }
};
