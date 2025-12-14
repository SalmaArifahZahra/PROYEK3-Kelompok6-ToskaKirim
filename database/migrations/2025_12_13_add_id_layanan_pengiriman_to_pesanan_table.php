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
        Schema::table('pesanan', function (Blueprint $table) {
            $table->unsignedBigInteger('id_layanan_pengiriman')->nullable()->after('id_ongkir');
            $table->foreign('id_layanan_pengiriman')->references('id')->on('layanan_pengiriman')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->dropForeign(['id_layanan_pengiriman']);
            $table->dropColumn('id_layanan_pengiriman');
        });
    }
};
