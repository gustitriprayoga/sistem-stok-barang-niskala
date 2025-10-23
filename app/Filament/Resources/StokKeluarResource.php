<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StokKeluarResource\Pages;
use App\Models\StokKeluar;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class StokKeluarResource extends Resource
{
    protected static ?string $model = StokKeluar::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-up-on-square-stack';
    protected static ?string $navigationGroup = 'Manajemen Stok';
    protected static ?int $navigationSort = 3;

    public static function canAccess(): bool
    {
        return auth()->user()->hasRole('karyawan');
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('barang_id')
                    ->relationship('barang', 'nama')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->live() // Membuat field ini reaktif
                    ->afterStateUpdated(function ($state, callable $set) {
                        // Otomatis isi harga jual terakhir sebagai saran
                        $stokKeluarTerakhir = StokKeluar::where('barang_id', $state)->latest()->first();
                        $set('harga_jual', $stokKeluarTerakhir?->harga_jual ?? 0);
                    })
                    ->label('Nama Barang'),

                Forms\Components\TextInput::make('jumlah')
                    ->required()
                    ->numeric()
                    ->live(onBlur: true) // Membuat field ini reaktif
                    ->afterStateUpdated(function ($state, callable $get, callable $set) {
                        // Hitung total harga saat jumlah diubah
                        $hargaJual = $get('harga_jual') ?? 0;
                        $set('total_harga', $state * $hargaJual);
                    }),

                Forms\Components\TextInput::make('harga_jual')
                    ->label('Harga Jual per Satuan')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->live(onBlur: true) // Membuat field ini reaktif
                    ->afterStateUpdated(function ($state, callable $get, callable $set) {
                        // Hitung total harga saat harga jual diubah
                        $jumlah = $get('jumlah') ?? 0;
                        $set('total_harga', $state * $jumlah);
                    }),

                // Field ini sekarang akan ter-update otomatis
                Forms\Components\TextInput::make('total_harga')
                    ->label('Total Harga')
                    ->numeric()
                    ->prefix('Rp')
                    ->disabled()
                    ->dehydrated(false), // Nilai hanya untuk display, dihitung ulang di Observer

                Forms\Components\DatePicker::make('tanggal_keluar')
                    ->required()
                    ->default(now()),

                // =========================================================
                // INILAH PERBAIKAN UTAMA UNTUK ERROR SQL
                // =========================================================
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->default(auth()->id())
                    ->disabled()
                    ->dehydrated() // 👈 TAMBAHKAN INI
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('barang.nama')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('jumlah')->numeric()->sortable(),
                Tables\Columns\TextColumn::make('tanggal_keluar')->date()->sortable(),
                Tables\Columns\TextColumn::make('user.name')->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStokKeluars::route('/'),
            'create' => Pages\CreateStokKeluar::route('/create'),
            'edit' => Pages\EditStokKeluar::route('/{record}/edit'),
        ];
    }
}
