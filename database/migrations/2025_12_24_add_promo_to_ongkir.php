<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ongkir', function (Blueprint $table) {
            $table->decimal('jarak_before', 5, 2)->nullable()->comment('Jarak sebelum promo diterapkan');
            $table->string('promo_name')->nullable()->comment('Nama promo yang diterapkan');
        });
    }

    public function down(): void
    {
        Schema::table('ongkir', function (Blueprint $table) {
            $table->dropColumn(['jarak_before', 'promo_name']);
        });
    }
};
