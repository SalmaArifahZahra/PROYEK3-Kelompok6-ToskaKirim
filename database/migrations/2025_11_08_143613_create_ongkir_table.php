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
        Schema::create('ongkir', function (Blueprint $table) {
            $table->bigIncrements('id_ongkir');
            $table->decimal('jarak', 5, 2);
            $table->decimal('tarif_per_km', 10, 2);
            $table->decimal('total_ongkir', 10, 2);
            $table->timestampsTz();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ongkir');
    }
};