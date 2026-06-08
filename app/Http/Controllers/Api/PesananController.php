<?php

namespace App\Http\Controllers\Api;

use App\Models\Pesanan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;

class PesananController extends Controller
{
    public function index()
    {
        try {
            $status = request()->get('status');
            $search = request()->get('search');
            $query = Pesanan::with('transaksi');
            
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

            return response()->json([
                'message' => 'Pesanan retrieved successfully',
                'data' => $pesanan
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error retrieving pesanan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $pesanan = Pesanan::with('transaksi')->findOrFail($id);

            return response()->json([
                'message' => 'Pesanan retrieved successfully',
                'data' => $pesanan
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Pesanan not found'
            ], 404);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'kode_pesanan' => 'required|string|unique:pesanan',
                'nama_pelanggan' => 'required|string|max:255',
                'no_hp' => 'required|string|max:20',
                'berat' => 'required|numeric|min:0.1',
                'harga_perkg' => 'required|numeric|min:0',
                'status' => 'sometimes|in:Baru,Proses,Selesai,Diambil',
                'tanggal_masuk' => 'sometimes|date',
            ]);

            $validated['total_harga'] = $validated['berat'] * $validated['harga_perkg'];
            $validated['status'] = $validated['status'] ?? 'Baru';
            $validated['tanggal_masuk'] = $validated['tanggal_masuk'] ?? now();

            $pesanan = Pesanan::create($validated);

            return response()->json([
                'message' => 'Pesanan created successfully',
                'data' => $pesanan
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $pesanan = Pesanan::findOrFail($id);

            $validated = $request->validate([
                'kode_pesanan' => 'sometimes|string|unique:pesanan,kode_pesanan,' . $id,
                'nama_pelanggan' => 'sometimes|string|max:255',
                'no_hp' => 'sometimes|string|max:20',
                'berat' => 'sometimes|numeric|min:0.1',
                'harga_perkg' => 'sometimes|numeric|min:0',
                'status' => 'sometimes|in:Baru,Proses,Selesai,Diambil',
            ]);

            if (isset($validated['berat']) && isset($validated['harga_perkg'])) {
                $validated['total_harga'] = $validated['berat'] * $validated['harga_perkg'];
            } elseif (isset($validated['berat'])) {
                $validated['total_harga'] = $validated['berat'] * $pesanan->harga_perkg;
            } elseif (isset($validated['harga_perkg'])) {
                $validated['total_harga'] = $pesanan->berat * $validated['harga_perkg'];
            }

            $pesanan->update($validated);

            return response()->json([
                'message' => 'Pesanan updated successfully',
                'data' => $pesanan
            ], 200);
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

    public function destroy($id)
    {
        try {
            $pesanan = Pesanan::findOrFail($id);
            if (request()->user()->role !== 'admin') {
                return response()->json([
                    'message' => 'Hanya Pemilik (Admin) yang dapat menghapus data pesanan.'
                ], 403);
            }
            $pesanan->delete();

            return response()->json([
                'message' => 'Pesanan deleted successfully'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Pesanan not found'
            ], 404);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $pesanan = Pesanan::findOrFail($id);

            $validated = $request->validate([
                'status' => 'required|in:Baru,Proses,Selesai,Diambil',
            ]);

            $pesanan->update($validated);

            return response()->json([
                'message' => 'Status updated successfully',
                'data' => $pesanan
            ], 200);
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
}
