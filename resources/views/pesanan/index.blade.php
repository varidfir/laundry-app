@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Kelola Pesanan</h2>

    <a href="{{ route('pesanan.create') }}" class="btn btn-primary">
        + Tambah Pesanan
    </a>
</div>

<!-- Filter & Search Bar -->
<div class="row g-3 mb-4 align-items-center">
    <div class="col-lg-8">
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('pesanan.index', request()->filled('search') ? ['search' => request('search')] : []) }}" 
               class="btn {{ is_null($status) ? 'btn-primary' : 'btn-outline-secondary' }} px-4 rounded-pill shadow-sm">
                Semua Pesanan
            </a>
            <a href="{{ route('pesanan.index', array_merge(['status' => 'Baru'], request()->filled('search') ? ['search' => request('search')] : [])) }}" 
               class="btn {{ $status === 'Baru' ? 'btn-primary' : 'btn-outline-secondary' }} px-4 rounded-pill shadow-sm">
                Baru
            </a>
            <a href="{{ route('pesanan.index', array_merge(['status' => 'Proses'], request()->filled('search') ? ['search' => request('search')] : [])) }}" 
               class="btn {{ $status === 'Proses' ? 'btn-primary' : 'btn-outline-secondary' }} px-4 rounded-pill shadow-sm">
                Di Proses
            </a>
            <a href="{{ route('pesanan.index', array_merge(['status' => 'Selesai'], request()->filled('search') ? ['search' => request('search')] : [])) }}" 
               class="btn {{ $status === 'Selesai' ? 'btn-primary' : 'btn-outline-secondary' }} px-4 rounded-pill shadow-sm">
                Selesai
            </a>
            <a href="{{ route('pesanan.index', array_merge(['status' => 'Diambil'], request()->filled('search') ? ['search' => request('search')] : [])) }}" 
               class="btn {{ $status === 'Diambil' ? 'btn-primary' : 'btn-outline-secondary' }} px-4 rounded-pill shadow-sm">
                Di Ambil
            </a>
        </div>
    </div>
    <div class="col-lg-4">
        <form action="{{ route('pesanan.index') }}" method="GET">
            @if($status)
                <input type="hidden" name="status" value="{{ $status }}">
            @endif
            <div class="input-group shadow-sm rounded-pill overflow-hidden bg-white border">
                <span class="input-group-text bg-white border-0 pe-0 text-muted">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" 
                       name="search" 
                       id="search-input"
                       value="{{ request('search') }}" 
                       class="form-control border-0 ps-2 py-2" 
                       placeholder="Cari kode, nama, atau HP..."
                       aria-label="Cari pesanan"
                       style="box-shadow: none; font-size: 0.95rem;">
                @if(request()->filled('search'))
                    <a href="{{ route('pesanan.index', $status ? ['status' => $status] : []) }}" 
                       class="btn btn-link d-flex align-items-center justify-content-center px-3 text-muted border-0"
                       title="Hapus pencarian"
                       style="box-shadow: none; text-decoration: none;">
                        <i class="bi bi-x-lg"></i>
                    </a>
                @endif
                <button type="submit" class="btn btn-primary px-4 rounded-pill m-1 py-1 d-flex align-items-center" style="font-size: 0.9rem;">
                    Cari
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">

        <table class="table table-hover align-middle">

            <thead class="table-light">
                <tr>
                    <th>Kode</th>
                    <th>Nama Pelanggan</th>
                    <th>No HP</th> {{-- ✅ TAMBAH INI --}}
                    <th>Waktu Dibuat</th>
                    <th>Berat</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

                @forelse($pesanan as $item)

                <tr>
                    <td>{{ $item->kode_pesanan }}</td>
                    <td>{{ $item->nama_pelanggan }}</td>

                    {{-- ✅ INI YANG HILANG --}}
                    <td>{{ $item->no_hp }}</td>
                    <td>{{ $item->created_at->format('d-m-Y H:i') }}</td>

                    <td>{{ $item->berat }} Kg</td>
                    <td>Rp {{ number_format($item->total_harga) }}</td>

                    {{-- STATUS --}}
                    <td>
                        <form action="{{ route('pesanan.updateStatus', $item->id) }}" method="POST">
                            @csrf

                            <select name="status"
                                    onchange="this.form.submit()"
                                    class="form-select form-select-sm"
                                    style="width:120px;">

                                <option value="Baru" {{ $item->status == 'Baru' ? 'selected' : '' }}>Baru</option>
                                <option value="Proses" {{ $item->status == 'Proses' ? 'selected' : '' }}>Proses</option>
                                <option value="Selesai" {{ $item->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="Diambil" {{ $item->status == 'Diambil' ? 'selected' : '' }}>Diambil</option>

                            </select>

                        </form>
                    </td>

                    {{-- AKSI --}}
                    <td>
                        <a href="{{ route('pesanan.edit', $item->id) }}" class="btn btn-warning btn-sm">
                            Edit
                        </a>

                        <a href="{{ route('transaksi.create', $item->id) }}" class="btn btn-success btn-sm">
                            Bayar
                        </a>

                        @if(auth()->user()->role === 'admin')
                        <form action="{{ route('pesanan.destroy', $item->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')

                            <button class="btn btn-danger btn-sm"
                                    onclick="return confirm('Hapus data ini?')">
                                Hapus
                            </button>
                        </form>
                        @endif
                    </td>

                </tr>

                @empty
                <tr>
                    <td colspan="8" class="text-center py-4 text-muted">
                        @if(request()->filled('search'))
                            Tidak ada pesanan yang cocok dengan pencarian <strong>"{{ request('search') }}"</strong>
                            @if($status)
                                dengan status <strong>"{{ $status }}"</strong>
                            @endif
                        @else
                            Belum ada data pesanan
                            @if($status)
                                dengan status <strong>"{{ $status }}"</strong>
                            @endif
                        @endif
                    </td>
                </tr>
                @endforelse

            </tbody>

        </table>

    </div>
</div>

@endsection