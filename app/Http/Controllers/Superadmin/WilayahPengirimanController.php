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
        // 1. SETTING SUPER
        set_time_limit(300); // 5 Menit maks
        ini_set('memory_limit', '512M');

        // 2. AMBIL 20 DATA (Batch)
        $wilayah = WilayahPengiriman::where('jarak_km', '<', 0.1)->take(20)->get();
        $sisaAntrian = WilayahPengiriman::where('jarak_km', '<', 0.1)->count();

        if ($wilayah->isEmpty()) {
            return back()->with('success', 'Semua data jarak sudah terisi!');
        }

        // Koordinat Toko (Pastikan Akurat)
        $tokoLat = -6.9205437;
        $tokoLon = 107.6720667;

        $updated = 0;
        $logs = []; // Simpan log apa yang terjadi
        
        // Client HTTP
        $client = new \GuzzleHttp\Client(['verify' => false, 'timeout' => 10]); 

        foreach ($wilayah as $w) {
            
            // Bersihkan teks dari spasi berlebih
            $kel = trim($w->kelurahan);
            $kec = trim($w->kecamatan);
            $kot = trim($w->kota_kabupaten);

            // --- STRATEGI BERLAPIS (PRIORITAS PENCARIAN) ---
            $queries = [
                // 1. Paling Akurat
                "$kel, $kec, $kot",
                // 2. Coba tanpa Kecamatan (Sering berhasil di sini)
                "$kel, $kot",
                // 3. Coba Kecamatan saja (Fallback biar gak 0)
                "$kec, $kot",
                // 4. Coba Kota saja (Darurat terakhir)
                "$kot"
            ];

            $found = false;

            foreach ($queries as $q) {
                try {
                    $response = $client->get('https://nominatim.openstreetmap.org/search', [
                        'headers' => ['User-Agent' => 'ToskaKirimApp/1.0'],
                        'query' => [
                            'q' => $q,
                            'format' => 'json',
                            'limit' => 1
                        ]
                    ]);
                    
                    $data = json_decode($response->getBody(), true);

                    if (!empty($data)) {
                        // KETEMU!
                        $destLat = $data[0]['lat'];
                        $destLon = $data[0]['lon'];

                        // Hitung
                        $jarakKm = $this->hitungJarakHaversine($tokoLat, $tokoLon, $destLat, $destLon);
                        $jarakRute = $jarakKm * 1.3; 

                        // Update & Break (Keluar dari loop pencarian)
                        $w->update(['jarak_km' => round($jarakRute, 3)]);
                        $updated++;
                        $found = true;
                        
                        // Jeda 1 detik (Wajib, aturan OpenStreetMap)
                        sleep(1);
                        break; 
                    }

                } catch (\Exception $e) {
                    // Lanjut ke query berikutnya
                    continue;
                }
            }

            if (!$found) {
                // Catat nama wilayah yang bandel banget gak ketemu
                $logs[] = "$kel ($kec)";
            }
        }

        // Susun Pesan
        $msg = "Batch Selesai! Sukses Update: $updated data. Sisa Antrian: " . ($sisaAntrian - 20);
        
        if (!empty($logs)) {
            $msg .= ". Gagal menemukan: " . implode(', ', array_slice($logs, 0, 3));
        }

        return back()->with('success', $msg);
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