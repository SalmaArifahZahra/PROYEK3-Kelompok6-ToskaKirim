<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KeranjangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('keranjang')->insert([
            [
                'id_user' => 3, // Budi Santoso
                'id_produk_detail' => 1, // Keripik Kentang Original
                'quantity' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 3, // Budi Santoso
                'id_produk_detail' => 5, // Teh Botol Original
                'quantity' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 4, // Siti Nurhaliza
                'id_produk_detail' => 9, // Nasi Goreng Biasa
                'quantity' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 4, // Siti Nurhaliza
                'id_produk_detail' => 7, // Jus Jeruk
                'quantity' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 5, // Ahmad Rizky
                'id_produk_detail' => 15, // Brownies Original
                'quantity' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 6, // Dewi Lestari
                'id_produk_detail' => 17, // Apel Merah
                'quantity' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 6, // Dewi Lestari
                'id_produk_detail' => 19, // Kangkung
                'quantity' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
