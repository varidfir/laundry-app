<?php

namespace App\Http\Controllers\Api;

use App\Models\Transaksi;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    public function index()
    {
        try {
            $transaksi = Transaksi::with('pesanan')->latest()->get();

            return response()->json([
                'message' => 'Transaksi retrieved successfully',
                'data' => $transaksi
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error retrieving transaksi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $transaksi = Transaksi::with('pesanan')->findOrFail($id);

            return response()->json([
                'message' => 'Transaksi retrieved successfully',
                'data' => $transaksi
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Transaksi not found'
            ], 404);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'pesanan_id' => 'required|exists:pesanan,id',
                'bayar' => 'required|numeric|min:0',
            ]);

            $pesanan = Pesanan::findOrFail($validated['pesanan_id']);

            if ($validated['bayar'] < $pesanan->total_harga) {
                return response()->json([
                    'message' => 'Payment amount is less than total price',
                    'total_harga' => $pesanan->total_harga,
                    'bayar' => $validated['bayar']
                ], 422);
            }

            $validated['kembalian'] = $validated['bayar'] - $pesanan->total_harga;
            $validated['tanggal_bayar'] = now();

            $transaksi = Transaksi::create($validated);

            // Update pesanan status to Diambil
            $pesanan->update(['status' => 'Diambil']);

            return response()->json([
                'message' => 'Transaksi created successfully',
                'data' => $transaksi->load('pesanan')
            ], 201);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Pesanan not found'
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    public function getByPesanan($pesananId)
    {
        try {
            $pesanan = Pesanan::findOrFail($pesananId);
            $transaksi = $pesanan->transaksi;

            return response()->json([
                'message' => 'Transaksi retrieved successfully',
                'data' => $transaksi
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Pesanan not found'
            ], 404);
        }
    }

    public function destroy($id)
    {
        try {
            $user = Auth::user();
            
            if (!$user || $user->role !== 'admin') {
                return response()->json([
                    'message' => 'Unauthorized. Only admin can delete transaksi.'
                ], 403);
            }

            $transaksi = Transaksi::findOrFail($id);
            
            // Update status pesanan kembali ke Selesai
            $transaksi->pesanan->update([
                'status' => 'Selesai'
            ]);

            // Hapus transaksi
            $transaksi->delete();

            return response()->json([
                'message' => 'Transaksi deleted successfully'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Transaksi not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error deleting transaksi',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
