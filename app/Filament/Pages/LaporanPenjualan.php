<?php

namespace App\Filament\Pages;

use App\Models\StokKeluar;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;

class LaporanPenjualan extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';
    protected static string $view = 'filament.pages.laporan-penjualan';
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?string $title = 'Laporan Penjualan';

    // =================================================================
    // TAMBAHKAN BARIS INI (INILAH PERBAIKANNYA)
    // =================================================================
    public ?array $data = [];
    // =================================================================

    // Properti untuk menampung hasil, akan di-passing ke view
    public array $hasilLaporan = [];

    // Form schema tidak berubah
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

    public function mount(): void
    {
        // karena $data sudah ada, kita bisa langsung mengisinya
        $this->form->fill([
            'tanggal_mulai' => now()->startOfMonth()->format('Y-m-d'),
            'tanggal_akhir' => now()->endOfMonth()->format('Y-m-d'),
        ]);

        $this->generateReportData();
    }

    public function updated($name)
    {
        if (str_starts_with($name, 'data.tanggal_')) {
            $this->generateReportData();
        }
    }

    private function generateReportData(): void
    {
        // Sekarang $this->form->getState() akan berjalan lancar
        $data = $this->form->getState();

        $query = StokKeluar::whereBetween('tanggal_keluar', [
            $data['tanggal_mulai'],
            $data['tanggal_akhir']
        ]);

        $this->hasilLaporan = [
            'total_pendapatan' => $query->sum('total_harga'),
            'total_barang_terjual' => $query->sum('jumlah'),
            'jumlah_transaksi' => $query->count(),
            'detail_transaksi' => $query->with('barang', 'user')->orderBy('tanggal_keluar', 'desc')->get(),
        ];
    }
}
