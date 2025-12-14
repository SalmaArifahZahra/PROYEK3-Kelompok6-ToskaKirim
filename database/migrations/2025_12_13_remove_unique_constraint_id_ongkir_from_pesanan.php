<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Use raw SQL to safely drop the constraint if it exists
        DB::statement("ALTER TABLE pesanan DROP CONSTRAINT IF EXISTS pesanan_id_ongkir_unique CASCADE");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback not needed
    }
};
