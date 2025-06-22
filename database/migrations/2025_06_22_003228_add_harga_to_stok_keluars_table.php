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
        Schema::table('stok_keluars', function (Blueprint $table) {
            // Harga jual per-unit saat transaksi terjadi
            $table->decimal('harga_jual', 15, 2)->default(0)->after('jumlah');
            // Total harga (jumlah * harga_jual)
            $table->decimal('total_harga', 15, 2)->default(0)->after('harga_jual');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stok_keluars', function (Blueprint $table) {
            $table->dropColumn(['harga_jual', 'total_harga']);
        });
    }
};
