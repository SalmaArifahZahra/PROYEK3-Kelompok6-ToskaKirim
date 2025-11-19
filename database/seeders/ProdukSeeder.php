<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('produk')->insert([
            [
                'id_kategori' => 1, // Makanan Ringan
                'nama' => 'Keripik Kentang',
                'deskripsi' => 'Keripik kentang renyah dengan berbagai rasa',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_kategori' => 1, // Makanan Ringan
                'nama' => 'Kacang Kulit',
                'deskripsi' => 'Kacang kulit asin renyah dan gurih',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_kategori' => 2, // Minuman
                'nama' => 'Teh Botol',
                'deskripsi' => 'Teh dalam kemasan botol siap minum',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_kategori' => 2, // Minuman
                'nama' => 'Jus Buah',
                'deskripsi' => 'Jus buah segar dalam kemasan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_kategori' => 3, // Makanan Berat
                'nama' => 'Nasi Goreng',
                'deskripsi' => 'Nasi goreng spesial dengan topping pilihan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_kategori' => 3, // Makanan Berat
                'nama' => 'Mie Ayam',
                'deskripsi' => 'Mie ayam dengan potongan ayam dan pangsit',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_kategori' => 4, // Roti & Kue
                'nama' => 'Roti Tawar',
                'deskripsi' => 'Roti tawar lembut untuk sarapan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_kategori' => 4, // Roti & Kue
                'nama' => 'Brownies',
                'deskripsi' => 'Brownies coklat lembut dan lezat',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_kategori' => 5, // Buah & Sayur
                'nama' => 'Apel',
                'deskripsi' => 'Apel merah segar dari kebun',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_kategori' => 5, // Buah & Sayur
                'nama' => 'Sayur Kangkung',
                'deskripsi' => 'Kangkung segar untuk tumis',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
