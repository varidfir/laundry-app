<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Pesanan;
use App\Models\Transaksi;
use Illuminate\Database\Seeder;

class ApiTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create test user
        $user = User::create([
            'name' => 'Admin Laundry',
            'email' => 'admin@laundry.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
        ]);

        echo "✓ Admin user created: admin@laundry.com / password123\n";

        // Create kasir user
        $kasir = User::create([
            'name' => 'Kasir Laundry',
            'email' => 'kasir@laundry.com',
            'password' => bcrypt('password123'),
            'role' => 'kasir',
        ]);

        echo "✓ Kasir user created: kasir@laundry.com / password123\n";

        // Create sample pesanan
        $pesanan1 = Pesanan::create([
            'kode_pesanan' => 'PSN001',
            'nama_pelanggan' => 'Budi Santoso',
            'no_hp' => '08123456789',
            'berat' => 5.5,
            'harga_perkg' => 15000,
            'total_harga' => 82500,
            'status' => 'Baru',
            'tanggal_masuk' => now(),
        ]);

        echo "✓ Pesanan 1 created: {$pesanan1->kode_pesanan}\n";

        $pesanan2 = Pesanan::create([
            'kode_pesanan' => 'PSN002',
            'nama_pelanggan' => 'Siti Rahayu',
            'no_hp' => '08987654321',
            'berat' => 3.5,
            'harga_perkg' => 15000,
            'total_harga' => 52500,
            'status' => 'Proses',
            'tanggal_masuk' => now()->subDays(1),
        ]);

        echo "✓ Pesanan 2 created: {$pesanan2->kode_pesanan}\n";

        $pesanan3 = Pesanan::create([
            'kode_pesanan' => 'PSN003',
            'nama_pelanggan' => 'Ahmad Wijaya',
            'no_hp' => '08765432109',
            'berat' => 7.0,
            'harga_perkg' => 15000,
            'total_harga' => 105000,
            'status' => 'Diambil',
            'tanggal_masuk' => now()->subDays(2),
        ]);

        echo "✓ Pesanan 3 created: {$pesanan3->kode_pesanan}\n";

        // Create sample transaksi
        $transaksi1 = Transaksi::create([
            'pesanan_id' => $pesanan3->id,
            'bayar' => 110000,
            'kembalian' => 5000,
            'tanggal_bayar' => now()->subDays(1),
        ]);

        echo "✓ Transaksi 1 created for Pesanan 3\n";

        echo "\n✅ Test data seeding completed!\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "API Ready for Testing\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "1. Start server: php artisan serve\n";
        echo "2. Login: POST /api/login\n";
        echo "   Email: admin@laundry.com\n";
        echo "   Password: password123\n";
        echo "3. Use returned token in Authorization header\n";
        echo "4. Test endpoints as documented\n";
    }
}
