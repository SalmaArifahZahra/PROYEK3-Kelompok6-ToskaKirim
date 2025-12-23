<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\WilayahPengiriman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class WilayahPengirimanController extends Controller
{
    // --- 1. INDEX & PENCARIAN ---
    public function index(Request $request)
    {
        $query = WilayahPengiriman::orderBy('kota_kabupaten')
                    ->orderBy('kecamatan')
                    ->orderBy('kelurahan');

        // Pencarian Smart
        $search = $request->get('search', '');
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('kelurahan', 'ILIKE', "%{$search}%")
                  ->orWhere('kecamatan', 'ILIKE', "%{$search}%")
                  ->orWhere('kota_kabupaten', 'ILIKE', "%{$search}%");
            });
        }

        $wilayah = $query->paginate(50)->appends($request->query());
        return view('superadmin.wilayah.index', compact('wilayah', 'search'));
    }

    // --- 2. HITUNG JARAK OTOMATIS (YANG HILANG TADI) ---
    public function hitungJarakOtomatis(Request $request)
    {
        // Setup agar tidak timeout
        set_time_limit(300);
        ini_set('memory_limit', '512M');

        // === KOORDINAT TOKO (GANTI DENGAN TITIK ASLI TOKOMU) ===
        $tokoLat = -6.917464; 
        $tokoLon = 107.619125; 
        // =======================================================

        // Ambil 20 data yang jaraknya masih 0 atau sangat kecil
        $wilayah = WilayahPengiriman::where('jarak_km', '<', 0.1)->take(20)->get();
        $sisaAntrian = WilayahPengiriman::where('jarak_km', '<', 0.1)->count();

        if ($wilayah->isEmpty()) {
            return back()->with('success', 'Semua wilayah sudah memiliki data jarak!');
        }

        $updated = 0;
        $client = new \GuzzleHttp\Client(['verify' => false, 'timeout' => 10]); 

        foreach ($wilayah as $w) {
            $kel = trim($w->kelurahan);
            $kec = trim($w->kecamatan);
            $kot = trim($w->kota_kabupaten);

            // Strategi Pencarian Berlapis
            $queries = [
                "$kel, $kec, $kot, Indonesia", // Prioritas 1: Lengkap
                "$kel, $kot, Indonesia",       // Prioritas 2: Tanpa Kecamatan
                "$kec, $kot, Indonesia",       // Prioritas 3: Kecamatan Saja
            ];

            $found = false;

            foreach ($queries as $q) {
                try {
                    $response = $client->get('https://nominatim.openstreetmap.org/search', [
                        'headers' => ['User-Agent' => 'ToskaKirimApp/1.0'],
                        'query' => ['q' => $q, 'format' => 'json', 'limit' => 1]
                    ]);
                    
                    $data = json_decode($response->getBody(), true);

                    if (!empty($data)) {
                        $destLat = $data[0]['lat'];
                        $destLon = $data[0]['lon'];

                        // Hitung Haversine
                        $jarakKm = $this->hitungJarakHaversine($tokoLat, $tokoLon, $destLat, $destLon);
                        $jarakRute = $jarakKm * 1.3; // Faktor koreksi jalan

                        $w->update(['jarak_km' => round($jarakRute, 3)]);
                        $updated++;
                        $found = true;
                        
                        sleep(1); // Jeda sopan ke API
                        break; 
                    }
                } catch (\Exception $e) { continue; }
            }
        }

        return back()->with('success', "Berhasil update $updated wilayah! Sisa antrian: " . ($sisaAntrian - 20));
    }

    // --- 3. IMPORT CSV (SMART DETECT) ---
    public function import(Request $request)
    {
        $request->validate(['file_csv' => 'required|mimes:csv,txt']);
        
        $file = $request->file('file_csv');
        $path = $file->getRealPath();

        $handle = fopen($path, 'r');
        $firstLine = fgets($handle);
        $delimiter = strpos($firstLine, ';') !== false ? ';' : ',';
        rewind($handle);
        fgetcsv($handle, 0, $delimiter); // Skip Header

        $inserted = 0;
        DB::beginTransaction();
        try {
            while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
                if (count($row) < 4) continue;
                $jarakRaw = $row[4] ?? 0;
                $jarakClean = str_replace(',', '.', $jarakRaw);

                WilayahPengiriman::updateOrCreate(
                    [
                        'kota_kabupaten' => $row[1],
                        'kecamatan'      => $row[2],
                        'kelurahan'      => $row[3],
                    ],
                    ['jarak_km' => (float) $jarakClean]
                );
                $inserted++;
            }
            DB::commit();
            fclose($handle);
            return back()->with('success', "$inserted data berhasil diimport!");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }

    // --- 4. EXPORT CSV ---
   public function export()
    {
        $fileName = 'wilayah_pengiriman_' . date('Y-m-d_H-i') . '.csv';
        
        $wilayahs = WilayahPengiriman::orderBy('kota_kabupaten')
                                     ->orderBy('kecamatan')
                                     ->orderBy('kelurahan')
                                     ->get();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($wilayahs) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Kota/Kabupaten', 'Kecamatan', 'Kelurahan', 'Jarak (KM)']);

            foreach ($wilayahs as $row) {
                fputcsv($file, [
                    $row->id, 
                    $row->kota_kabupaten, 
                    $row->kecamatan, 
                    $row->kelurahan, 
                    str_replace('.', ',', $row->jarak_km)
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // --- 5. CRUD STANDARD ---
    public function create() { return view('superadmin.wilayah.create'); }

    public function store(Request $request) {
        $request->validate([
            'kota_kabupaten' => 'required',
            'kecamatan' => 'required',
            'kelurahan' => 'required',
            'jarak_km' => 'required|numeric|min:0',
        ]);
        WilayahPengiriman::create($request->all());
        return redirect()->route('superadmin.wilayah.index')->with('success', 'Data berhasil ditambahkan!');
    }

    public function edit($id) {
        $wilayah = WilayahPengiriman::findOrFail($id);
        return view('superadmin.wilayah.edit', compact('wilayah'));
    }

    public function update(Request $request, $id) {
        $request->validate([
            'kota_kabupaten' => 'required',
            'kecamatan' => 'required',
            'kelurahan' => 'required',
            'jarak_km' => 'required|numeric|min:0',
        ]);
        WilayahPengiriman::findOrFail($id)->update($request->all());
        return redirect()->route('superadmin.wilayah.index')->with('success', 'Data berhasil diperbarui!');
    }

    public function destroy($id)
    {
        try {
            $wilayah = WilayahPengiriman::findOrFail($id);
            $wilayah->delete();

            return redirect()->route('superadmin.wilayah.index')
                             ->with('success', 'Data wilayah berhasil dihapus!');
                             
        } catch (\Exception $e) {
            // Jika error (misal data sedang dipakai di tabel pesanan), jangan crash!
            // Kembalikan ke halaman sebelumnya dengan pesan error merah.
            return back()->with('error', 'Gagal menghapus: Data ini mungkin sedang digunakan oleh sistem.');
        }
    }

    // --- 6. RUMUS PRIVATE ---
    private function hitungJarakHaversine($lat1, $lon1, $lat2, $lon2) {
        $earthRadius = 6371; 
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2) * sin($dLon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        return $earthRadius * $c;
    }
}