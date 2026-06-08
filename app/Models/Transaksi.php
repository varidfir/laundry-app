<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';

    protected $fillable = [
        'pesanan_id',
        'bayar',
        'kembalian',
        'tanggal_bayar'
    ];

    protected $casts = [
        'tanggal_bayar' => 'datetime',
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }
}