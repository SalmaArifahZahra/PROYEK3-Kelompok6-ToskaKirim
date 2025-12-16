<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PesananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pesanan')->insert([
            [
                'id_user' => 3, // Budi Santoso
                'id_ongkir' => 1, // 2.5 km
                'waktu_pesanan' => now()->subDays(5),
                'subtotal_produk' => 68000.00,
                'grand_total' => 80500.00, // subtotal + ongkir 12500
                'status_pesanan' => 'selesai',
                'penerima_nama' => 'Budi Santoso',
                'penerima_telepon' => '081234567890',
                'alamat_lengkap' => 'Jl. Senayan Raya No. 12A, RT 005/RW 003, Kelurahan Senayan, Kecamatan Kebayoran Baru, Jakarta Selatan',
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(2),
            ],
            [
                'id_user' => 4, // Siti Nurhaliza
                'id_ongkir' => 2, // 5 km (Bandung)
                'waktu_pesanan' => now()->subDays(3),
                'subtotal_produk' => 105000.00,
                'grand_total' => 130000.00,
                'status_pesanan' => 'dikirim',
                'penerima_nama' => 'Siti Nurhaliza',
                'penerima_telepon' => '082345678901',
                'alamat_lengkap' => 'Jl. Arcamanik Endah No. 12, RT 003/RW 007, Kelurahan Sukamiskin, Kecamatan Arcamanik, Kota Bandung',
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(1),
            ],
            [
                'id_user' => 5, // Ahmad Rizky
                'id_ongkir' => 3, // 7.5 km
                'waktu_pesanan' => now()->subDays(2),
                'subtotal_produk' => 87000.00,
                'grand_total' => 124500.00, // subtotal + ongkir 37500
                'status_pesanan' => 'diproses',
                'penerima_nama' => 'Ahmad Rizky',
                'penerima_telepon' => '083456789012',
                'alamat_lengkap' => 'Jl. BSD Raya No. 45, RT 012/RW 006, Kelurahan Serpong, Kecamatan Serpong, Tangerang',
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subHours(12),
            ],
            [
                'id_user' => 6, // Dewi Lestari
                'id_ongkir' => 4, // 10 km
                'waktu_pesanan' => now()->subDay(),
                'subtotal_produk' => 95000.00,
                'grand_total' => 145000.00, // subtotal + ongkir 50000
                'status_pesanan' => 'menunggu_verifikasi',
                'penerima_nama' => 'Dewi Lestari',
                'penerima_telepon' => '084567890123',
                'alamat_lengkap' => 'Jl. Kranji Raya No. 33, RT 007/RW 002, Kelurahan Kranji, Kecamatan Bekasi Barat, Bekasi',
                'created_at' => now()->subDay(),
                'updated_at' => now()->subHours(6),
            ],
        ]);
    }
}
