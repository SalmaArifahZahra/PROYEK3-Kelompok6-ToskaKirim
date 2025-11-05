<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipping_costs', function (Blueprint $table) {
            $table->id('id_shipping_costs');
            $table->decimal('jarak', 10, 2)->nullable();
            $table->decimal('tarif', 15, 2)->nullable();
            $table->decimal('shipping_costs', 15, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipping_costs');
    }
};