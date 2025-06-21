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
        Schema::create('stok_masuks', function (Blueprint $table) {
            $table->id(); // ID unik untuk setiap transaksi masuk

            // Kolom foreign key yang terhubung ke tabel 'barangs'
            // onDelete('cascade') berarti jika data barang dihapus, semua riwayat stok masuknya juga akan terhapus
            $table->foreignId('barang_id')->constrained('barangs')->onDelete('cascade');

            // Kolom foreign key yang terhubung ke tabel 'users'
            // onDelete('restrict') mencegah pengguna dihapus jika masih memiliki riwayat transaksi
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');

            $table->decimal('jumlah', 15, 2); // Jumlah stok yang ditambahkan
            $table->date('tanggal_masuk'); // Tanggal transaksi

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_masuks');
    }
};
