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
                'nama_kategori' => 'Makanan ',
                'foto' => 'kategori/category1.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Minuman',
                'foto' => 'kategori/category2.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Kebutuhan Rumah',
                'foto' => 'kategori/category3.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Kesehatan & Kebersihan',
                'foto' => 'kategori/category4.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Lainnya',
                'foto' => 'kategori/category5.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
