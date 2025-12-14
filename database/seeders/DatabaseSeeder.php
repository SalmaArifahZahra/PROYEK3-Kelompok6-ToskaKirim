<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            KategoriSeeder::class,
            // PengaturanSeeder::class,
            OngkirSeeder::class,
            AlamatUserSeeder::class,
            ProdukSeeder::class,
            ProdukDetailSeeder::class,
            KeranjangSeeder::class,
            PesananSeeder::class,
            PesananDetailSeeder::class,
            PembayaranSeeder::class,
            WilayahImportSeeder::class,
        ]);
    }
}
