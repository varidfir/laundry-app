<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PesananController;
use App\Http\Controllers\Api\TransaksiController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    // User routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // ==========================================
    // Rute yang BISA diakses oleh KASIR & ADMIN
    // ==========================================
    
    // Pesanan routes
    Route::get('/pesanan', [PesananController::class, 'index']);
    Route::get('/pesanan/{id}', [PesananController::class, 'show']);
    Route::post('/pesanan', [PesananController::class, 'store']);
    Route::put('/pesanan/{id}', [PesananController::class, 'update']);
    Route::patch('/pesanan/{id}/status', [PesananController::class, 'updateStatus']);

    // Transaksi routes
    Route::get('/transaksi', [TransaksiController::class, 'index']);
    Route::get('/transaksi/{id}', [TransaksiController::class, 'show']);
    Route::post('/transaksi', [TransaksiController::class, 'store']);
    Route::get('/pesanan/{pesananId}/transaksi', [TransaksiController::class, 'getByPesanan']);

    // ==========================================
    // Rute yang HANYA BISA diakses oleh ADMIN
    // ==========================================
    Route::middleware('admin')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::delete('/pesanan/{id}', [PesananController::class, 'destroy']);
        Route::delete('/transaksi/{id}', [TransaksiController::class, 'destroy']);
    });
});