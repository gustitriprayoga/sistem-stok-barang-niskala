<?php

namespace App\Filament\Widgets;

use App\Models\Barang;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class StokKritisWidget extends BaseWidget
{
    // Judul yang akan ditampilkan di atas tabel
    protected static ?string $heading = 'Barang dengan Stok Kritis';

    // Mengatur seberapa lebar widget ini, 'full' berarti lebar penuh
    protected int | string | array $columnSpan = 'full';

    // Method untuk mengambil data yang akan ditampilkan
    public function table(Table $table): Table
    {
        return $table
            // Query untuk mengambil barang yang stoknya di bawah 20%
            ->query(
                Barang::query()
                    ->where('stok_maksimal', '>', 0)
                    ->whereRaw('stok_sekarang < (stok_maksimal * 0.20)')
                    ->orderBy('stok_sekarang', 'asc') // Urutkan dari yang paling sedikit stoknya
            )
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Barang'),

                Tables\Columns\TextColumn::make('stok_sekarang')
                    ->label('Sisa Stok'),

                /**
                 * (a) PASTIKAN NAMA DI DALAM make() PERSIS SEPERTI INI
                 */
                Tables\Columns\TextColumn::make('persentase_stok')
                    ->label('Sisa Stok (%)')
                    ->formatStateUsing(fn($state) => number_format($state, 2) . ' %')
                    ->color('danger'),
            ])
            ->paginated(false);
    }
}
