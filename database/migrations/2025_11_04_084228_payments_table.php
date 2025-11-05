<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id('id_payments');
            
        $table->foreignId('id_order')
            ->constrained('orders', 'id_order')
            ->onUpdate('cascade')
            ->onDelete('cascade')
            ->unique();
            $table->dateTime('payments_date');
            $table->string('bukti_tf')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};