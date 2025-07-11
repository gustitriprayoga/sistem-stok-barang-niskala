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


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('barang_id') // ... kode sebelumnya
                    ->reactive() // Tambahkan ini agar form bereaksi pada perubahan
                    ->afterStateUpdated(function ($state, callable $set) {
                        // Ambil harga terakhir dari barang ini jika ada, untuk dijadikan saran
                        $stokKeluarTerakhir = StokKeluar::where('barang_id', $state)->latest()->first();
                        if ($stokKeluarTerakhir) {
                            $set('harga_jual', $stokKeluarTerakhir->harga_jual);
                        }
                    }),
                Forms\Components\TextInput::make('jumlah')
                    ->required()
                    ->numeric()
                    ->reactive(), // Tambahkan ini
                Forms\Components\TextInput::make('harga_jual')
                    ->label('Harga Jual per Satuan')
                    ->required()
                    ->numeric()
                    ->prefix('Rp'),
                Forms\Components\TextInput::make('total_harga')
                    ->label('Total Harga')
                    ->numeric()
                    ->prefix('Rp')
                    ->disabled() // Field ini tidak bisa diisi manual
                    ->dehydrated(false),
                Forms\Components\Select::make('barang_id')
                    ->relationship('barang', 'nama')
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\TextInput::make('jumlah')
                    ->required()
                    ->numeric(),
                Forms\Components\DatePicker::make('tanggal_keluar')
                    ->required()
                    ->default(now()),
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->default(auth()->id())
                    ->disabled()
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
