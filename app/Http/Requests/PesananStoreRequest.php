<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PesananStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'kode_pesanan' => ['required', 'string', 'max:255'],
            'nama_pelanggan' => ['required', 'string', 'max:255'],
            'no_hp' => ['required', 'string', 'max:20'],
            'berat' => ['required', 'numeric', 'min:0.1'],
            'harga_perkg' => ['required', 'integer', 'min:0'],
            'tanggal_masuk' => ['required', 'date'],
        ];
    }
}

