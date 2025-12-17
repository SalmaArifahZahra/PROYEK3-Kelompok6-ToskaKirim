<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MetodePembayaran;
use App\Models\LayananPengiriman;
use App\Models\Kategori;
use App\Models\Produk;
use App\Models\Pengaturan;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    // Menampilkan halaman dashboard admin.
    public function index(): View
    {
        // Hitung data penting untuk operasional toko
        $totalMetodePembayaran = MetodePembayaran::count();
        $totalLayananPengiriman = LayananPengiriman::count();
        $totalKategori = Kategori::whereNull('parent_id')->count();
        $totalProduk = Produk::count();
        $totalPengaturan = Pengaturan::count();
        
        // Checklist data penting untuk operasional toko
        $checklist = [
            [
                'title' => 'Metode Pembayaran',
                'description' => 'Minimal 1 metode pembayaran diperlukan agar customer dapat checkout',
                'count' => $totalMetodePembayaran,
                'icon' => 'fa-credit-card',
                'url' => '#',
                'required' => true,
                'managed_by' => 'superadmin'
            ],
            [
                'title' => 'Layanan Pengiriman',
                'description' => 'Minimal 1 layanan pengiriman diperlukan agar customer dapat checkout',
                'count' => $totalLayananPengiriman,
                'icon' => 'fa-truck',
                'url' => route('superadmin.layanan.index'),
                'required' => true,
                'managed_by' => 'admin'
            ],
            [
                'title' => 'Kategori Produk',
                'description' => 'Minimal 1 kategori diperlukan untuk mengorganisir produk',
                'count' => $totalKategori,
                'icon' => 'fa-tags',
                'url' => route('admin.kategori.index'),
                'required' => true,
                'managed_by' => 'admin'
            ],
            [
                'title' => 'Produk',
                'description' => 'Minimal 1 produk dengan varian diperlukan agar customer dapat berbelanja',
                'count' => $totalProduk,
                'icon' => 'fa-box',
                'url' => route('admin.produk.selectKategori'),
                'required' => true,
                'managed_by' => 'admin'
            ],
            [
                'title' => 'Pengaturan Toko',
                'description' => 'Informasi toko seperti nama, alamat, kontak untuk ditampilkan ke customer',
                'count' => $totalPengaturan,
                'icon' => 'fa-store',
                'url' => '#',
                'required' => false,
                'managed_by' => 'superadmin'
            ],
        ];
        
        // Hitung total required dan yang sudah complete
        $totalRequired = collect($checklist)->where('required', true)->count();
        $completedCount = collect($checklist)->where('required', true)->filter(function($item) {
            return $item['count'] > 0;
        })->count();
        
        return view('admin.dashboard', [
            'checklist' => $checklist,
            'totalRequired' => $totalRequired,
            'completedCount' => $completedCount,
            'totalMetodePembayaran' => $totalMetodePembayaran,
            'totalLayananPengiriman' => $totalLayananPengiriman,
            'totalKategori' => $totalKategori,
            'totalProduk' => $totalProduk,
        ]);
    }
}