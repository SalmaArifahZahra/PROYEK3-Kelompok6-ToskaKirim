<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WilayahPengirimanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $path = database_path('csv/wilayah_pengiriman.csv');

        if (!file_exists($path)) {
            $this->command->error("File CSV tidak ditemukan di: $path");
            $this->command->info("Pastikan kamu sudah membuat folder 'database/csv' dan menaruh file di sana.");
            return;
        }

        $handle = fopen($path, 'r');
        
        fgetcsv($handle); 

        $chunkSize = 1000; 
        $data = [];

        $this->command->info('Mulai membaca CSV...');

        while (($row = fgetcsv($handle)) !== false) {
            $data[] = [
                'id' => $row[0], 
                'kota_kabupaten' => $row[1],
                'kecamatan' => $row[2],
                'kelurahan' => $row[3],
                'jarak_km' => isset($row[4]) && $row[4] !== '' ? (float) $row[4] : 0, 
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