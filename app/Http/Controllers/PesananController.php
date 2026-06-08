<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Http\Requests\PesananStoreRequest;
use App\Http\Requests\PesananUpdateRequest;
use App\Http\Requests\PesananUpdateStatusRequest;
use Illuminate\Support\Facades\Auth;

class PesananController extends Controller
{

    

    /**
     * Display listing
     */
    public function index()
    {
        $status = request()->get('status');
        $search = request()->get('search');
        
        $query = Pesanan::query();
        
        if ($status && in_array($status, ['Baru', 'Proses', 'Selesai', 'Diambil'])) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('kode_pesanan', 'like', "%{$search}%")
                  ->orWhere('nama_pelanggan', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%");
            });
        }
        
        $pesanan = $query->latest()->get();

        return view('pesanan.index', compact('pesanan', 'status', 'search'));
    }

    /**
     * Form create
     */
    public function create()
    {
        $lastPesanan = Pesanan::latest()->first();

        if ($lastPesanan) {
            $prefixLength = str_starts_with($lastPesanan->kode_pesanan, 'PSN') ? 3 : 2;
            $number = (int) substr($lastPesanan->kode_pesanan, $prefixLength);
            $number++;
        } else {
            $number = 1;
        }

        $kodePesanan = 'PSN' . str_pad($number, 3, '0', STR_PAD_LEFT);

        return view('pesanan.create', compact('kodePesanan'));
    }

    /**
     * Store data baru
     */
    public function store(PesananStoreRequest $request)
    {
        $validated = $request->validated();

        $validated['total_harga'] = $validated['berat'] * $validated['harga_perkg'];
        $validated['status'] = 'Baru';

        Pesanan::create($validated);


        return redirect()
            ->route('pesanan.index')
            ->with('success', 'Data berhasil ditambahkan');
    }

    /**
     * Form edit
     */
    public function edit(Pesanan $pesanan)
    {
        return view('pesanan.edit', compact('pesanan'));
    }

    /**
     * Update data (tanpa status)
     */
    public function update(PesananUpdateRequest $request, Pesanan $pesanan)
    {
        $validated = $request->validated();

        $validated['total_harga'] = $validated['berat'] * $validated['harga_perkg'];

        $pesanan->update($validated);


        return redirect()
            ->route('pesanan.index')
            ->with('success', 'Data berhasil diupdate');
    }

    /**
     * 🔥 UPDATE STATUS (UNTUK DROPDOWN)
     */
    public function updateStatus(PesananUpdateStatusRequest $request, Pesanan $pesanan)
    {
        $pesanan->update([
            'status' => $request->status,
        ]);

        return redirect()->route('pesanan.index');
    }




    /**
     * Hapus data
     */
    public function destroy(Pesanan $pesanan)
    {
        abort_if(!Auth::check() || Auth::user()->role !== 'admin', 403, 'Hanya Pemilik (Admin) yang dapat menghapus data pesanan.');

        $pesanan->delete();

        return redirect()
            ->route('pesanan.index')
            ->with('success', 'Data berhasil dihapus');
    }
}
