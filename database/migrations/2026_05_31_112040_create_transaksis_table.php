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
    Schema::create('transaksi', function (Blueprint $table) {

        $table->id();

        $table->foreignId('pesanan_id')
              ->constrained('pesanan')
              ->onDelete('cascade');

        $table->integer('bayar');

        $table->integer('kembalian');

        $table->date('tanggal_bayar');

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
