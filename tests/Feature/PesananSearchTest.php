<?php

use App\Models\Pesanan;
use App\Models\User;

test('web search by code works', function () {
    Pesanan::create([
        'kode_pesanan' => 'PSN001',
        'nama_pelanggan' => 'Alice Doe',
        'no_hp' => '08123456789',
        'berat' => 2.5,
        'harga_perkg' => 10000,
        'total_harga' => 25000,
        'status' => 'Baru',
        'tanggal_masuk' => now(),
    ]);

    Pesanan::create([
        'kode_pesanan' => 'PSN002',
        'nama_pelanggan' => 'Bob Smith',
        'no_hp' => '08987654321',
        'berat' => 3,
        'harga_perkg' => 10000,
        'total_harga' => 30000,
        'status' => 'Proses',
        'tanggal_masuk' => now(),
    ]);

    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/pesanan?search=PSN001');

    $response->assertStatus(200);
    $response->assertSee('PSN001');
    $response->assertSee('Alice Doe');
    $response->assertDontSee('PSN002');
    $response->assertDontSee('Bob Smith');
});

test('web search by name works', function () {
    Pesanan::create([
        'kode_pesanan' => 'PSN001',
        'nama_pelanggan' => 'Alice Doe',
        'no_hp' => '08123456789',
        'berat' => 2.5,
        'harga_perkg' => 10000,
        'total_harga' => 25000,
        'status' => 'Baru',
        'tanggal_masuk' => now(),
    ]);

    Pesanan::create([
        'kode_pesanan' => 'PSN002',
        'nama_pelanggan' => 'Bob Smith',
        'no_hp' => '08987654321',
        'berat' => 3,
        'harga_perkg' => 10000,
        'total_harga' => 30000,
        'status' => 'Proses',
        'tanggal_masuk' => now(),
    ]);

    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/pesanan?search=Alice');

    $response->assertStatus(200);
    $response->assertSee('PSN001');
    $response->assertSee('Alice Doe');
    $response->assertDontSee('PSN002');
    $response->assertDontSee('Bob Smith');
});

test('web search by phone number works', function () {
    Pesanan::create([
        'kode_pesanan' => 'PSN001',
        'nama_pelanggan' => 'Alice Doe',
        'no_hp' => '08123456789',
        'berat' => 2.5,
        'harga_perkg' => 10000,
        'total_harga' => 25000,
        'status' => 'Baru',
        'tanggal_masuk' => now(),
    ]);

    Pesanan::create([
        'kode_pesanan' => 'PSN002',
        'nama_pelanggan' => 'Bob Smith',
        'no_hp' => '08987654321',
        'berat' => 3,
        'harga_perkg' => 10000,
        'total_harga' => 30000,
        'status' => 'Proses',
        'tanggal_masuk' => now(),
    ]);

    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/pesanan?search=0898765');

    $response->assertStatus(200);
    $response->assertSee('PSN002');
    $response->assertSee('Bob Smith');
    $response->assertDontSee('PSN001');
    $response->assertDontSee('Alice Doe');
});

test('web search combined with status filter works', function () {
    Pesanan::create([
        'kode_pesanan' => 'PSN001',
        'nama_pelanggan' => 'Alice Doe',
        'no_hp' => '08123456789',
        'berat' => 2.5,
        'harga_perkg' => 10000,
        'total_harga' => 25000,
        'status' => 'Baru',
        'tanggal_masuk' => now(),
    ]);

    Pesanan::create([
        'kode_pesanan' => 'PSN002',
        'nama_pelanggan' => 'Alice Smith',
        'no_hp' => '08987654321',
        'berat' => 3,
        'harga_perkg' => 10000,
        'total_harga' => 30000,
        'status' => 'Proses',
        'tanggal_masuk' => now(),
    ]);

    $user = User::factory()->create();

    // Search "Alice" with status "Baru" -> should find PSN001 but not PSN002
    $response = $this->actingAs($user)->get('/pesanan?status=Baru&search=Alice');

    $response->assertStatus(200);
    $response->assertSee('PSN001');
    $response->assertSee('Alice Doe');
    $response->assertDontSee('PSN002');
    $response->assertDontSee('Alice Smith');
});

test('api search by code, name, and phone works', function () {
    $user = User::factory()->create();

    Pesanan::create([
        'kode_pesanan' => 'PSN001',
        'nama_pelanggan' => 'Alice Doe',
        'no_hp' => '08123456789',
        'berat' => 2.5,
        'harga_perkg' => 10000,
        'total_harga' => 25000,
        'status' => 'Baru',
        'tanggal_masuk' => now(),
    ]);

    Pesanan::create([
        'kode_pesanan' => 'PSN002',
        'nama_pelanggan' => 'Bob Smith',
        'no_hp' => '08987654321',
        'berat' => 3,
        'harga_perkg' => 10000,
        'total_harga' => 30000,
        'status' => 'Proses',
        'tanggal_masuk' => now(),
    ]);

    // Search by code
    $response = $this->actingAs($user)
        ->getJson('/api/pesanan?search=PSN001');

    $response->assertStatus(200)
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.kode_pesanan', 'PSN001');

    // Search by name
    $response = $this->actingAs($user)
        ->getJson('/api/pesanan?search=Bob');

    $response->assertStatus(200)
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.nama_pelanggan', 'Bob Smith');

    // Search by status and name combined
    $response = $this->actingAs($user)
        ->getJson('/api/pesanan?status=Baru&search=Alice');

    $response->assertStatus(200)
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.kode_pesanan', 'PSN001');
});
