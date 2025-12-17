<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /*
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'nama' => 'Super Admin',
                'email' => 'superadmin@toskakirim.com',
                'password' => Hash::make('password123'),
                'peran' => 'superadmin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Admin ToskaKirim',
                'email' => 'admin@toskakirim.com',
                'password' => Hash::make('password123'),
                'peran' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Budi Santoso',
                'email' => 'budi@gmail.com',
                'password' => Hash::make('password123'),
                'peran' => 'customer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Siti Nurhaliza',
                'email' => 'siti@gmail.com',
                'password' => Hash::make('password123'),
                'peran' => 'customer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Ahmad Rizky',
                'email' => 'ahmad@gmail.com',
                'password' => Hash::make('password123'),
                'peran' => 'customer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Dewi Lestari',
                'email' => 'dewi@gmail.com',
                'password' => Hash::make('password123'),
                'peran' => 'customer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
