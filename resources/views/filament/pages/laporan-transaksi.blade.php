<x-filament-panels::page>
    {{-- Form Filter --}}
    <div>
        {{ $this->form }}
    </div>

    <div class="mt-6">
        {{-- Kartu Statistik 3 Kolom --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-4">
            {{-- Masuk --}}
            <div class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow border-b-4 border-green-500">
                <div class="flex items-center">
                    <div class="bg-green-100 dark:bg-green-500/20 text-green-600 dark:text-green-400 rounded-lg p-3">
                        <x-heroicon-o-arrow-down class="w-6 h-6" />
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Barang Masuk</p>
                        <p class="text-2xl font-bold">{{ number_format($hasilLaporan['total_barang_masuk'] ?? 0, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            {{-- Keluar --}}
            <div class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow border-b-4 border-red-500">
                <div class="flex items-center">
                    <div class="bg-red-100 dark:bg-red-500/20 text-red-600 dark:text-red-400 rounded-lg p-3">
                        <x-heroicon-o-arrow-up class="w-6 h-6" />
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Barang Keluar</p>
                        <p class="text-2xl font-bold">{{ number_format($hasilLaporan['total_barang_keluar'] ?? 0, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            {{-- Pendapatan --}}
            <div class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow border-b-4 border-primary-500">
                <div class="flex items-center">
                    <div class="bg-blue-100 dark:bg-blue-500/20 text-blue-600 dark:text-blue-400 rounded-lg p-3">
                        <x-heroicon-o-currency-dollar class="w-6 h-6" />
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Pendapatan</p>
                        <p class="text-2xl font-bold">Rp {{ number_format($hasilLaporan['total_pendapatan'] ?? 0, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabel Riwayat Transaksi Full Width --}}
        <div class="overflow-hidden bg-white dark:bg-gray-800 shadow md:-mx-6 lg:-mx-8 md:rounded-none">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">Tanggal</th>
                            <th scope="col" class="px-6 py-3 text-center">Status</th>
                            <th scope="col" class="px-6 py-3">Nama Barang</th>
                            <th scope="col" class="px-6 py-3 text-right">Jumlah</th>
                            <th scope="col" class="px-6 py-3 text-right">Total Harga</th>
                            <th scope="col" class="px-6 py-3">Dicatat oleh</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($hasilLaporan['detail_transaksi'] ?? [] as $transaksi)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <td class="px-6 py-4 whitespace-nowrap italic">
                                    {{ \Carbon\Carbon::parse($transaksi['tanggal_transaksi'])->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if ($transaksi['tipe'] == 'masuk')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                            MASUK
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">
                                            KELUAR
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                    {{ $transaksi['barang']['nama'] ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    {{ number_format($transaksi['jumlah'], 0) }} {{ $transaksi['barang']['satuan'] ?? '' }}
                                </td>
                                <td class="px-6 py-4 text-right font-semibold">
                                    @if($transaksi['tipe'] == 'keluar')
                                        Rp {{ number_format($transaksi['total_harga'], 0, ',', '.') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-400">
                                    {{ $transaksi['user']['name'] ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center">Tidak ada data transaksi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-filament-panels::page>
