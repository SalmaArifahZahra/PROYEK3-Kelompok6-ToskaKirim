<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'nama' => 'SuperAdmin',
            'email' => 'super@gmail.com',
            'password' => Hash::make('super123'),
            'peran' => 'superadmin',
        ]);

        User::create([
            'nama' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
            'peran' => 'admin',
        ]);

        User::create([
            'nama' => 'Customer',
            'email' => 'customer@gmail.com',
            'password' => Hash::make('customer123'),
            'peran' => 'customer',
        ]);
    }
}
