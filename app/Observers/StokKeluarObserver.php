<?php

namespace App\Observers;

use App\Models\StokKeluar;

class StokKeluarObserver
{
    // Method ini akan berjalan SEBELUM data disimpan
    public function saving(StokKeluar $stokKeluar): void
    {
        // Hitung total harga otomatis
        $stokKeluar->total_harga = $stokKeluar->jumlah * $stokKeluar->harga_jual;
    }
    /**
     * Handle the StokKeluar "created" event.
     */
    public function created(StokKeluar $stokKeluar): void
    {
        //
    }

    /**
     * Handle the StokKeluar "updated" event.
     */
    public function updated(StokKeluar $stokKeluar): void
    {
        //
    }

    /**
     * Handle the StokKeluar "deleted" event.
     */
    public function deleted(StokKeluar $stokKeluar): void
    {
        //
    }

    /**
     * Handle the StokKeluar "restored" event.
     */
    public function restored(StokKeluar $stokKeluar): void
    {
        //
    }

    /**
     * Handle the StokKeluar "force deleted" event.
     */
    public function forceDeleted(StokKeluar $stokKeluar): void
    {
        //
    }
}
