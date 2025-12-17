<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ongkir;
use App\Models\Pembayaran;
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
        $totalOngkir = Ongkir::count();
        $totalPembayaran = Pembayaran::count();
        $totalKategori = Kategori::whereNull('parent_id')->count();
        $totalProduk = Produk::count();
        $totalPengaturan = Pengaturan::count();
        
        // Checklist data penting
        $checklist = [
            [
                'title' => 'Layanan Pengiriman',
                'description' => 'Minimal 1 metode pengiriman agar customer dapat checkout',
                'count' => $totalOngkir,
                'icon' => 'fa-truck',
                'url' => route('admin.dashboard'), // Superadmin yang atur
                'required' => true,
                'managed_by' => 'superadmin'
            ],
            [
                'title' => 'Metode Pembayaran',
                'description' => 'Minimal 1 metode pembayaran agar customer dapat checkout',
                'count' => $totalPembayaran,
                'icon' => 'fa-credit-card',
                'url' => route('admin.dashboard'), // Superadmin yang atur
                'required' => true,
                'managed_by' => 'superadmin'
            ],
            [
                'title' => 'Kategori Produk',
                'description' => 'Minimal 1 kategori untuk mengorganisir produk',
                'count' => $totalKategori,
                'icon' => 'fa-tags',
                'url' => route('admin.kategori.index'),
                'required' => true,
                'managed_by' => 'admin'
            ],
            [
                'title' => 'Produk',
                'description' => 'Minimal 1 produk dengan varian agar customer dapat berbelanja',
                'count' => $totalProduk,
                'icon' => 'fa-box',
                'url' => route('admin.produk.selectKategori'),
                'required' => true,
                'managed_by' => 'admin'
            ],
            [
                'title' => 'Pengaturan Toko',
                'description' => 'Informasi toko untuk ditampilkan ke customer',
                'count' => $totalPengaturan,
                'icon' => 'fa-store',
                'url' => route('admin.dashboard'), // Superadmin yang atur
                'required' => true,
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
            'totalOngkir' => $totalOngkir,
            'totalPembayaran' => $totalPembayaran,
            'totalKategori' => $totalKategori,
            'totalProduk' => $totalProduk,
        ]);
    }
}