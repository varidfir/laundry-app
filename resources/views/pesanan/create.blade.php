<!DOCTYPE html>
<html>
<head>
    <title>Tambah Pesanan</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body style="background:#f4f6f9;">

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-md-7">

            <div class="card shadow-sm border-0">

                <div class="card-body p-4">

                    <h4 class="mb-4">Tambah Pesanan</h4>

                    <form action="{{ route('pesanan.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Kode Pesanan</label>
                            <input type="text"
                                   name="kode_pesanan"
                                   value="{{ $kodePesanan }}"
                                   class="form-control"
                                   readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nama Pelanggan</label>
                            <input type="text"
                                   name="nama_pelanggan"
                                   class="form-control"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">No HP</label>
                            <input type="text"
                                   name="no_hp"
                                   class="form-control"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Berat (Kg)</label>
                            <input type="number"
                                   step="0.1"
                                   name="berat"
                                   class="form-control"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Harga / Kg</label>
                            <input type="number"
                                   name="harga_perkg"
                                   value="7000"
                                   class="form-control"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tanggal Masuk</label>
                            <input type="date"
                                   name="tanggal_masuk"
                                   class="form-control"
                                   required>
                        </div>

                        <div class="d-flex gap-2">

                            <button type="submit" class="btn btn-primary">
                                Simpan
                            </button>

                            <a href="{{ route('pesanan.index') }}" class="btn btn-secondary">
                                Kembali
                            </a>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>