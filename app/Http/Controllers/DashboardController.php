<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Transaksi;

class DashboardController extends Controller
{
    public function index()
    {
        // Total semua pesanan
        $totalPesanan = Pesanan::count();

        // Status pesanan
        $baru = Pesanan::where('status', 'Baru')->count();
        $diproses = Pesanan::where('status', 'Proses')->count();
        $selesai = Pesanan::where('status', 'Selesai')->count();
        $diambil = Pesanan::where('status', 'Diambil')->count();

        // Total pendapatan (hanya dari pesanan yang sudah dibayar)
        $pendapatan = Pesanan::whereHas('transaksi')->sum('total_harga');

        return view('dashboard', compact(
            'totalPesanan',
            'baru',
            'diproses',
            'selesai',
            'diambil',
            'pendapatan'
        ));
    }
}