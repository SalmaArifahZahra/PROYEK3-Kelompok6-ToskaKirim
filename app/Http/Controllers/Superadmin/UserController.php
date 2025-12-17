<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WilayahPengiriman;
use App\Models\Pengaturan;
use App\Models\Pesanan;
use App\Models\MetodePembayaran;
use App\Models\LayananPengiriman;
use App\Models\Kategori;
use App\Models\Produk;
use Illuminate\Support\Facades\Schema;

class UserController extends Controller
{
    public function dashboard()
    {
        // --- BAGIAN 1: STATISTIK UTAMA (METRIK BISNIS) ---
        $totalAdmin = User::where('peran', 'admin')->count();
        $totalCustomer = User::where('peran', 'customer')->count();
        
        // Hitung Pendapatan (Safe Mode)
        $totalPendapatan = 0;
        if (Schema::hasTable('pesanan')) {
            try {
                $totalPendapatan = Pesanan::where('status_pesanan', 'selesai')->sum('grand_total'); 
            } catch (\Exception $e) { $totalPendapatan = 0; }
        }

        // Hitung Logistik (Cakupan Wilayah)
        $totalWilayah = WilayahPengiriman::count();
        $wilayahTerisi = WilayahPengiriman::where('jarak_km', '>', 0)->count();
        $persenLogistik = $totalWilayah > 0 ? round(($wilayahTerisi / $totalWilayah) * 100, 1) : 0;


        // --- BAGIAN 2: CHECKLIST KESIAPAN SISTEM (DARI VERSI 1) ---
        $checklist = [
            [
                'title' => 'Identitas Toko',
                'desc' => 'WA & Alamat Toko',
                'count' => Pengaturan::whereIn('key', ['nomor_wa', 'alamat_toko'])->count() >= 2 ? 1 : 0,
                'required' => true,
                'url' => route('superadmin.kontrol_toko.index'),
                'icon' => 'fa-store',
                'color' => 'teal'
            ],
            [
                'title' => 'Metode Pembayaran',
                'desc' => 'Bank / E-Wallet',
                'count' => MetodePembayaran::count(),
                'required' => true,
                'url' => route('superadmin.payments.index'),
                'icon' => 'fa-credit-card',
                'color' => 'blue'
            ],
            [
                'title' => 'Layanan Kirim',
                'desc' => 'Reguler / Express',
                'count' => LayananPengiriman::count(),
                'required' => true,
                'url' => route('superadmin.layanan.index'),
                'icon' => 'fa-truck',
                'color' => 'indigo'
            ],
            [
                'title' => 'Kategori Produk',
                'desc' => 'Kategori Utama',
                'count' => Kategori::count(),
                'required' => true,
                'url' => route('admin.kategori.index'), 
                'icon' => 'fa-tags',
                'color' => 'orange'
            ],
            [
                'title' => 'Database Wilayah',
                'desc' => 'Jarak Terhitung',
                'count' => $wilayahTerisi, 
                'target' => $totalWilayah, 
                'required' => true,
                'url' => route('superadmin.wilayah.index'),
                'icon' => 'fa-map-location-dot',
                'color' => 'rose'
            ]
        ];

        // Hitung Progress Checklist
        $completedTasks = 0;
        $totalTasks = count($checklist);
        foreach($checklist as $item) {
            if($item['count'] > 0) $completedTasks++;
        }
        $progressPersen = ($totalTasks > 0) ? round(($completedTasks / $totalTasks) * 100) : 0;


        // --- BAGIAN 3: TIM TERBARU ---
        $latestAdmins = User::where('peran', 'admin')->latest()->take(5)->get();

        return view('superadmin.dashboard', compact(
            'totalAdmin', 'totalCustomer', 'totalPendapatan', 'persenLogistik',
            'checklist', 'completedTasks', 'totalTasks', 'progressPersen',
            'latestAdmins'
        ));
    }
}