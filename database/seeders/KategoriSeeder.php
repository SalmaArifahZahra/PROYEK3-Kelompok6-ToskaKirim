<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('kategori')->insert([
            [
                'nama_kategori' => 'Makanan Ringan',
                'foto' => 'kategori/makanan-ringan.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Minuman',
                'foto' => 'kategori/minuman.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Makanan Berat',
                'foto' => 'kategori/makanan-berat.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Roti & Kue',
                'foto' => 'kategori/roti-kue.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Buah & Sayur',
                'foto' => 'kategori/buah-sayur.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
