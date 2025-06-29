<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Barang;
use App\Models\User;
use App\Models\StokMasuk; // Buat model StokMasuk
use App\Models\StokKeluar; // Buat model StokKeluar
use Faker\Factory;

class StokTransaksiSeeder extends Seeder
{
    public function run(): void
    {
        // $faker = Factory::create('id_ID'); // Menggunakan data Faker Indonesia
        // $semuaBarang = Barang::all();
        // $semuaUser = User::all();

        // foreach ($semuaBarang as $barang) {
        //     $totalStokMasuk = 0;
        //     $totalStokKeluar = 0;

        //     // Membuat 5-10 transaksi stok masuk per barang
        //     for ($i = 0; $i < rand(5, 10); $i++) {
        //         $jumlah = rand(10, 50);
        //         StokMasuk::create([
        //             'barang_id' => $barang->id,
        //             'user_id' => $semuaUser->random()->id,
        //             'jumlah' => $jumlah,
        //             'tanggal_masuk' => $faker->dateTimeBetween('-3 months', 'now'),
        //         ]);
        //         $totalStokMasuk += $jumlah;
        //     }

        //     // Membuat 3-7 transaksi stok keluar per barang
        //     for ($i = 0; $i < rand(3, 7); $i++) {
        //         // Pastikan jumlah keluar tidak melebihi stok masuk
        //         $jumlah = rand(5, 20);
        //         if (($totalStokMasuk - $totalStokKeluar) > $jumlah) {
        //             StokKeluar::create([
        //                 'barang_id' => $barang->id,
        //                 'user_id' => $semuaUser->random()->id,
        //                 'jumlah' => $jumlah,
        //                 'tanggal_keluar' => $faker->dateTimeBetween('-2 months', 'now'),
        //             ]);
        //             $totalStokKeluar += $jumlah;
        //         }
        //     }

        //     // Update stok_sekarang di tabel barangs
        //     $barang->stok_sekarang = $totalStokMasuk - $totalStokKeluar;
        //     $barang->save();
        }
    }
}
