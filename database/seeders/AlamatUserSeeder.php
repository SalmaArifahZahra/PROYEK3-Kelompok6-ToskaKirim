<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AlamatUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('alamat_user')->insert([
            [
                'id_user' => 3, // Budi Santoso
                'label_alamat' => 'Rumah',
                'nama_penerima' => 'Budi Santoso',
                'telepon_penerima' => '081234567890',
                'kota_kabupaten' => 'Jakarta Selatan',
                'kecamatan' => 'Kebayoran Baru',
                'kelurahan' => 'Senayan',
                'rt' => '005',
                'rw' => '003',
                'no_rumah' => '12A',
                'jalan_patokan' => 'Jl. Senayan Raya No. 12A, dekat GBK',
                'is_utama' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 3, // Budi Santoso
                'label_alamat' => 'Kantor',
                'nama_penerima' => 'Budi Santoso',
                'telepon_penerima' => '081234567890',
                'kota_kabupaten' => 'Jakarta Pusat',
                'kecamatan' => 'Tanah Abang',
                'kelurahan' => 'Kebon Melati',
                'rt' => '010',
                'rw' => '005',
                'no_rumah' => '88',
                'jalan_patokan' => 'Jl. Sudirman No. 88, gedung perkantoran lt. 5',
                'is_utama' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 4, // Siti Nurhaliza
                'label_alamat' => 'Rumah',
                'nama_penerima' => 'Siti Nurhaliza',
                'telepon_penerima' => '082345678901',
                'kota_kabupaten' => 'Jakarta Timur',
                'kecamatan' => 'Matraman',
                'kelurahan' => 'Palmeriam',
                'rt' => '008',
                'rw' => '004',
                'no_rumah' => '25',
                'jalan_patokan' => 'Jl. Palmeriam Raya No. 25, dekat Taman Palmeriam',
                'is_utama' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 5, // Ahmad Rizky
                'label_alamat' => 'Rumah',
                'nama_penerima' => 'Ahmad Rizky',
                'telepon_penerima' => '083456789012',
                'kota_kabupaten' => 'Tangerang',
                'kecamatan' => 'Serpong',
                'kelurahan' => 'Serpong',
                'rt' => '012',
                'rw' => '006',
                'no_rumah' => '45',
                'jalan_patokan' => 'Jl. BSD Raya No. 45, komplek BSD City',
                'is_utama' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 6, // Dewi Lestari
                'label_alamat' => 'Rumah',
                'nama_penerima' => 'Dewi Lestari',
                'telepon_penerima' => '084567890123',
                'kota_kabupaten' => 'Bekasi',
                'kecamatan' => 'Bekasi Barat',
                'kelurahan' => 'Kranji',
                'rt' => '007',
                'rw' => '002',
                'no_rumah' => '33',
                'jalan_patokan' => 'Jl. Kranji Raya No. 33, dekat stasiun Kranji',
                'is_utama' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
