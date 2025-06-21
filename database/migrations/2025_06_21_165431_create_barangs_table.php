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
        Schema::create('barangs', function (Blueprint $table) {
            $table->id(); // Kolom ID unik untuk setiap barang
            $table->string('nama'); // Kolom untuk nama bahan baku, contoh: 'Tepung Terigu'
            $table->string('satuan', 50); // Kolom untuk satuan, contoh: 'kg', 'liter', 'pcs'

            // Menggunakan tipe data decimal untuk stok agar bisa menampung angka pecahan (contoh: 10.5 kg)
            // Default 0 berarti saat barang baru dibuat, stoknya adalah 0
            $table->decimal('stok_sekarang', 15, 2)->default(0);
            $table->decimal('stok_maksimal', 15, 2)->default(0);

            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};
