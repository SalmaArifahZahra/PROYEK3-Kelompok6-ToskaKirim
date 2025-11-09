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
        Schema::create('alamat_user', function (Blueprint $table) {
            $table->bigIncrements('id_alamat');
            $table->unsignedBigInteger('id_user');
            $table->string('label_alamat', 100);
            $table->string('nama_penerima', 255);
            $table->string('telepon_penerima', 20);
            $table->string('kota_kabupaten', 100);
            $table->string('kecamatan', 100);
            $table->string('kelurahan', 100);
            $table->string('rt', 5)->nullable();
            $table->string('rw', 5)->nullable();
            $table->string('no_rumah', 20)->nullable();
            $table->text('jalan_patokan');
            $table->boolean('is_utama')->default(false);
            $table->timestampsTz();

            // Foreign Key Constraint
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alamat_user');
    }
};