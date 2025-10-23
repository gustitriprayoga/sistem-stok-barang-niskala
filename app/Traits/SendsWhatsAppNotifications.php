<?php

namespace App\Traits;

use App\Models\Barang;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

trait SendsWhatsAppNotifications
{
    /**
     * Method untuk mengirim notifikasi WhatsApp.
     */
    public function sendWhatsAppNotification(Barang $barang): void
    {
        try {
            // Ambil konfigurasi dari file .env
            $apiUrl = config('app.wa_api_url', env('WA_API_URL'));
            $sessionId = config('app.wa_session_id', env('WA_API_SESSION'));
            $recipient = config('app.wa_recipient_number', env('WA_RECIPIENT_NUMBER'));

            // Pastikan semua konfigurasi ada
            if (!$apiUrl || !$sessionId || !$recipient) {
                Log::error('Konfigurasi API WhatsApp tidak lengkap. Cek file .env');
                return; // Hentikan fungsi jika konfigurasi kurang
            }

            // Buat pesan notifikasi
            $message = sprintf(
                "⚠️ PERINGATAN STOK MENIPIS ⚠️\n\nStok untuk barang '%s' telah mencapai batas kritis.\n\nSisa Stok: *%s %s*\n\nMohon untuk segera melakukan pengadaan ulang.",
                $barang->nama,
                $barang->stok_sekarang,
                $barang->satuan
            );

            // Kirim request ke API WhatsApp
            $response = Http::withoutVerifying()->post($apiUrl, [
                'sessionId' => $sessionId,
                'to' => $recipient,
                'text' => $message,
            ]);

            // Catat jika ada error dari API
            if ($response->failed()) {
                Log::error('Gagal mengirim notifikasi WA - Respon API: ' . $response->body());
            }
        } catch (\Exception $e) {
            // Jika API gagal, catat error di log agar tidak mengganggu proses utama
            Log::error('Gagal mengirim notifikasi WA - Exception: ' . $e->getMessage());
        }
    }
}
