<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransaksiStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'pesanan_id' => ['required', 'integer', 'exists:pesanan,id'],
            'bayar' => ['required', 'numeric', 'min:0'],
        ];
    }
}

