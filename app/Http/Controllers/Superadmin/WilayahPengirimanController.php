<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\WilayahPengiriman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; // Library HTTP Client

class WilayahPengirimanController extends Controller
{
    // Menampilkan daftar wilayah
    public function index() {
        $wilayah = WilayahPengiriman::orderBy('kota_kabupaten')
                    ->orderBy('kecamatan')
                    ->orderBy('kelurahan')
                    ->paginate(50);
                    
        return view('superadmin.wilayah.index', compact('wilayah'));
    }

    // Update manual jarak (jika diedit lewat tabel)
    public function update(Request $request, $id) {
        $wilayah = WilayahPengiriman::findOrFail($id);
        $wilayah->update(['jarak_km' => $request->jarak_km]);
        return back()->with('success', 'Jarak berhasil diupdate manual');
    }

    // --- FITUR BARU: HITUNG OTOMATIS (GRATIS) ---
    public function hitungJarakOtomatis(Request $request)
    {
        // 1. Ambil 5 wilayah yang jaraknya masih 0
        // (Kita batasi 5 agar server OpenStreetMap tidak memblokir kita karena spam)
        $wilayah = WilayahPengiriman::where('jarak_km', 0)->take(5)->get();

        if ($wilayah->isEmpty()) {
            return back()->with('success', 'Semua wilayah sudah memiliki data jarak!');
        }

        // 2. Setting Koordinat Toko 
        $tokoLat = -6.9205437;
        $tokoLon = 107.6720667;

        $updated = 0;
        $client = new \GuzzleHttp\Client(); // Library request bawaan Laravel

        foreach ($wilayah as $w) {
            // Format pencarian: "Kelurahan, Kecamatan, Kota"
            $query = "{$w->kelurahan}, {$w->kecamatan}, {$w->kota_kabupaten}";

            try {
                // Request ke API Nominatim (Gratis)
                $response = $client->get('https://nominatim.openstreetmap.org/search', [
                    'headers' => [
                        'User-Agent' => 'ToskaKirimApp/1.0 (admin@toskakirim.com)' // Wajib isi User-Agent
                    ],
                    'query' => [
                        'q' => $query,
                        'format' => 'json',
                        'limit' => 1
                    ]
                ]);

                $data = json_decode($response->getBody(), true);

                if (!empty($data)) {
                    $destLat = $data[0]['lat'];
                    $destLon = $data[0]['lon'];

                    // Hitung Jarak Garis Lurus (Rumus Haversine)
                    $jarakKm = $this->hitungJarakHaversine($tokoLat, $tokoLon, $destLat, $destLon);
                    
                    // Faktor Koreksi: Jalan raya biasanya 1.3x lebih jauh dari garis lurus
                    $jarakRuteEstimasi = $jarakKm * 1.3; 

                    // Simpan ke database (3 angka belakang koma)
                    $w->update(['jarak_km' => round($jarakRuteEstimasi, 3)]);
                    $updated++;
                    
                    // Jeda 1 detik (sopan santun ke server gratisan)
                    sleep(1); 
                }
            } catch (\Exception $e) {
                // Jika gagal, lanjut ke wilayah berikutnya
                continue; 
            }
        }

        return back()->with('success', "Berhasil menghitung jarak untuk $updated wilayah secara otomatis!");
    }

    // --- RUMUS MATEMATIKA (Private) ---
    private function hitungJarakHaversine($lat1, $lon1, $lat2, $lon2) {
        $earthRadius = 6371; // Radius bumi dalam KM

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat/2) * sin($dLat/2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon/2) * sin($dLon/2);

        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        $distance = $earthRadius * $c;

        return $distance;
    }
}