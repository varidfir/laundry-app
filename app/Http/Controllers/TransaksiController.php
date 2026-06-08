<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Pesanan;
use App\Http\Requests\TransaksiStoreRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transaksi = Transaksi::with('pesanan')->latest()->get();
        return view('transaksi.index', compact('transaksi'));
    }

    /**
     * Show the form for creating a new resource.
     */
   public function create($id)
{
    $pesanan = Pesanan::findOrFail($id);

    return view(
        'transaksi.create',
        compact('pesanan')
    );
}

    /**
     * Store a newly created resource in storage.
     */
  public function store(TransaksiStoreRequest $request)
{
    $validated = $request->validated();
    
    $pesanan = Pesanan::findOrFail(
        $validated['pesanan_id']
    );

    $kembalian =
        $validated['bayar'] -
        $pesanan->total_harga;

   $transaksi = Transaksi::create([

        'pesanan_id' => $pesanan->id,

        'bayar' => $validated['bayar'],

        'kembalian' => $kembalian,

        'tanggal_bayar' => now()

    ]);
    

    $pesanan->update([
        'status' => 'Diambil'
    ]);

    return redirect()
        ->route(
            'transaksi.struk',
            $transaksi->id
        );
}
public function struk($id)
{
    $transaksi = Transaksi::with('pesanan')
                    ->findOrFail($id);

    $pdf = Pdf::loadView(
        'transaksi.struk',
        compact('transaksi')
    );

    return $pdf->stream(
        'struk-laundry.pdf'
    );
}

    /**
     * Display the specified resource.
     */
    public function show(Transaksi $transaksi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaksi $transaksi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaksi $transaksi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaksi $transaksi)
    {
        abort_if(!Auth::check() || Auth::user()->role !== 'admin', 403, 'Hanya Admin yang dapat menghapus riwayat transaksi.');

        // Update status pesanan kembali ke Selesai
        $transaksi->pesanan->update([
            'status' => 'Selesai'
        ]);

        // Hapus transaksi
        $transaksi->delete();

        return redirect()
            ->route('transaksi.index')
            ->with('success', 'Riwayat transaksi berhasil dihapus');
    }
}
