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
    public function index(Request $request)
    {
        $transaksi = Transaksi::with('pesanan')->latest()->get();
        
        // JIKA DIMINTA LEWAT POSTMAN (API)
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Daftar semua transaksi berhasil diambil',
                'data' => $transaksi
            ], 200);
        }

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

       
        $kembalian = $validated['bayar'] - $pesanan->total_harga;

        
        if ($kembalian < 0) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Uang pembayaran tidak cukup'
                ], 422);
            }
            return redirect()->back()->withErrors(['bayar' => 'Uang pembayaran tidak cukup'])->withInput();
        }

        $transaksi = Transaksi::create([
            'pesanan_id' => $pesanan->id,
            'bayar' => $validated['bayar'],
            'kembalian' => $kembalian,
            'tanggal_bayar' => now()
        ]);
        
        $pesanan->update([
            'status' => 'Diambil'
        ]);

        
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil dibuat',
                'data' => $transaksi->load('pesanan') 
            ], 201);
        }

       
        return redirect()
            ->route(
                'transaksi.struk',
                $transaksi->id
            );
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        
        $transaksi = Transaksi::with('pesanan')->findOrFail($id);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Detail transaksi berhasil ditemukan',
                'data' => $transaksi
            ], 200);
        }

        return view('transaksi.show', compact('transaksi'));
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
    public function destroy(Request $request, Transaksi $transaksi)
    {
        
        $user = $request->user() ?? Auth::user();

        if (!$user || $user->role !== 'admin') {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya Admin yang dapat menghapus riwayat transaksi.'
                ], 403);
            }
            abort(403, 'Hanya Admin yang dapat menghapus riwayat transaksi.');
        }

       
        $transaksi->pesanan->update([
            'status' => 'Selesai'
        ]);

        
        $transaksi->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Riwayat transaksi berhasil dihapus'
            ], 200);
        }

        return redirect()
            ->route('transaksi.index')
            ->with('success', 'Riwayat transaksi berhasil dihapus');
    }
}