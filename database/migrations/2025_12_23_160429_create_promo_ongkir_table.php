<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::dropIfExists('promo_ongkir'); // Hapus tabel lama

        Schema::create('promo_ongkir', function (Blueprint $table) {
            $table->id();
            $table->string('nama_promo');     // Contoh: "Promo Gajian", "Gratis Ongkir Sultan"
            $table->text('deskripsi')->nullable();
            
            // --- LOGIKA UTAMA ---
            $table->decimal('min_belanja', 12, 2); // Syarat: 125.000
            $table->decimal('nilai_potongan', 8, 2); // Benefit: 3 (KM)
            
            // Pilihan Mekanisme: FLAT (Sekali) atau KELIPATAN (Berkali-kali)
            $table->enum('mekanisme', ['flat', 'kelipatan'])->default('flat'); 
            
            // Safety Net (Penting buat toko!)
            $table->decimal('maksimum_potongan', 8, 2)->nullable(); 
            
            // Tanggal Berlaku
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('promo_ongkir');
    }
};
