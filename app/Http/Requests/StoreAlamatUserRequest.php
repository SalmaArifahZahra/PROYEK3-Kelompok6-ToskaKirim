<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAlamatUserRequest extends FormRequest
{
    /**
     * Tentukan apakah user diizinkan membuat request ini.
     */
    public function authorize(): bool
    {
        return true; 
    }

    /**
     * Dapatkan aturan validasi yang berlaku untuk request ini.
     */
    public function rules(): array
    {
        return [
            'label_alamat' => 'required|string|max:100',
            'nama_penerima' => 'required|string|max:255',
            'telepon_penerima' => 'required|string|max:20',
            'kota_kabupaten' => 'required|string|max:100',
            'kecamatan' => 'required|string|max:100',
            'kelurahan' => 'required|string|max:100',
            'jalan_patokan' => 'required|string',
            'rt' => 'nullable|string|max:5',
            'rw' => 'nullable|string|max:5',
            'no_rumah' => 'nullable|string|max:20',
            'is_utama' => 'nullable|boolean',
        ];
    }
}