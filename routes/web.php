<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\UserController;

Route::get(
    '/transaksi/create/{id}',
    [TransaksiController::class, 'create']
)->name('transaksi.create');

Route::get(
    '/transaksi/struk/{id}',
    [TransaksiController::class, 'struk']
)->name('transaksi.struk');

Route::post(
    '/transaksi/store',
    [TransaksiController::class, 'store']
)->name('transaksi.store');

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::resource('pesanan', PesananController::class)->middleware('auth');

Route::get('/transaksi', [TransaksiController::class, 'index'])
    ->name('transaksi.index')
    ->middleware('auth');

Route::delete('/transaksi/{transaksi}', [TransaksiController::class, 'destroy'])
    ->name('transaksi.destroy')
    ->middleware('auth');

Route::resource('users', UserController::class)->middleware(['auth', 'role:admin']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/pesanan/{pesanan}/status', [PesananController::class, 'updateStatus'])
    ->name('pesanan.updateStatus');

require __DIR__.'/auth.php';