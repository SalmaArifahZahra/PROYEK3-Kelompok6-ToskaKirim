<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProdukDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('produk_detail')->insert([
            // Keripik Kentang
            [
                'id_produk' => 1,
                'nama_varian' => 'Original 100gr',
                'foto' => 'produk/keripik-kentang-original.jpg',
                'harga_modal' => 8000.00,
                'harga_jual' => 12000.00,
                'stok' => 50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_produk' => 1,
                'nama_varian' => 'Balado 100gr',
                'foto' => 'produk/keripik-kentang-balado.jpg',
                'harga_modal' => 8500.00,
                'harga_jual' => 13000.00,
                'stok' => 45,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Kacang Kulit
            [
                'id_produk' => 2,
                'nama_varian' => 'Asin 200gr',
                'foto' => 'produk/kacang-kulit-asin.jpg',
                'harga_modal' => 12000.00,
                'harga_jual' => 18000.00,
                'stok' => 30,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_produk' => 2,
                'nama_varian' => 'Pedas 200gr',
                'foto' => 'produk/kacang-kulit-pedas.jpg',
                'harga_modal' => 12500.00,
                'harga_jual' => 19000.00,
                'stok' => 25,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Teh Botol
            [
                'id_produk' => 3,
                'nama_varian' => 'Original 500ml',
                'foto' => 'produk/teh-botol-original.jpg',
                'harga_modal' => 4000.00,
                'harga_jual' => 6000.00,
                'stok' => 100,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_produk' => 3,
                'nama_varian' => 'Less Sugar 500ml',
                'foto' => 'produk/teh-botol-less-sugar.jpg',
                'harga_modal' => 4500.00,
                'harga_jual' => 6500.00,
                'stok' => 80,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Jus Buah
            [
                'id_produk' => 4,
                'nama_varian' => 'Jeruk 250ml',
                'foto' => 'produk/jus-jeruk.jpg',
                'harga_modal' => 6000.00,
                'harga_jual' => 10000.00,
                'stok' => 60,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_produk' => 4,
                'nama_varian' => 'Mangga 250ml',
                'foto' => 'produk/jus-mangga.jpg',
                'harga_modal' => 7000.00,
                'harga_jual' => 11000.00,
                'stok' => 55,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Nasi Goreng
            [
                'id_produk' => 5,
                'nama_varian' => 'Biasa',
                'foto' => 'produk/nasi-goreng-biasa.jpg',
                'harga_modal' => 10000.00,
                'harga_jual' => 15000.00,
                'stok' => 20,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_produk' => 5,
                'nama_varian' => 'Spesial',
                'foto' => 'produk/nasi-goreng-spesial.jpg',
                'harga_modal' => 15000.00,
                'harga_jual' => 22000.00,
                'stok' => 15,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Mie Ayam
            [
                'id_produk' => 6,
                'nama_varian' => 'Regular',
                'foto' => 'produk/mie-ayam-regular.jpg',
                'harga_modal' => 8000.00,
                'harga_jual' => 13000.00,
                'stok' => 25,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_produk' => 6,
                'nama_varian' => 'Jumbo',
                'foto' => 'produk/mie-ayam-jumbo.jpg',
                'harga_modal' => 12000.00,
                'harga_jual' => 18000.00,
                'stok' => 20,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Roti Tawar
            [
                'id_produk' => 7,
                'nama_varian' => 'Gandum',
                'foto' => 'produk/roti-tawar-gandum.jpg',
                'harga_modal' => 12000.00,
                'harga_jual' => 18000.00,
                'stok' => 30,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_produk' => 7,
                'nama_varian' => 'Putih',
                'foto' => 'produk/roti-tawar-putih.jpg',
                'harga_modal' => 10000.00,
                'harga_jual' => 15000.00,
                'stok' => 35,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Brownies
            [
                'id_produk' => 8,
                'nama_varian' => 'Original 20x20cm',
                'foto' => 'produk/brownies-original.jpg',
                'harga_modal' => 35000.00,
                'harga_jual' => 50000.00,
                'stok' => 15,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_produk' => 8,
                'nama_varian' => 'Keju 20x20cm',
                'foto' => 'produk/brownies-keju.jpg',
                'harga_modal' => 40000.00,
                'harga_jual' => 55000.00,
                'stok' => 12,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Apel
            [
                'id_produk' => 9,
                'nama_varian' => 'Merah 1kg',
                'foto' => 'produk/apel-merah.jpg',
                'harga_modal' => 25000.00,
                'harga_jual' => 35000.00,
                'stok' => 40,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_produk' => 9,
                'nama_varian' => 'Hijau 1kg',
                'foto' => 'produk/apel-hijau.jpg',
                'harga_modal' => 28000.00,
                'harga_jual' => 38000.00,
                'stok' => 35,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Kangkung
            [
                'id_produk' => 10,
                'nama_varian' => 'Segar 500gr',
                'foto' => 'produk/kangkung.jpg',
                'harga_modal' => 3000.00,
                'harga_jual' => 5000.00,
                'stok' => 50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
