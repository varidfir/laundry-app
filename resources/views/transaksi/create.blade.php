<!DOCTYPE html>
<html>
<head>
    <title>Pembayaran Laundry</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body style="background:#f4f6f9;">

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-md-6">

            <div class="card shadow-sm border-0">

                <div class="card-body p-4">

                    <h4 class="mb-4">Pembayaran Laundry</h4>

                    <div class="mb-3">
                        <strong>Kode :</strong><br>
                        {{ $pesanan->kode_pesanan }}
                    </div>

                    <div class="mb-3">
                        <strong>Nama :</strong><br>
                        {{ $pesanan->nama_pelanggan }}
                    </div>

                    <div class="mb-3">
                        <strong>Total Tagihan :</strong><br>
                        <span class="text-success fw-bold">
                            Rp {{ number_format($pesanan->total_harga) }}
                        </span>
                    </div>

                    <hr>

                    <form action="{{ route('transaksi.store') }}" method="POST">
                        @csrf

                        <input type="hidden" name="pesanan_id" value="{{ $pesanan->id }}">

                        <div class="mb-3">
                            <label class="form-label">Uang Bayar</label>
                            <input type="number"
                                   name="bayar"
                                   class="form-control"
                                   required
                                   placeholder="Masukkan jumlah pembayaran">
                        </div>

                        <button type="submit" class="btn btn-success w-100">
                            Simpan Pembayaran
                        </button>

                        <a href="{{ route('pesanan.index') }}" class="btn btn-secondary w-100 mt-2">
                            Kembali
                        </a>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>