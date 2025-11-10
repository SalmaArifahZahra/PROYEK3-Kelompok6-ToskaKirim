<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PengaturanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pengaturan')->insert([
            'id_pengaturan' => 1,
            'tarif_per_km' => 5000.00,
            'foto_qris' => 'pengaturan/qris-toskikirim.jpg',
        ]);
    }
}
