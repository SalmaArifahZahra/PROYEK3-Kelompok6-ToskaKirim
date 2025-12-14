<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Enums\RoleEnum;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PelangganController extends Controller
{
    // Menampilkan daftar pelanggan yang pernah berbelanja
    public function index(Request $request): View
    {
        $query = User::where('peran', RoleEnum::CUSTOMER)
                     ->withCount('pesanan')
                     ->with(['pesanan' => function($q) {
                         $q->latest()->limit(1);
                     }]);

        // Filter pencarian
        $search = $request->get('search');
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama', 'ILIKE', "%{$search}%")
                  ->orWhere('email', 'ILIKE', "%{$search}%");
            });
        }

        // Filter sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        $allowedSorts = ['nama', 'email', 'created_at', 'pesanan_count'];
        if (in_array($sortBy, $allowedSorts)) {
            if ($sortBy === 'pesanan_count') {
                $query->orderBy('pesanan_count', $sortOrder);
            } else {
                $query->orderBy($sortBy, $sortOrder);
            }
        }

        $pelangganList = $query->paginate(15);

        return view('admin.pelanggan.index', [
            'pelangganList' => $pelangganList,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder
        ]);
    }
}
