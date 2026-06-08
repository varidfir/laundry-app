# Sistem Laundry REST API

Sistem Laundry berbasis **REST API** yang dibangun menggunakan **Laravel 13** dan **Laravel Sanctum**.

Aplikasi ini menerapkan sistem **Authentication**, **Role-Based Access Control (RBAC)** dengan tingkatan hak akses untuk **Admin** dan **Kasir**, manajemen relasi database, kalkulasi kembalian otomatis, perubahan status pesanan otomatis, hingga pencetakan struk pembayaran dalam format PDF.

---

# Dokumen Pengumpulan & Pengujian

* **Laporan Pengujian Aplikasi**
* **Postman Collection:** [`Laundry-App API (Fixed).postman_collection.json](https://github.com/varidfir/laundry-app/blob/main/Laundry-App%20API%20test.postman_collection.json)`

---

# Fitur Utama & Hak Akses (RBAC)

## Admin

* Memiliki semua hak akses yang dimiliki oleh **Kasir**.
* **Menambahkan Akun Baru** — Dapat mendaftarkan akun kasir baru.
* **Menghapus Data Pesanan** — Memiliki otorisasi penuh untuk menghapus data dari database.
* **Menghapus Data Transaksi** — Dapat menghapus riwayat transaksi pembayaran lama.

## Kasir

### Authentication

* Login
* Logout
* Melihat profil user yang sedang aktif

### Manajemen Pesanan

* Membuat data pesanan baru
* Melihat daftar pesanan
* Melihat detail pesanan
* Memperbarui informasi pesanan

### Alur Status Laundry

* Mengubah status pengerjaan pesanan laundry

### Manajemen Transaksi

* Melakukan input pembayaran
* Melihat riwayat transaksi
* Menghitung uang kembalian secara otomatis
* Melihat detail transaksi berdasarkan pesanan tertentu

---

# 🚀 Panduan Instalasi Projek

Ikuti langkah-langkah berikut untuk menjalankan proyek ini di lingkungan lokal Anda.

## 1. Clone Repository

```bash
git clone <url-repository-anda>
cd laundry-app
```

## 2. Install Dependency

```bash
composer install
```

## 3. Salin Konfigurasi Environment

### Windows (CMD / PowerShell)

```cmd
copy .env.example .env
```

### Linux / macOS

```bash
cp .env.example .env
```

## 4. Generate Application Key

```bash
php artisan key:generate
```

## 5. Konfigurasi Database

Buka file `.env`, lalu sesuaikan konfigurasi database berikut:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laundry_db
DB_USERNAME=root
DB_PASSWORD=
```

## 6. Jalankan Migration & Seeder

```bash
php artisan migrate --seed
```
## 7. Jalankan Server Lokal

```bash
php artisan serve
```

Server akan berjalan pada:

```text
http://127.0.0.1:8000
```
**Akun Default (Admin):**
- **Email:** `admin@gmail.com`
- **Password:** `admin123`

**Akun Default (Kasir):**
- **Email:** `kasir1@gmail.com`
- **Password:** `kasir123`
---

# Dokumentasi Endpoint API

> **Catatan Pengujian**
>
> Pastikan menambahkan header berikut pada Postman:
>
> ```http
> Accept: application/json
> ```
>
> Agar Laravel selalu mengembalikan respons JSON ketika terjadi error validasi.

---

# 1. Authentication Route

| Method | Endpoint        | Akses      | Keterangan                                      |
| ------ | --------------- | ---------- | ----------------------------------------------- |
| POST   | `/api/register` | Admin Only | Mendaftarkan akun kasir baru (Hanya Admin)      |
| POST   | `/api/login`    | Publik     | Autentikasi akun untuk mendapatkan Bearer Token |
| POST   | `/api/logout`   | Token      | Menghapus token aktif dan keluar dari sistem    |
| GET    | `/api/user`     | Token      | Mengambil profil user yang sedang login         |

---

# 2. Manajemen Pesanan (Sanctum Protected)

| Method      | Endpoint                   | Akses         | Keterangan                                 |
| ----------- | -------------------------- | ------------- | ------------------------------------------ |
| GET         | `/api/pesanan`             | Kasir & Admin | Menampilkan seluruh daftar pesanan laundry |
| POST        | `/api/pesanan`             | Kasir & Admin | Membuat data pesanan baru                  |
| GET         | `/api/pesanan/{id}`        | Kasir & Admin | Melihat detail satu pesanan                |
| PUT / PATCH | `/api/pesanan/{id}`        | Kasir & Admin | Memperbarui data pesanan                   |
| PATCH       | `/api/pesanan/{id}/status` | Kasir & Admin | Mengubah status progress laundry           |
| DELETE      | `/api/pesanan/{id}`        | Admin Only    | Menghapus data pesanan                     |

---

# 3. Manajemen Transaksi (Sanctum Protected)

| Method | Endpoint                              | Akses         | Keterangan                                                               |
| ------ | ------------------------------------- | ------------- | ------------------------------------------------------------------------ |
| GET    | `/api/transaksi`                      | Kasir & Admin | Melihat seluruh riwayat transaksi                                        |
| POST   | `/api/transaksi`                      | Kasir & Admin | Membuat transaksi baru (otomatis hitung kembalian & ubah status pesanan) |
| GET    | `/api/transaksi/{id}`                 | Kasir & Admin | Melihat detail transaksi                                                 |
| GET    | `/api/pesanan/{pesanan_id}/transaksi` | Kasir & Admin | Mencari transaksi berdasarkan ID pesanan                                 |
| DELETE | `/api/transaksi/{id}`                 | Admin Only    | Menghapus riwayat transaksi                                              |

---

# Struktur Kode Penting

```text
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── PesananController.php
│   │   └── TransaksiController.php
│   │       # Mengatur logika REST API & Web Render sekaligus
│   │
│   └── Requests/
│       └── TransaksiStoreRequest.php
│           # Validasi input transaksi dari sisi server
│
├── Models/
│   ├── User.php
│   │   # Representasi tabel user & RBAC (Role)
│   │
│   ├── Pesanan.php
│   │   # Model Pesanan (Relasi HasOne/HasMany ke Transaksi)
│   │
│   └── Transaksi.php
│       # Model Transaksi (Relasi BelongsTo ke Pesanan)
│
database/
├── migrations/
│   # Struktur blueprint tabel database laundry
│
└── seeders/
    # Data awal pengujian (User Admin & Kasir)
```
