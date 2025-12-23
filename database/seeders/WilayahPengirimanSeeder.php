<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WilayahPengirimanSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('csv/wilayah_pengiriman.csv');

        if (!file_exists($path)) {
            $this->command->error("File CSV tidak ditemukan di: $path");
            return;
        }

        // 1. DETEKSI OTOMATIS DELIMITER 
        $handle = fopen($path, 'r');
        $firstLine = fgets($handle); 
        
        $delimiter = strpos($firstLine, ';') !== false ? ';' : ',';

        rewind($handle); 
        
        fgetcsv($handle, 0, $delimiter); 

        $this->command->info("Terdeteksi format CSV menggunakan pemisah: '$delimiter'");

        $chunkSize = 1000; 
        $data = [];

        while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
            
            // Safety Check: Pastikan kolom cukup
            if (count($row) < 4) continue;

            // 2. BERSIHKAN ANGKA JARAK (Auto-Convert)
            $jarakRaw = $row[4] ?? 0;
            $jarakClean = str_replace(',', '.', $jarakRaw); // Ubah koma jadi titik

            $data[] = [
                'id' => $row[0], 
                'kota_kabupaten' => $row[1],
                'kecamatan' => $row[2],
                'kelurahan' => $row[3],
                'jarak_km' => (float) $jarakClean, 
                'created_at' => now(), 
                'updated_at' => now(),
            ];

            if (count($data) >= $chunkSize) {
                DB::table('wilayah_pengiriman')->insertOrIgnore($data);
                $data = [];
            }
        }

        if (!empty($data)) {
            DB::table('wilayah_pengiriman')->insertOrIgnore($data);
        }

        fclose($handle);
        $this->command->info('Sukses! Data wilayah berhasil diimport.');
    }
}