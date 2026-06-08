@extends('layouts.app')

@section('content')

<h2 class="mb-4">Dashboard Laundry</h2>

<div class="row">

    {{-- TOTAL PESANAN --}}
    <div class="col-md-3 mb-3">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <h6>Total Pesanan</h6>
                <h2>{{ $totalPesanan }}</h2>
            </div>
        </div>
    </div>

    {{-- BARU --}}
    <div class="col-md-3 mb-3">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <h6>Baru</h6>
                <h2>{{ $baru }}</h2>
            </div>
        </div>
    </div>

    {{-- DIPROSES --}}
    <div class="col-md-3 mb-3">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <h6>Diproses</h6>
                <h2>{{ $diproses }}</h2>
            </div>
        </div>
    </div>

    {{-- SELESAI --}}
    <div class="col-md-3 mb-3">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <h6>Selesai</h6>
                <h2>{{ $selesai }}</h2>
            </div>
        </div>
    </div>

    {{-- DIAMBIL --}}
    <div class="col-md-3 mb-3">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <h6>Diambil</h6>
                <h2>{{ $diambil }}</h2>
            </div>
        </div>
    </div>

    {{-- PENDAPATAN --}}
    <div class="col-md-3 mb-3">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <h6>Pendapatan</h6>
                <h4>Rp {{ number_format($pendapatan) }}</h4>
            </div>
        </div>
    </div>

</div>

@endsection