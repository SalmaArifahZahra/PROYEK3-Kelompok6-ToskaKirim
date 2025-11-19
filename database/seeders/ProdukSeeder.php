<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProdukSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('produk')->insert([
            // Kategori 1 - Makanan
            [
                'id_kategori' => 1,
                'nama' => 'Minyak Goreng Bimoli 1Liter',
                'deskripsi' => 'Minyak goreng kualitas premium untuk masak sehari-hari',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_kategori' => 1,
                'nama' => 'Beras Pandan Wangi Sania 5Kg',
                'deskripsi' => 'Beras wangi pilihan dengan kualitas unggul',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Kategori 2 - Minuman
            [
                'id_kategori' => 2,
                'nama' => 'Aqua Air Mineral 600ml',
                'deskripsi' => 'Air mineral segar kemasan botol',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_kategori' => 2,
                'nama' => 'Ultra Teh Kotak Extra 300ml',
                'deskripsi' => 'Teh melati dalam kemasan kotak siap minum',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Kategori 3 - Kebutuhan Rumah
            [
                'id_kategori' => 3,
                'nama' => 'Rinso Detergent Anti Noda 800g',
                'deskripsi' => 'Deterjen bubuk untuk membersihkan pakaian',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_kategori' => 3,
                'nama' => 'Spons Cuci Piring Scotch-Brite',
                'deskripsi' => 'Spons untuk mencuci peralatan dapur',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Kategori 4 - Kesehatan & Kebersihan
            [
                'id_kategori' => 4,
                'nama' => 'Lifebuoy Sabun Mandi fresh 60g',
                'deskripsi' => 'Sabun kesehatan untuk perlindungan dari kuman',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_kategori' => 4,
                'nama' => 'Instance Hand Sanitizer Sray 100ml',
                'deskripsi' => 'Pembersih tangan tanpa air praktis digunakan',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Kategori 5 - Lainnya
            [
                'id_kategori' => 5,
                'nama' => 'Djarum super rokok ',
                'deskripsi' => 'Rokok kretek filter berkualitas',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_kategori' => 5,
                'nama' => 'Baterai ABC 4pcs',
                'deskripsi' => 'Baterai ukuran AA untuk berbagai perangkat',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
