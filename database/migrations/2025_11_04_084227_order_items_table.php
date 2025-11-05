<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        
        Schema::create('orders_item', function (Blueprint $table) {
            $table->foreignId('id_order')
                  ->constrained('orders', 'id_order')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->foreignId('id_products')
                  ->constrained('products', 'id_products')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');

            $table->integer('quantity');

            $table->primary(['id_order', 'id_products']);
            
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders_item');
    }
};