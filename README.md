# UTS Sistem Laundry REST API

Project ini adalah Sistem Laundry berbasis **REST API** yang dibangun menggunakan **Laravel 13** dan **Laravel Sanctum**. Project ini ditujukan untuk memenuhi tugas Ujian Tengah Semester (UTS).

Aplikasi ini menerapkan sistem **Authentication**, **Role-Based Access Control (RBAC)** dengan tingkatan hak akses untuk **Admin** dan **Kasir**, manajemen relasi database, kalkulasi kembalian otomatis, perubahan status pesanan otomatis, hingga pencetakan struk pembayaran dalam format PDF.

---

## Identitas
* **Nama:** Varid Firmansyah
* **NIM:** 2305101020
* **Kelas:** 6B
* **Tugas:** UTS WEB full stack

---

## Persyaratan Sistem
Sebelum menjalankan aplikasi ini, pastikan komputer/laptop sudah terinstall:
* PHP >= 8.1
* Composer
* MySQL (XAMPP / Laragon)
* Postman (untuk pengujian API)

---

## Cara Menjalankan Project (Installation Guide)
Ikuti langkah-langkah berikut untuk menjalankan proyek ini di lingkungan lokal Anda.

### 1. Clone Repository (Opsional)
```bash
git clone <url-repository-anda>
cd laundry-app
```

### 2. Install Dependensi (Penting)
Karena folder `vendor` mungkin tidak disertakan untuk menghemat ukuran, silakan buka Terminal/Command Prompt di dalam folder project ini, lalu jalankan perintah:
```bash
composer install
```

### 3. Setup Environment Variables
Salin file `.env.example` dan ubah namanya menjadi `.env`.
```cmd
copy .env.example .env
```
Setelah itu, generate application key dengan perintah:
```bash
php artisan key:generate
```

### 4. Setup Database
Buka phpMyAdmin atau HeidiSQL, lalu buat database baru (misalnya dengan nama `laundry_db`).

Buka file `.env` di text editor, lalu sesuaikan konfigurasi koneksi database berikut:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laundry_db
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Jalankan Migrasi & Seeder
Untuk membuat struktur tabel dan mengisi data awal (dummy data) berupa Akun Admin dan Kasir secara otomatis, jalankan perintah ini di Terminal:
```bash
php artisan migrate --seed
```

### 6. Jalankan Local Server
Setelah semua setup selesai, jalankan server Laravel dengan perintah:
```bash
php artisan serve
```
Server akan berjalan di http://127.0.0.1:8000.

**Akun Default (Admin):**
- **Email:** `admin@gmail.com`
- **Password:** `admin123`

**Akun Default (Kasir):**
- **Email:** `kasir1@gmail.com`
- **Password:** `kasir123`

---

## Pengujian API (Testing)
Untuk melakukan pengujian endpoint, Anda dapat meng-import file Collection Postman (.json) yang telah disertakan di dalam folder pengumpulan ini ke dalam aplikasi Postman Anda.

* **Dokumen Pengumpulan & Pengujian:**
    * [Postman Collection](<Laundry-App API test.postman_collection.json>)

### Hasil Pengujian API (Postman Screenshots)

1. **Register**
  ![alt text](Dokumentasi_Postman/Register.png)
2. **Login**
   ![alt text](Dokumentasi_Postman/Login.png)
3. **Get User**
   ![alt text](<Dokumentasi_Postman/Get Use.png>)

4. **Membuat pesanan**
  ![alt text](<Dokumentasi_Postman/Membuat pesanan.png>)
  
5. **Lihat semua pesanan**
  ![alt text](<Dokumentasi_Postman/Lihat semua pesanan.png>)

6. **Detail pesanan**
   ![alt text](<Dokumentasi_Postman/Detail pesanan.png>)
7. **Update pesanan**
   ![alt text](<Dokumentasi_Postman/Update pesanan.png>)
8. **Membuat transaksi pesanan**
   ![alt text](<Dokumentasi_Postman/Membuat transaksi pesanan.png>)
9. **Transaksi berdasarkan id pesanan**
   ![alt text](<Dokumentasi_Postman/Transaksi berdasarka pesanan.png>)
10. **Lihat semua transaksi**
    ![alt text](<Dokumentasi_Postman/Lihat semua transaksi.png>)
11. **Detail transaksi**
   ![alt text](<Dokumentasi_Postman/Detai transaksi.png>)
