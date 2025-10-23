<?php

namespace App\Filament\Pages;

use App\Models\StokKeluar;
use App\Models\StokMasuk;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;

class LaporanTransaksi extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static string $view = 'filament.pages.laporan-transaksi';
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?string $title = 'Laporan Riwayat Transaksi';
    protected static ?int $navigationSort = 2; // Urutan di menu Laporan

    public ?array $data = [];
    public array $hasilLaporan = [];

    // Tampilkan hanya untuk admin
    public static function canAccess(): bool
    {
        return auth()->user()->hasRole('admin');
    }

    // Filter berdasarkan rentang tanggal
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        DatePicker::make('tanggal_mulai')
                            ->label('Tanggal Mulai')
                            ->default(now()->startOfMonth())
                            ->live(),
                        DatePicker::make('tanggal_akhir')
                            ->label('Tanggal Akhir')
                            ->default(now()->endOfMonth())
                            ->live(),
                    ])
                    ->columns(2),
            ])
            ->statePath('data');
    }

    // Inisialisasi halaman
    public function mount(): void
    {
        $this->form->fill([
            'tanggal_mulai' => now()->startOfMonth()->format('Y-m-d'),
            'tanggal_akhir' => now()->endOfMonth()->format('Y-m-d'),
        ]);

        $this->generateReportData();
    }

    // Perbarui data saat filter tanggal diubah
    public function updated($name)
    {
        if (in_array($name, ['data.tanggal_mulai', 'data.tanggal_akhir'])) {
            $this->generateReportData();
        }
    }

    // Logika utama untuk mengambil, menggabung, dan mengurutkan data
    private function generateReportData(): void
    {
        $data = $this->form->getState();

        // Inisialisasi hasil laporan untuk mencegah error
        if (empty($data['tanggal_mulai']) || empty($data['tanggal_akhir'])) {
            $this->hasilLaporan = [
                'total_barang_masuk' => 0,
                'total_barang_keluar' => 0,
                'detail_transaksi' => [],
            ];
            return;
        }

        // 1. Ambil data stok keluar dan format
        $stokKeluar = StokKeluar::whereBetween('tanggal_keluar', [$data['tanggal_mulai'], $data['tanggal_akhir']])
            ->with('barang', 'user')
            ->get()
            ->map(function ($item) {
                $item->tipe = 'keluar';
                $item->tanggal_transaksi = $item->tanggal_keluar;
                return $item;
            });

        // 2. Ambil data stok masuk dan format
        $stokMasuk = StokMasuk::whereBetween('tanggal_masuk', [$data['tanggal_mulai'], $data['tanggal_akhir']])
            ->with('barang', 'user')
            ->get()
            ->map(function ($item) {
                $item->tipe = 'masuk';
                $item->tanggal_transaksi = $item->tanggal_masuk;
                return $item;
            });

        // 3. Gabungkan kedua koleksi data dan urutkan berdasarkan tanggal terbaru
        $transaksiGabungan = $stokMasuk->merge($stokKeluar)->sortByDesc('tanggal_transaksi');

        // 4. Siapkan data untuk ditampilkan di view
        $this->hasilLaporan = [
            'total_barang_masuk' => $stokMasuk->sum('jumlah'),
            'total_barang_keluar' => $stokKeluar->sum('jumlah'),
            'detail_transaksi' => $transaksiGabungan,
        ];
    }
}
