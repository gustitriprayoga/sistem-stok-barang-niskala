<?php

namespace App\Observers;

use App\Models\Barang;
use App\Models\StokKeluar;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class StokKeluarObserver
{
    /**
     * Handle the StokKeluar "saving" event.
     * Method ini berjalan SEBELUM data disimpan, untuk menghitung total harga.
     */
    public function saving(StokKeluar $stokKeluar): void
    {
        if ($stokKeluar->isDirty('jumlah', 'harga_jual')) {
            $stokKeluar->total_harga = $stokKeluar->jumlah * $stokKeluar->harga_jual;
        }
    }

    /**
     * Handle the StokKeluar "created" event.
     * Method ini berjalan SETELAH data baru berhasil dibuat.
     */
    public function created(StokKeluar $stokKeluar): void
    {
        // 1. Ambil barang yang terkait dengan transaksi ini
        $barang = $stokKeluar->barang;

        // Simpan level stok sebelum dikurangi
        $stokSebelumnya = $barang->stok_sekarang;

        // 2. Kurangi stok barang
        $barang->stok_sekarang -= $stokKeluar->jumlah;
        $barang->save();

        // 3. Cek kondisi untuk notifikasi
        // Notifikasi hanya dikirim jika stok SEBELUMNYA di atas 10
        // dan stok SEKARANG di bawah atau sama dengan 10.
        // Ini mencegah notifikasi dikirim berulang kali (misal dari 9 ke 8).
        $ambangBatas = 10;
        if ($stokSebelumnya > $ambangBatas && $barang->stok_sekarang <= $ambangBatas) {
            $this->sendWhatsAppNotification($barang);
        }
    }

    /**
     * Method untuk mengirim notifikasi WhatsApp.
     */
    private function sendWhatsAppNotification(Barang $barang): void
    {
        try {
            // Ambil konfigurasi dari file .env
            $apiUrl = config('app.wa_api_url', env('WA_API_URL'));
            $sessionId = config('app.wa_session_id', env('WA_API_SESSION'));
            $recipient = config('app.wa_recipient_number', env('WA_RECIPIENT_NUMBER'));

            // Buat pesan notifikasi
            $message = sprintf(
                "⚠️ PERINGATAN STOK MENIPIS ⚠️\n\nStok untuk barang '%s' telah mencapai batas kritis.\n\nSisa Stok: *%s %s*\n\nMohon untuk segera melakukan pengadaan ulang.",
                $barang->nama,
                $barang->stok_sekarang,
                $barang->satuan
            );

            // Kirim request ke API WhatsApp
            Http::withoutVerifying()->post($apiUrl, [
                'sessionId' => $sessionId,
                'to' => $recipient,
                'text' => $message,
            ]);
        } catch (\Exception $e) {
            // Jika API gagal, catat error di log agar tidak mengganggu proses utama
            Log::error('Gagal mengirim notifikasi WA: ' . $e->getMessage());
        }
    }
}
