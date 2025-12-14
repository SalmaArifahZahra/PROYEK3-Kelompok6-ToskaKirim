<?php

namespace App\Services;

use App\Models\WilayahPengiriman;
use App\Models\LayananPengiriman;
use App\Models\AlamatUser;

class OngkirService
{
    /**
     * Hitung ongkir berdasarkan alamat dan layanan pengiriman
     * 
     * @param int $idLayananPengiriman - ID dari layanan_pengiriman
     * @param int $idAlamat - ID dari alamat_user
     * @return array ['jarak' => float, 'tarif_per_km' => int, 'total_ongkir' => int]
     */
    public function hitungOngkir($idLayananPengiriman, $idAlamat)
    {
        // 1. Ambil data alamat
        $alamat = AlamatUser::find($idAlamat);
        if (!$alamat) {
            return [
                'jarak' => 0,
                'tarif_per_km' => 0,
                'total_ongkir' => 0,
                'error' => 'Alamat tidak ditemukan'
            ];
        }

        // 2. Cari wilayah berdasarkan kelurahan + kecamatan + kota_kabupaten
        $wilayah = WilayahPengiriman::where('kelurahan', $alamat->kelurahan)
            ->where('kecamatan', $alamat->kecamatan)
            ->where('kota_kabupaten', $alamat->kota_kabupaten)
            ->first();

        if (!$wilayah) {
            return [
                'jarak' => 0,
                'tarif_per_km' => 0,
                'total_ongkir' => 0,
                'error' => "Wilayah {$alamat->kelurahan}, {$alamat->kecamatan}, {$alamat->kota_kabupaten} tidak ditemukan"
            ];
        }

        // 3. Ambil data layanan pengiriman
        $layanan = LayananPengiriman::find($idLayananPengiriman);
        if (!$layanan) {
            return [
                'jarak' => 0,
                'tarif_per_km' => 0,
                'total_ongkir' => 0,
                'error' => 'Layanan pengiriman tidak ditemukan'
            ];
        }

        // 4. Hitung total ongkir
        $jarak = (float) $wilayah->jarak_km;
        $tarifPerKm = (int) $layanan->tarif_per_km;
        $totalOngkir = (int) ($jarak * $tarifPerKm);

        return [
            'jarak' => $jarak,
            'tarif_per_km' => $tarifPerKm,
            'total_ongkir' => $totalOngkir,
            'error' => null
        ];
    }

    /**
     * Ambil jarak hanya berdasarkan alamat
     */
    public function getJarak($idAlamat)
    {
        $alamat = AlamatUser::find($idAlamat);
        if (!$alamat) {
            return null;
        }

        $wilayah = WilayahPengiriman::where('kelurahan', $alamat->kelurahan)
            ->where('kecamatan', $alamat->kecamatan)
            ->where('kota_kabupaten', $alamat->kota_kabupaten)
            ->first();

        return $wilayah ? $wilayah->jarak_km : null;
    }
}
