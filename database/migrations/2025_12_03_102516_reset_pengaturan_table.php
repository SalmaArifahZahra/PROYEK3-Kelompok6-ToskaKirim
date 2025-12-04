<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Hapus tabel lama yang salah
        Schema::dropIfExists('pengaturan');

        // 2. Buat tabel baru dengan struktur Key-Value
        Schema::create('pengaturan', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();   // Kolom untuk nama setting (misal: 'nomor_wa')
            $table->text('value')->nullable(); // Kolom untuk isi (misal: '08123456')
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturan');
    }
};