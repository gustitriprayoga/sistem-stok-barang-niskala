<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Barang; // Pastikan Anda sudah membuat Model 'Barang'

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dataBarang = [
            ['nama' => 'Tepung Terigu Protein Tinggi', 'satuan' => 'kg', 'stok_maksimal' => 200],
            ['nama' => 'Gula Pasir Kristal', 'satuan' => 'kg', 'stok_maksimal' => 150],
            ['nama' => 'Telur Ayam Negeri', 'satuan' => 'butir', 'stok_maksimal' => 1000],
            ['nama' => 'Minyak Goreng Sawit', 'satuan' => 'liter', 'stok_maksimal' => 100],
            ['nama' => 'Garam Meja Beryodium', 'satuan' => 'gram', 'stok_maksimal' => 5000],
            ['nama' => 'Ragi Instan', 'satuan' => 'gram', 'stok_maksimal' => 1000],
        ];

        foreach ($dataBarang as $barang) {
            // Gunakan Model Barang untuk membuat data baru
            // Pastikan Anda sudah membuat model dengan `php artisan make:model Barang`
            Barang::create([
                'nama' => $barang['nama'],
                'satuan' => $barang['satuan'],
                'stok_sekarang' => 0, // Stok awal selalu 0
                'stok_maksimal' => $barang['stok_maksimal'],
            ]);
        }
    }
}