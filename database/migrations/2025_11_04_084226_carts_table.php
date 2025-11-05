<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id('id_carts');
            
            $table->foreignId('id_user')
                  ->constrained('users', 'id_user')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->foreignId('id_products')
                  ->constrained('products', 'id_products')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->integer('quantity');
            $table->timestamps();

            $table->unique(['id_user', 'id_products']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};