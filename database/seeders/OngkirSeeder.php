<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OngkirSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ongkir')->insert([
            [
                'jarak' => 2.50,
                'tarif_per_km' => 5000.00,
                'total_ongkir' => 12500.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'jarak' => 5.00,
                'tarif_per_km' => 5000.00,
                'total_ongkir' => 25000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'jarak' => 7.50,
                'tarif_per_km' => 5000.00,
                'total_ongkir' => 37500.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'jarak' => 10.00,
                'tarif_per_km' => 5000.00,
                'total_ongkir' => 50000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
