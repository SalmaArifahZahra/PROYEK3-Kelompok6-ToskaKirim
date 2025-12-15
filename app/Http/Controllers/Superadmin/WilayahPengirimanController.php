<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\WilayahPengiriman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; // Library HTTP Client

class WilayahPengirimanController extends Controller
{
    // Menampilkan daftar wilayah
    public function index(Request $request) {
        $query = WilayahPengiriman::orderBy('kota_kabupaten')
                    ->orderBy('kecamatan')
                    ->orderBy('kelurahan');
        
        // Search parameter
        $search = $request->get('search', '');
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('kelurahan', 'ILIKE', "%{$search}%")
                  ->orWhere('kecamatan', 'ILIKE', "%{$search}%")
                  ->orWhere('kota_kabupaten', 'ILIKE', "%{$search}%");
            });
        }
        
        $wilayah = $query->paginate(50);
        $wilayah->appends($request->query());
        
        return view('superadmin.wilayah.index', [
            'wilayah' => $wilayah,
            'search' => $search
        ]);
    }

    // Update manual jarak (jika diedit lewat tabel)
    public function update(Request $request, $id) {
        $wilayah = WilayahPengiriman::findOrFail($id);
        $wilayah->update(['jarak_km' => $request->jarak_km]);
        return back()->with('success', 'Jarak berhasil diupdate manual');
    }

    // Hitung jarak otomatis menggunakan OpenStreetMap API
   public function hitungJarakOtomatis(Request $request)
    {
        set_time_limit(300); // 5 Menit maks
        ini_set('memory_limit', '512M');

        $wilayah = WilayahPengiriman::where('jarak_km', '<', 0.1)->take(20)->get();
        $sisaAntrian = WilayahPengiriman::where('jarak_km', '<', 0.1)->count();

        if ($wilayah->isEmpty()) {
            return back()->with('success', 'Semua data jarak sudah terisi!');
        }

        // Koordinat Toko
        $tokoLat = -6.9205437;
        $tokoLon = 107.6720667;

        $updated = 0;
        $logs = []; // Simpan log apa yang terjadi
        
        // Client HTTP
        $client = new \GuzzleHttp\Client(['verify' => false, 'timeout' => 10]); 

        foreach ($wilayah as $w) {
            
            $kel = trim($w->kelurahan);
            $kec = trim($w->kecamatan);
            $kot = trim($w->kota_kabupaten);

            $queries = [
                // Paling Akurat
                "$kel, $kec, $kot",
                // Coba tanpa Kecamatan (Sering berhasil di sini)
                "$kel, $kot",
                // Coba Kecamatan saja (Fallback biar gak 0)
                "$kec, $kot",
                // Coba Kota saja (Darurat terakhir)
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
                        $destLat = $data[0]['lat'];
                        $destLon = $data[0]['lon'];

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

    // Fungsi hitung jarak Haversine
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