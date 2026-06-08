<?php

use App\Models\User;
use App\Models\Pesanan;

// ─── Helper untuk membuat user berdasarkan role ───────────────────────────────

function makeAdmin(): User
{
    return User::factory()->create(['role' => 'admin']);
}

function makeKasir(): User
{
    return User::factory()->create(['role' => 'kasir']);
}

function makePesanan(): Pesanan
{
    return Pesanan::create([
        'kode_pesanan'   => 'PSN-TEST-' . rand(1000, 9999),
        'nama_pelanggan' => 'Test Pelanggan',
        'no_hp'          => '08123456789',
        'berat'          => 2,
        'harga_perkg'    => 10000,
        'total_harga'    => 20000,
        'status'         => 'Baru',
        'tanggal_masuk'  => now(),
    ]);
}

// ─── GUEST: Tidak bisa mengakses halaman yang dilindungi ──────────────────────

test('guest diblokir dari halaman pesanan', function () {
    $response = $this->get('/pesanan');
    $response->assertRedirect('/login');
});

test('guest diblokir dari halaman users', function () {
    $response = $this->get('/users');
    $response->assertRedirect('/login');
});

test('guest diblokir dari halaman riwayat transaksi', function () {
    $response = $this->get('/transaksi');
    $response->assertRedirect('/login');
});

// ─── ADMIN: Bisa mengakses semua halaman ─────────────────────────────────────

test('admin bisa mengakses halaman pesanan', function () {
    $admin = makeAdmin();
    $this->actingAs($admin)->get('/pesanan')->assertStatus(200);
});

test('admin bisa mengakses halaman manajemen user', function () {
    $admin = makeAdmin();
    $this->actingAs($admin)->get('/users')->assertStatus(200);
});

test('admin bisa mengakses halaman riwayat transaksi', function () {
    $admin = makeAdmin();
    $this->actingAs($admin)->get('/transaksi')->assertStatus(200);
});

test('admin bisa menghapus pesanan', function () {
    $admin   = makeAdmin();
    $pesanan = makePesanan();

    $response = $this->actingAs($admin)->delete("/pesanan/{$pesanan->id}");

    $response->assertRedirect('/pesanan');
    $this->assertDatabaseMissing('pesanan', ['id' => $pesanan->id]);
});

test('admin bisa membuat user baru', function () {
    $admin = makeAdmin();

    $response = $this->actingAs($admin)->post('/users', [
        'name'     => 'Kasir Baru',
        'email'    => 'kasirbaru@test.com',
        'password' => 'password123',
        'role'     => 'kasir',
    ]);

    $response->assertRedirect('/users');
    $this->assertDatabaseHas('users', [
        'email' => 'kasirbaru@test.com',
        'role'  => 'kasir',
    ]);
});

// ─── KASIR: Akses terbatas ────────────────────────────────────────────────────

test('kasir bisa mengakses halaman pesanan', function () {
    $kasir = makeKasir();
    $this->actingAs($kasir)->get('/pesanan')->assertStatus(200);
});

test('kasir bisa mengakses halaman riwayat transaksi', function () {
    $kasir = makeKasir();
    $this->actingAs($kasir)->get('/transaksi')->assertStatus(200);
});

test('kasir dilarang mengakses manajemen user', function () {
    $kasir = makeKasir();
    $this->actingAs($kasir)->get('/users')->assertStatus(403);
});

test('kasir dilarang membuat user baru', function () {
    $kasir = makeKasir();

    $response = $this->actingAs($kasir)->post('/users', [
        'name'     => 'User Baru',
        'email'    => 'userbaru@test.com',
        'password' => 'password123',
        'role'     => 'kasir',
    ]);

    $response->assertStatus(403);
    $this->assertDatabaseMissing('users', ['email' => 'userbaru@test.com']);
});

test('kasir dilarang menghapus pesanan', function () {
    $kasir   = makeKasir();
    $pesanan = makePesanan();

    $response = $this->actingAs($kasir)->delete("/pesanan/{$pesanan->id}");

    $response->assertStatus(403);
    $this->assertDatabaseHas('pesanan', ['id' => $pesanan->id]);
});

// ─── KASIR: Boleh update status & edit pesanan ───────────────────────────────

test('kasir bisa mengupdate status pesanan', function () {
    $kasir   = makeKasir();
    $pesanan = makePesanan();

    $response = $this->actingAs($kasir)->post("/pesanan/{$pesanan->id}/status", [
        '_token' => csrf_token(),
        'status' => 'Proses',
    ]);

    // Harus redirect setelah update status (bukan 403)
    $response->assertRedirect();
    $this->assertDatabaseHas('pesanan', [
        'id'     => $pesanan->id,
        'status' => 'Proses',
    ]);
});

// ─── API: Kasir tidak bisa menghapus pesanan via API ─────────────────────────

test('api: kasir dilarang menghapus pesanan', function () {
    $kasir   = makeKasir();
    $pesanan = makePesanan();

    $response = $this->actingAs($kasir)
        ->deleteJson("/api/pesanan/{$pesanan->id}");

    $response->assertStatus(403);
    $this->assertDatabaseHas('pesanan', ['id' => $pesanan->id]);
});

test('api: admin bisa menghapus pesanan', function () {
    $admin   = makeAdmin();
    $pesanan = makePesanan();

    $response = $this->actingAs($admin)
        ->deleteJson("/api/pesanan/{$pesanan->id}");

    $response->assertStatus(200);
    $this->assertDatabaseMissing('pesanan', ['id' => $pesanan->id]);
});

// ─── TRANSAKSI: Pembayaran pesanan ───────────────────────────────────────────

test('kasir bisa membuat transaksi pembayaran via web', function () {
    $kasir   = makeKasir();
    $pesanan = makePesanan(); // total_harga = 20000

    $response = $this->actingAs($kasir)->post('/transaksi/store', [
        'pesanan_id' => $pesanan->id,
        'bayar'      => 25000,
    ]);

    // Berhasil dan diarahkan ke halaman struk
    $response->assertRedirect();
    
    // Memastikan data transaksi tersimpan di database
    $this->assertDatabaseHas('transaksi', [
        'pesanan_id' => $pesanan->id,
        'bayar'      => 25000,
        'kembalian'  => 5000,
    ]);

    // Memastikan status pesanan berubah menjadi 'Diambil'
    $this->assertDatabaseHas('pesanan', [
        'id'     => $pesanan->id,
        'status' => 'Diambil',
    ]);
});

test('api: user bisa membuat transaksi pembayaran via api', function () {
    $kasir   = makeKasir();
    $pesanan = makePesanan(); // total_harga = 20000

    $response = $this->actingAs($kasir)
        ->postJson('/api/transaksi', [
            'pesanan_id' => $pesanan->id,
            'bayar'      => 30000,
        ]);

    $response->assertStatus(201);
    
    // Memastikan data transaksi tersimpan
    $this->assertDatabaseHas('transaksi', [
        'pesanan_id' => $pesanan->id,
        'bayar'      => 30000,
        'kembalian'  => 10000,
    ]);

    // Memastikan status pesanan berubah menjadi 'Diambil'
    $this->assertDatabaseHas('pesanan', [
        'id'     => $pesanan->id,
        'status' => 'Diambil',
    ]);
});
