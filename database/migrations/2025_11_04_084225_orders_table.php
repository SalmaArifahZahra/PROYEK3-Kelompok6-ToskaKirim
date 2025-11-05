<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id('id_order');
            
            $table->foreignId('id_user')
                  ->constrained('users', 'id_user')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            $table->foreignId('id_shipping_costs')
                  ->constrained('shipping_costs', 'id_shipping_costs')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            $table->dateTime('order_date');
            $table->decimal('total_price', 15, 2);
            $table->enum('status_order', ['belum dikirim', 'sedang dikirim', 'sudah dikirim', 'dibatalkan'])
                  ->default('belum dikirim');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};