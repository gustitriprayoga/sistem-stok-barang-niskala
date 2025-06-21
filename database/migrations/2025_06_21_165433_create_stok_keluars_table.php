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
        Schema::create('stok_keluars', function (Blueprint $table) {
            $table->id(); // ID unik untuk setiap transaksi keluar

            // Foreign key ke tabel 'barangs'
            $table->foreignId('barang_id')->constrained('barangs')->onDelete('cascade');

            // Foreign key ke tabel 'users'
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');

            $table->decimal('jumlah', 15, 2); // Jumlah stok yang digunakan/keluar
            $table->date('tanggal_keluar'); // Tanggal transaksi

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_keluars');
    }
};
