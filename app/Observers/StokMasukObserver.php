<?php

namespace App\Observers;

use App\Models\StokMasuk;

class StokMasukObserver
{
    /**
     * Handle the StokMasuk "created" event.
     */
    public function created(StokMasuk $stokMasuk): void
    {
        $barang = $stokMasuk->barang;

        if ($barang) {
            // Menambah stok_sekarang di tabel barangs
            $barang->increment('stok_sekarang', $stokMasuk->jumlah);
        }
    }

    /**
     * Handle the StokMasuk "updated" event.
     */
    public function updated(StokMasuk $stokMasuk): void
    {
        //
    }

    /**
     * Handle the StokMasuk "deleted" event.
     */
    public function deleted(StokMasuk $stokMasuk): void
    {
        $barang = $stokMasuk->barang;

        if ($barang) {
            // Mengurangi stok_sekarang (pembatalan stok masuk)
            $barang->decrement('stok_sekarang', $stokMasuk->jumlah);
        }
    }

    /**
     * Handle the StokMasuk "restored" event.
     */
    public function restored(StokMasuk $stokMasuk): void
    {
        //
    }

    /**
     * Handle the StokMasuk "force deleted" event.
     */
    public function forceDeleted(StokMasuk $stokMasuk): void
    {
        //
    }
}
