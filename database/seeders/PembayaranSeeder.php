<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PembayaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pembayaran')->insert([
            [
                'id_pesanan' => 1,
                'bukti_bayar' => 'pembayaran/bukti-pesanan-1.jpg',
                'jumlah_bayar' => 80500.00,
                'tanggal_bayar' => now()->subDays(5)->toDateString(),
                'status_pembayaran' => 'diterima',
                'catatan_admin' => 'Pembayaran sudah sesuai dan terverifikasi',
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(4),
            ],
            [
                'id_pesanan' => 2,
                'bukti_bayar' => 'pembayaran/bukti-pesanan-2.jpg',
                'jumlah_bayar' => 130000.00,
                'tanggal_bayar' => now()->subDays(3)->toDateString(),
                'status_pembayaran' => 'diterima',
                'catatan_admin' => 'Pembayaran diterima, pesanan sedang dikirim',
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(2),
            ],
            [
                'id_pesanan' => 3,
                'bukti_bayar' => 'pembayaran/bukti-pesanan-3.jpg',
                'jumlah_bayar' => 124500.00,
                'tanggal_bayar' => now()->subDays(2)->toDateString(),
                'status_pembayaran' => 'diterima',
                'catatan_admin' => 'Pembayaran terverifikasi, pesanan sedang diproses',
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDay(),
            ],
            [
                'id_pesanan' => 4,
                'bukti_bayar' => 'pembayaran/bukti-pesanan-4.jpg',
                'jumlah_bayar' => 145000.00,
                'tanggal_bayar' => now()->subDay()->toDateString(),
                'status_pembayaran' => 'menunggu_verifikasi',
                'catatan_admin' => null,
                'created_at' => now()->subDay(),
                'updated_at' => now()->subDay(),
            ],
        ]);
    }
}
