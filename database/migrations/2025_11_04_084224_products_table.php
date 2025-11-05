<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id('id_products');
            
            $table->foreignId('id_categories')
                  ->constrained('categories', 'id_categories')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            $table->string('name', 100);
            $table->text('deskripsi')->nullable();
            $table->string('image')->nullable();
            $table->string('jenis', 50)->nullable();
            $table->string('tipe', 50)->nullable();
            $table->decimal('price', 15, 2);
            $table->integer('stock')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};