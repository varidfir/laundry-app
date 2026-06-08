<!DOCTYPE html>
<html>
<head>
    <title>Struk Laundry</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            width: 280px;
            margin: auto;
            text-align: center;
        }

        .header {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .sub {
            font-size: 12px;
            margin-bottom: 10px;
        }

        .line {
            border-top: 1px dashed #000;
            margin: 8px 0;
        }

        .row {
            display: flex;
            justify-content: space-between;
            margin: 3px 0;
            text-align: left;
        }

        .label {
            width: 50%;
        }

        .value {
            width: 50%;
            text-align: right;
        }

        .footer {
            margin-top: 10px;
            font-size: 12px;
        }

        @media print {
            button {
                display: none;
            }
        }
    </style>

</head>
<body>

<div class="header">LAUNDRY APP</div>
<div class="sub">Struk Pembayaran</div>

<div class="line"></div>

<div class="row">
    <div class="label">Kode</div>
    <div class="value">{{ $transaksi->pesanan->kode_pesanan }}</div>
</div>

<div class="row">
    <div class="label">Nama</div>
    <div class="value">{{ $transaksi->pesanan->nama_pelanggan }}</div>
</div>

<div class="row">
    <div class="label">Berat</div>
    <div class="value">{{ $transaksi->pesanan->berat }} Kg</div>
</div>

<div class="line"></div>

<div class="row">
    <div class="label">Total</div>
    <div class="value">Rp {{ number_format($transaksi->pesanan->total_harga) }}</div>
</div>

<div class="row">
    <div class="label">Bayar</div>
    <div class="value">Rp {{ number_format($transaksi->bayar) }}</div>
</div>

<div class="row">
    <div class="label"><b>Kembalian</b></div>
    <div class="value"><b>Rp {{ number_format($transaksi->kembalian) }}</b></div>
</div>

<div class="line"></div>

<div class="footer">
    Terima Kasih <br>
    Laundry Anda Aman & Cepat
</div>

<br>

<button onclick="window.print()" style="padding:8px 12px;">
    Print Struk
</button>

</body>
</html>