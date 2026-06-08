<!DOCTYPE html>
<html>
<head>
    <title>Edit Pesanan</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body style="background:#f4f6f9;">

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-md-7">

            <div class="card shadow-sm border-0">

                <div class="card-body p-4">

                    <h4 class="mb-4">Edit Pesanan</h4>

                    <form action="{{ route('pesanan.update', $pesanan->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Kode Pesanan</label>
                            <input type="text"
                                   name="kode_pesanan"
                                   value="{{ $pesanan->kode_pesanan }}"
                                   class="form-control"
                                   readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nama Pelanggan</label>
                            <input type="text"
                                   name="nama_pelanggan"
                                   value="{{ $pesanan->nama_pelanggan }}"
                                   class="form-control"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">No HP</label>
                            <input type="text"
                                   name="no_hp"
                                   value="{{ $pesanan->no_hp }}"
                                   class="form-control"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Berat (Kg)</label>
                            <input type="number"
                                   step="0.1"
                                   name="berat"
                                   value="{{ $pesanan->berat }}"
                                   class="form-control"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Harga / Kg</label>
                            <input type="number"
                                   name="harga_perkg"
                                   value="{{ $pesanan->harga_perkg }}"
                                   class="form-control"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status</label>

                            <select name="status" class="form-select">

                                <option value="Baru"
                                    {{ $pesanan->status == 'Baru' ? 'selected' : '' }}>
                                    Baru
                                </option>

                                <option value="Proses"
                                    {{ $pesanan->status == 'Proses' ? 'selected' : '' }}>
                                    Proses
                                </option>

                                <option value="Selesai"
                                    {{ $pesanan->status == 'Selesai' ? 'selected' : '' }}>
                                    Selesai
                                </option>

                            </select>

                        </div>

                        <div class="d-flex gap-2">

                            <button type="submit" class="btn btn-success">
                                Update
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