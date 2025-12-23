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
                'kota_kabupaten' => 'Kota Bandung',
                'kecamatan' => 'Cibeunying Kidul',
                'kelurahan' => 'Cicadas',
                'rt' => '007',
                'rw' => '013',
                'no_rumah' => '4',
                'jalan_patokan' => 'Jl. Yudhawastu Pramuka VII',
                'is_utama' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 3, // Budi Santoso
                'label_alamat' => 'Kantor',
                'nama_penerima' => 'Budi Santoso',
                'telepon_penerima' => '081234567890',
                'kota_kabupaten' => 'Kota Bandung',
                'kecamatan' => 'Sumur Bandung',
                'kelurahan' => 'Merdeka',
                'rt' => '002',
                'rw' => '006',
                'no_rumah' => '36A',
                'jalan_patokan' => 'Jl. Patrakomala No. 36A, Kantor Kelurahan Merdeka',
                'is_utama' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 4, 
                'label_alamat' => 'Rumah',
                'nama_penerima' => 'Siti Nurhaliza',
                'telepon_penerima' => '082345678901',
                'kota_kabupaten' => 'Kabupaten Bandung',
                'kecamatan' => 'Cileunyi',
                'kelurahan' => 'Cileunyi Kulon',
                'rt' => '001',
                'rw' => '004',
                'no_rumah' => '49',
                'jalan_patokan' => 'Komplek Taman Cileunyi Blok 2A, Toko Pak Odo',
                'is_utama' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'id_user' => 5, // Ahmad Rizky
                'label_alamat' => 'Rumah',
                'nama_penerima' => 'Ahmad Rizky',
                'telepon_penerima' => '083456789012',
                'kota_kabupaten' => 'Kabupaten Bandung Barat',
                'kecamatan' => 'Parongpong',
                'kelurahan' => 'Sariwangi',
                'rt' => '004',
                'rw' => '006',
                'no_rumah' => '27',
                'jalan_patokan' => 'Gg. Cemara 10, Jl. Sariwangi Selatan, Kost Pa Dedi',
                'is_utama' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 6, // Dewi Lestari
                'label_alamat' => 'Rumah',
                'nama_penerima' => 'Dewi Lestari',
                'telepon_penerima' => '084567890123',
                'kota_kabupaten' => 'Kabupaten Bandung',
                'kecamatan' => 'Baleendah',
                'kelurahan' => 'Jelekong',
                'rt' => '001',
                'rw' => '004',
                'no_rumah' => '12',
                'jalan_patokan' => 'Gg. Batu Gajah, Jl. Jelekong, Toko Asep Etoy',
                'is_utama' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
