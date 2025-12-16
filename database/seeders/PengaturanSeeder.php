<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pengaturan; 

class PengaturanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Daftar pengaturan awal untuk Toko
        $settings = [
            [
                'key' => 'nomor_wa',
                'value' => '085603474373' 
            ],
            [
                'key' => 'alamat_toko',
                'value' => 'Jl. Arcamanik Endah Ruko III Kecamatan No.3, Sukamiskin, Kec. Arcamanik, Kota Bandung, Jawa Barat 40921'
            ],
        ];

        foreach ($settings as $setting) {
            Pengaturan::updateOrCreate(
                ['key' => $setting['key']], 
                ['value' => $setting['value']] 
            );
        }
    }
}