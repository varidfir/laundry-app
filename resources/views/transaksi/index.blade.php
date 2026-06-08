@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Riwayat Transaksi</h2>
        <p class="text-muted mb-0" style="font-size:14px;">Daftar semua transaksi pembayaran laundry</p>
    </div>
</div>

{{-- Ringkasan Statistik --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100" style="border-radius:12px; border-left:4px solid #3b82f6 !important;">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div style="width:48px; height:48px; border-radius:12px; background:rgba(59,130,246,0.1); display:flex; align-items:center; justify-content:center;">
                        <i class="bi bi-receipt fs-4 text-primary"></i>
                    </div>
                    <div>
                        <div class="text-muted" style="font-size:13px;">Total Transaksi</div>
                        <div class="fw-bold fs-4">{{ $transaksi->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100" style="border-radius:12px;">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div style="width:48px; height:48px; border-radius:12px; background:rgba(34,197,94,0.1); display:flex; align-items:center; justify-content:center;">
                        <i class="bi bi-cash-coin fs-4 text-success"></i>
                    </div>
                    <div>
                        <div class="text-muted" style="font-size:13px;">Total Pendapatan</div>
                        <div class="fw-bold fs-5">Rp {{ number_format($transaksi->sum(fn($t) => $t->pesanan->total_harga ?? 0)) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100" style="border-radius:12px;">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div style="width:48px; height:48px; border-radius:12px; background:rgba(168,85,247,0.1); display:flex; align-items:center; justify-content:center;">
                        <i class="bi bi-calendar3 fs-4" style="color:#8b5cf6;"></i>
                    </div>
                    <div>
                        <div class="text-muted" style="font-size:13px;">Transaksi Hari Ini</div>
                        <div class="fw-bold fs-4">{{ $transaksi->filter(fn($t) => $t->tanggal_bayar->isToday())->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">

        <table class="table table-hover align-middle">

            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Tanggal Bayar</th>
                    <th>Kode Pesanan</th>
                    <th>Nama Pelanggan</th>
                    <th>Total Tagihan</th>
                    <th>Jumlah Bayar</th>
                    <th>Kembalian</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

                @forelse($transaksi as $item)

                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td style="font-size:13px;">
                        <div class="fw-semibold">{{ $item->tanggal_bayar->format('d M Y') }}</div>
                        <div class="text-muted">{{ $item->tanggal_bayar->format('H:i') }}</div>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark border" style="font-family:monospace; letter-spacing:1px;">
                            {{ $item->pesanan->kode_pesanan ?? '-' }}
                        </span>
                    </td>
                    <td class="fw-semibold">{{ $item->pesanan->nama_pelanggan ?? '-' }}</td>
                    <td class="text-muted">Rp {{ number_format($item->pesanan->total_harga ?? 0) }}</td>
                    <td class="fw-semibold text-success">Rp {{ number_format($item->bayar) }}</td>
                    <td>
                        @if($item->kembalian > 0)
                            <span class="text-primary fw-semibold">Rp {{ number_format($item->kembalian) }}</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('transaksi.struk', $item->id) }}"
                           target="_blank"
                           class="btn btn-outline-secondary btn-sm"
                           title="Cetak Struk">
                            <i class="bi bi-printer-fill me-1"></i> Struk
                        </a>
                        @if(Auth::check() && Auth::user()->role === 'admin')
                            <form action="{{ route('transaksi.destroy', $item->id) }}" 
                                  method="POST" 
                                  style="display:inline-block;"
                                  onsubmit="return confirm('Yakin ingin menghapus transaksi ini? Status pesanan akan diubah ke Selesai.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="btn btn-outline-danger btn-sm"
                                        title="Hapus Transaksi">
                                    <i class="bi bi-trash-fill me-1"></i> Hapus
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>

                @empty
                <tr>
                    <td colspan="8" class="text-center py-5 text-muted">
                        <i class="bi bi-receipt fs-1 d-block mb-2 opacity-50"></i>
                        Belum ada riwayat transaksi
                    </td>
                </tr>
                @endforelse

            </tbody>

        </table>

    </div>
</div>

@endsection
