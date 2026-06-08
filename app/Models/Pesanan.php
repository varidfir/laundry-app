<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table = 'pesanan';

    protected $fillable = [
        'kode_pesanan',
        'nama_pelanggan',
        'no_hp',
        'berat',
        'harga_perkg',
        'total_harga',
        'status',
        'tanggal_masuk'
    ];
    public function transaksi()
{
    return $this->hasOne(Transaksi::class);
}
}