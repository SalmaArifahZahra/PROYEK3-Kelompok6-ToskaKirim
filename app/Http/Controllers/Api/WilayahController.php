<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WilayahController extends Controller
{
    protected $baseUrl = 'https://ibnux.github.io/data-indonesia';

    public function getProvinces()
    {
        try {
            $response = Http::get("{$this->baseUrl}/provinsi.json");
            
            if (!$response->successful()) {
                throw new \Exception('API request failed with status ' . $response->status());
            }
            
            $provinces = $response->json();
            
            $data = collect($provinces)->map(function ($item) {
                return [
                    'id' => $item['id'],
                    'name' => $item['nama']
                ];
            })->values()->all();
            
            return response()->json(['data' => $data]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal mengambil data provinsi',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getRegencies($provinceId)
    {
        try {
            $response = Http::get("{$this->baseUrl}/kabupaten/{$provinceId}.json");
            
            if (!$response->successful()) {
                throw new \Exception('API request failed with status ' . $response->status());
            }
            
            $regencies = $response->json();
            
            $data = collect($regencies)->map(function ($item) {
                return [
                    'id' => $item['id'],
                    'name' => $item['nama']
                ];
            })->values()->all();
            
            return response()->json(['data' => $data]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal mengambil data kota/kabupaten',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getDistricts($regencyId)
    {
        try {
            $response = Http::get("{$this->baseUrl}/kecamatan/{$regencyId}.json");
            
            if (!$response->successful()) {
                throw new \Exception('API request failed with status ' . $response->status());
            }
            
            $districts = $response->json();
            
            $data = collect($districts)->map(function ($item) {
                return [
                    'id' => $item['id'],
                    'name' => $item['nama']
                ];
            })->values()->all();
            
            return response()->json(['data' => $data]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal mengambil data kecamatan',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getVillages($districtId)
    {
        try {
            $response = Http::get("{$this->baseUrl}/kelurahan/{$districtId}.json");
            
            if (!$response->successful()) {
                throw new \Exception('API request failed with status ' . $response->status());
            }
            
            $villages = $response->json();
            
            $data = collect($villages)->map(function ($item) {
                return [
                    'id' => $item['id'],
                    'name' => $item['nama']
                ];
            })->values()->all();
            
            return response()->json(['data' => $data]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal mengambil data kelurahan',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}