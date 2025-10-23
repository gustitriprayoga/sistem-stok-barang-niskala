<x-filament-panels::page>
    {{-- Form Filter Tanggal --}}
    <div>
        {{ $this->form }}
    </div>

    {{-- Hasil Laporan --}}
    <div class="mt-6">

        {{-- Kartu Statistik --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow">
                <div class="flex items-center">
                    <div class="bg-green-100 dark:bg-green-500/20 text-green-600 dark:text-green-400 rounded-lg p-3">
                        <x-heroicon-o-arrow-down class="w-6 h-6" />
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Barang Masuk</p>
                        <p class="text-2xl font-bold">
                            {{ number_format($hasilLaporan['total_barang_masuk'] ?? 0, 2, ',', '.') }}</p>
                    </div>
                </div>
            </div>
            <div class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow">
                <div class="flex items-center">
                    <div class="bg-red-100 dark:bg-red-500/20 text-red-600 dark:text-red-400 rounded-lg p-3">
                        <x-heroicon-o-arrow-up class="w-6 h-6" />
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Barang Keluar</p>
                        <p class="text-2xl font-bold">
                            {{ number_format($hasilLaporan['total_barang_keluar'] ?? 0, 2, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ✅ PERBAIKAN: Tabel Riwayat Transaksi dibuat lebar penuh --}}
        <div class="overflow-x-auto bg-white dark:bg-gray-800 shadow md:-mx-6 lg:-mx-8 md:rounded-none">
            <table class="min-w-full w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Tanggal</th>
                        <th scope="col" class="px-6 py-3">Status</th>
                        <th scope="col" class="px-6 py-3">Nama Barang</th>
                        <th scope="col" class="px-6 py-3">Jumlah</th>
                        <th scope="col" class="px-6 py-3">Dicatat oleh</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($hasilLaporan['detail_transaksi'] ?? [] as $transaksi)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td class="px-6 py-4">
                                {{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d M Y') }}</td>
                            <td class="px-6 py-4">
                                @if ($transaksi->tipe == 'masuk')
                                    <span
                                        class="inline-flex items-center px-2 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full dark:bg-green-900 dark:text-green-300">MASUK</span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2 py-1 text-xs font-medium text-red-700 bg-red-100 rounded-full dark:bg-red-900 dark:text-red-300">KELUAR</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">{{ $transaksi->barang->nama }}</td>
                            <td class="px-6 py-4">{{ $transaksi->jumlah }} {{ $transaksi->barang->satuan }}</td>
                            <td class="px-6 py-4">{{ $transaksi->user->name }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center">Tidak ada data transaksi untuk periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-filament-panels::page>