12. **Logout**
    ![alt text](Dokumentasi_Postman/Logout.png)

---

## LAPORAN TUGAS UTS: SISTEM LAUNDRY REST API

### 1. Fitur Utama & Hak Akses (RBAC)
**Admin:**
* Memiliki semua hak akses yang dimiliki oleh Kasir.
* **Menambahkan Akun Baru** — Dapat mendaftarkan akun kasir baru.
* **Menghapus Data Pesanan** — Memiliki otorisasi penuh untuk menghapus data dari database.
* **Menghapus Data Transaksi** — Dapat menghapus riwayat transaksi pembayaran lama.

**Kasir:**
* **Authentication:** Login, Logout, Melihat profil user yang sedang aktif.
* **Manajemen Pesanan:** Membuat data pesanan baru, Melihat daftar pesanan, Melihat detail pesanan, Memperbarui informasi pesanan.
* **Alur Status Laundry:** Mengubah status pengerjaan pesanan laundry.
* **Manajemen Transaksi:** Melakukan input pembayaran, Melihat riwayat transaksi, Menghitung uang kembalian secara otomatis, Melihat detail transaksi berdasarkan pesanan tertentu.

### 2. Entity Relationship Diagram (ERD) & Struktur Database
Sistem ini menggunakan beberapa tabel utama:
* **Tabel users**: Representasi tabel user & RBAC (Role untuk Admin dan Kasir).
* **Tabel pesanan**: Model Pesanan (Relasi HasOne/HasMany ke Transaksi).
* **Tabel transaksi**: Model Transaksi (Relasi BelongsTo ke Pesanan).

### 3. Daftar Endpoint API
Berikut adalah daftar rute API yang telah dibangun dan berhasil diuji di Postman:

> **Catatan Pengujian:** Pastikan menambahkan header `Accept: application/json` pada Postman agar Laravel selalu mengembalikan respons JSON ketika terjadi error validasi.

**Authentication Route**
| Method | Endpoint | Akses | Keterangan |
| --- | --- | --- | --- |
| POST | `/api/register` | Admin Only | Mendaftarkan akun kasir baru (Hanya Admin) |
| POST | `/api/login` | Publik | Autentikasi akun untuk mendapatkan Bearer Token |
| POST | `/api/logout` | Token | Menghapus token aktif dan keluar dari sistem |
| GET | `/api/user` | Token | Mengambil profil user yang sedang login |

**Manajemen Pesanan (Sanctum Protected)**
| Method | Endpoint | Akses | Keterangan |
| --- | --- | --- | --- |
| GET | `/api/pesanan` | Kasir & Admin | Menampilkan seluruh daftar pesanan laundry |
| POST | `/api/pesanan` | Kasir & Admin | Membuat data pesanan baru |
| GET | `/api/pesanan/{id}` | Kasir & Admin | Melihat detail satu pesanan |
| PUT / PATCH | `/api/pesanan/{id}` | Kasir & Admin | Memperbarui data pesanan |
| PATCH | `/api/pesanan/{id}/status` | Kasir & Admin | Mengubah status progress laundry |
| DELETE | `/api/pesanan/{id}` | Admin Only | Menghapus data pesanan |

**Manajemen Transaksi (Sanctum Protected)**
| Method | Endpoint | Akses | Keterangan |
| --- | --- | --- | --- |
| GET | `/api/transaksi` | Kasir & Admin | Melihat seluruh riwayat transaksi |
| POST | `/api/transaksi` | Kasir & Admin | Membuat transaksi baru (otomatis hitung kembalian & ubah status pesanan) |
| GET | `/api/transaksi/{id}` | Kasir & Admin | Melihat detail transaksi |
| GET | `/api/pesanan/{pesanan_id}/transaksi` | Kasir & Admin | Mencari transaksi berdasarkan ID pesanan |
| DELETE | `/api/transaksi/{id}` | Admin Only | Menghapus riwayat transaksi |

### 4. Struktur Kode Penting
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
│   ├── Pesanan.php
│   │   # Model Pesanan (Relasi HasOne/HasMany ke Transaksi)
│   └── Transaksi.php
│       # Model Transaksi (Relasi BelongsTo ke Pesanan)
│
database/
├── migrations/
│   # Struktur blueprint tabel database laundry
└── seeders/
    # Data awal pengujian (User Admin & Kasir)
```

