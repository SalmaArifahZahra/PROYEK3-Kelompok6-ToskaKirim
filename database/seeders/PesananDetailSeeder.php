<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PesananDetailSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('pesanan_detail')->insert([
            // Pesanan 1 - Budi Santoso
            [
                'id_pesanan' => 1,
                'id_varian' => 1, // Keripik Kentang Original
                'kuantitas' => 2,
                'harga_saat_beli' => 12000.00,
                'subtotal_item' => 24000.00,
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
            [
                'id_pesanan' => 1,
                'id_varian' => 5, // Teh Botol Original
                'kuantitas' => 4,
                'harga_saat_beli' => 6000.00,
                'subtotal_item' => 24000.00,
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
            [
                'id_pesanan' => 1,
                'id_varian' => 11, // Mie Ayam Regular
                'kuantitas' => 2,
                'harga_saat_beli' => 13000.00,
                'subtotal_item' => 26000.00,
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],

            // Pesanan 2 - Siti Nurhaliza
            [
                'id_pesanan' => 2,
                'id_varian' => 9, // Nasi Goreng Biasa
                'kuantitas' => 3,
                'harga_saat_beli' => 15000.00,
                'subtotal_item' => 45000.00,
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
            ],
            [
                'id_pesanan' => 2,
                'id_varian' => 10, // Nasi Goreng Spesial
                'kuantitas' => 2,
                'harga_saat_beli' => 22000.00,
                'subtotal_item' => 44000.00,
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
            ],
            [
                'id_pesanan' => 2,
                'id_varian' => 7, // Jus Jeruk
                'kuantitas' => 4,
                'harga_saat_beli' => 10000.00,
                'subtotal_item' => 40000.00,
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
            ],

            // Pesanan 3 - Ahmad Rizky
            [
                'id_pesanan' => 3,
                'id_varian' => 15, // Brownies Original
                'kuantitas' => 1,
                'harga_saat_beli' => 50000.00,
                'subtotal_item' => 50000.00,
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
            [
                'id_pesanan' => 3,
                'id_varian' => 13, // Roti Tawar Gandum
                'kuantitas' => 2,
                'harga_saat_beli' => 18000.00,
                'subtotal_item' => 36000.00,
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],

            // Pesanan 4 - Dewi Lestari
            [
                'id_pesanan' => 4,
                'id_varian' => 17, // Apel Merah
                'kuantitas' => 2,
                'harga_saat_beli' => 35000.00,
                'subtotal_item' => 70000.00,
                'created_at' => now()->subDay(),
                'updated_at' => now()->subDay(),
            ],
            [
                'id_pesanan' => 4,
                'id_varian' => 19, // Kangkung
                'kuantitas' => 5,
                'harga_saat_beli' => 5000.00,
                'subtotal_item' => 25000.00,
                'created_at' => now()->subDay(),
                'updated_at' => now()->subDay(),
            ],
        ]);
    }
}
