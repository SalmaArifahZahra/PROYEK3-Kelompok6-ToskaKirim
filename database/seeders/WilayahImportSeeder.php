<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WilayahPengiriman;
use Illuminate\Support\Facades\DB;

class WilayahImportSeeder extends Seeder
{
    public function run()
    {
        // Setup agar bisa baca file CSV dari berbagai OS (Mac/Windows)
        ini_set('auto_detect_line_endings', true);
        
        $csvFile = database_path('seeders/DataKecKel.csv'); 

        if (!file_exists($csvFile)) {
            $this->command->error("File CSV tidak ditemukan di: $csvFile");
            return;
        }

        $file = fopen($csvFile, 'r');
        $dataMap = [];
        
        // Variabel untuk menyimpan "Header Aktif" saat ini
        $currentHeaderKota = null;
        $currentHeaderKecamatan = null;
        
        $this->command->info("Mulai scanning data...");

        // Loop membaca baris demi baris
        while (($row = fgetcsv($file, 0, ';')) !== false) {
            
            // 1. Cek apakah baris ini kosong melompong? Skip.
            $isiBaris = implode('', $row);
            if (empty(trim($isiBaris))) continue;

            // 2. DETEKSI HEADER BLOK BARU
            // Ambil sel pertama yang ada isinya
            $firstCell = null;
            foreach ($row as $cell) {
                if (!empty(trim($cell))) {
                    $firstCell = trim($cell);
                    break;
                }
            }

            // Jika sel pertama mengandung kata "Kota" atau "Kabupaten", berarti ini HEADER KOTA
            if ($firstCell && (stripos($firstCell, 'Kota') !== false || stripos($firstCell, 'Kabupaten') !== false)) {
                
                $this->command->info("🔹 Blok Baru Ditemukan: $firstCell");
                
                $currentHeaderKota = $row;
                
                // PENTING: Baris setelah Header Kota PASTI Header Kecamatan
                // Kita langsung ambil baris selanjutnya
                $nextRow = fgetcsv($file, 0, ';');
                if ($nextRow) {
                    $currentHeaderKecamatan = $nextRow;
                }
                
                // Lanjut ke iterasi berikutnya (jangan proses ini sebagai data kelurahan)
                continue; 
            }

            // 3. PROSES DATA KELURAHAN
            // Syarat: Kita harus punya Header Kota & Kecamatan yang sedang aktif
            if ($currentHeaderKota && $currentHeaderKecamatan) {
                foreach ($row as $index => $kelurahan) {
                    
                    // Pastikan kolom ini punya header
                    if (!isset($currentHeaderKota[$index]) || !isset($currentHeaderKecamatan[$index])) continue;

                    $namaKota = trim($currentHeaderKota[$index]);
                    $namaKecamatan = trim($currentHeaderKecamatan[$index]);
                    $namaKelurahan = trim($kelurahan);

                    // Skip data kosong atau sampah
                    if (empty($namaKelurahan) || empty($namaKecamatan) || empty($namaKota)) continue;
                    
                    // Skip jika sisa-sisa header (Unnamed)
                    if (str_contains($namaKota, 'Unnamed')) continue;

                    $dataMap[] = [
                        'kota_kabupaten' => $namaKota,
                        'kecamatan' => $namaKecamatan,
                        'kelurahan' => $namaKelurahan,
                        'jarak_km' => 0, // Default 0
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }
        fclose($file);

        // 4. Masukkan ke Database
        if (count($dataMap) > 0) {
            // Hapus data lama biar bersih
            WilayahPengiriman::truncate();
            
            // Insert massal per 100 data
            $barisMasuk = 0;
            foreach (array_chunk($dataMap, 100) as $chunk) {
                WilayahPengiriman::insert($chunk);
                $barisMasuk += count($chunk);
            }
            $this->command->info("🎉 SUKSES TOTAL! Berhasil import $barisMasuk wilayah.");
        } else {
            $this->command->error("❌ Gagal. Tidak ada data yang terbaca. Cek delimiter CSV.");
        }
    }
}