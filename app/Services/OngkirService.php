<?php

namespace App\Services;

use App\Models\LayananPengiriman;
use App\Models\AlamatUser;
use Illuminate\Support\Facades\DB;

class OngkirService
{
    public function hitungOngkir($idLayanan, $idAlamatUser)
    {
        try {
            // 1. Ambil Data Layanan & Alamat User
            $layanan = LayananPengiriman::find($idLayanan);
            $alamatUser = AlamatUser::find($idAlamatUser);

            if (!$layanan || !$alamatUser) {
                return ['error' => 'Data layanan atau alamat tidak valid.'];
            }

            // 2. Normalisasi String (Bersihkan spasi & lowercase)
            // Tujuannya agar " Arcamanik " cocok dengan "arcamanik"
            $kecamatanUser = strtolower(trim($alamatUser->kecamatan));
            $kelurahanUser = strtolower(trim($alamatUser->kelurahan));
            $kotaUser = strtolower(trim($alamatUser->kota_kabupaten));

            // 3. QUERY DINAMIS KE TABLE WILAYAH_PENGIRIMAN
            // Prioritas 1: Cari yang Kelurahan & Kecamatan & Kota COCOK (Paling Akurat)
            $wilayah = DB::table('wilayah_pengiriman')
                ->where(DB::raw('LOWER(kelurahan)'), $kelurahanUser)
                ->where(DB::raw('LOWER(kecamatan)'), $kecamatanUser)
                ->where(DB::raw('LOWER(kota_kabupaten)'), $kotaUser)
                ->first();

            // Prioritas 2: Jika tidak ketemu, cari berdasarkan Kecamatan & Kota saja
            if (!$wilayah) {
                $wilayah = DB::table('wilayah_pengiriman')
                    ->where(DB::raw('LOWER(kecamatan)'), $kecamatanUser)
                    ->where(DB::raw('LOWER(kota_kabupaten)'), $kotaUser)
                    ->first();
            }

            // Prioritas 3: Jika tidak ketemu juga, cari berdasarkan Kota saja (Fallback Kasar)
            if (!$wilayah) {
                $wilayah = DB::table('wilayah_pengiriman')
                    ->where(DB::raw('LOWER(kota_kabupaten)'), $kotaUser)
                    ->first();
            }

            // 4. Ambil Jarak
            if ($wilayah) {
                // Pastikan nama kolom di database Anda 'jarak_km' atau 'jarak'
                // Sesuaikan baris ini dengan nama kolom di tabel database Anda
                $jarak = $wilayah->jarak_km ?? $wilayah->jarak ?? 5; 
            } else {
                // Jika Wilayah benar-benar tidak ada di database pengiriman
                // Kita return error agar Admin sadar harus input data, 
                // ATAU beri default jarak jauh (misal 10km)
                // Disini saya set default 5km agar tidak error
                $jarak = 5; 
            }

            // 5. Hitung Tarif
            $tarifPerKm = $layanan->tarif_per_km;
            $totalOngkir = $jarak * $tarifPerKm;

            // FIX: Hapus logic minimum 5000 agar 2km * 2000 tetap jadi 4000
            // if ($totalOngkir < 5000) $totalOngkir = 5000; 

            return [
                'jarak' => (float) $jarak,
                'tarif_per_km' => (int) $tarifPerKm,
                'total_ongkir' => (int) ceil($totalOngkir),
                'total_ongkir_formatted' => 'Rp ' . number_format(ceil($totalOngkir), 0, ',', '.'),
                'error' => null
            ];

        } catch (\Exception $e) {
            return [
                'error' => 'Gagal menghitung ongkir: ' . $e->getMessage()
            ];
        }
    }
}