<?php

namespace App\Filament\Widgets;

use App\Models\Barang;
use App\Models\StokKeluar;
use App\Models\StokMasuk;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StokStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // Logika untuk menghitung barang dengan stok kritis (di bawah 20%)
        // Ditambahkan where('stok_maksimal', '>', 0) untuk menghindari pembagian dengan nol
        $stokKritis = Barang::where('stok_maksimal', '>', 0)
                            ->whereRaw('stok_sekarang < (stok_maksimal * 0.20)')
                            ->count();

        // Logika untuk menghitung total transaksi hari ini
        $transaksiHariIni = StokMasuk::whereDate('created_at', today())->count() +
                            StokKeluar::whereDate('created_at', today())->count();

        return [
            // Kartu 1: Menampilkan total jenis barang
            Stat::make('Total Jenis Barang', Barang::count())
                ->description('Jumlah semua bahan baku terdaftar')
                ->descriptionIcon('heroicon-m-archive-box')
                ->color('success'),

            // Kartu 2: Menampilkan jumlah barang yang stoknya kritis
            Stat::make('Stok Kritis', $stokKritis)
                ->description('Barang dengan stok di bawah 20%')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger'), // Warna merah untuk menandakan bahaya/penting

            // Kartu 3: Menampilkan total transaksi hari ini
            Stat::make('Transaksi Hari Ini', $transaksiHariIni)
                ->description('Total stok masuk & keluar hari ini')
                ->descriptionIcon('heroicon-m-arrows-right-left')
                ->color('info'),
        ];
    }
}