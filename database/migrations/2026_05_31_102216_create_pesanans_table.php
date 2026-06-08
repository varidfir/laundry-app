<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id();

            $table->string('kode_pesanan');
            $table->string('nama_pelanggan');
            $table->string('no_hp');

            $table->decimal('berat', 8, 2);
            $table->integer('harga_perkg');
            $table->integer('total_harga');

            $table->enum('status', [
                'Baru',
                'Proses',
                'Selesai',
                'Diambil',
            ])->default('Baru');

            $table->date('tanggal_masuk');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};

