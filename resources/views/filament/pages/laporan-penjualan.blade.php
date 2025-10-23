<x-filament-panels::page>
    {{-- Form Filter akan tetap ada, tapi tanpa tombol submit --}}
    <div>
        {{ $this->form }}
    </div>

    {{-- Hasil laporan akan selalu ditampilkan berdasarkan state terakhir --}}
    <div class="mt-6">
        {{-- Bagian Ringkasan Statistik --}}
        <div class="grid grid-cols-1 gap-6 md:grid-cols-3 mb-6">
            <div class="p-6 bg-white dark:bg-gray-800/50 rounded-xl shadow">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Pendapatan</p>
                <p class="text-3xl font-bold">Rp {{ number_format($hasilLaporan['total_pendapatan'] ?? 0, 2, ',', '.') }}</p>
            </div>
            <div class="p-6 bg-white dark:bg-gray-800/50 rounded-xl shadow">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Barang Terjual</p>
                <p class="text-3xl font-bold">{{ number_format($hasilLaporan['total_barang_terjual'] ?? 0, 2, ',', '.') }}</p>
            </div>
            <div class="p-6 bg-white dark:bg-gray-800/50 rounded-xl shadow">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Jumlah Transaksi</p>
                <p class="text-3xl font-bold">{{ $hasilLaporan['jumlah_transaksi'] ?? 0 }}</p>
            </div>
        </div>

        {{-- Tabel Detail Transaksi --}}
        <div class="overflow-x-auto bg-white dark:bg-gray-800/50 rounded-xl shadow">
            <table class="min-w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Tanggal</th>
                        <th scope="col" class="px-6 py-3">Nama Barang</th>
                        <th scope="col" class="px-6 py-3">Jumlah</th>
                        <th scope="col" class="px-6 py-3">Total Harga</th>
                        <th scope="col" class="px-6 py-3">Dicatat oleh</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($hasilLaporan['detail_transaksi'] ?? [] as $transaksi)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td class="px-6 py-4">{{ \Carbon\Carbon::parse($transaksi->tanggal_keluar)->format('d M Y') }}</td>
                            <td class="px-6 py-4">{{ $transaksi->barang->nama }}</td>
                            <td class="px-6 py-4">{{ $transaksi->jumlah }}</td>
                            <td class="px-6 py-4">Rp {{ number_format($transaksi->total_harga, 2, ',', '.') }}</td>
                            <td class="px-6 py-4">{{ $transaksi->user->name }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center">Tidak ada data untuk periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-filament-panels::page>
