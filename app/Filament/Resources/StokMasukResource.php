<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StokMasukResource\Pages;
use App\Models\StokMasuk;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class StokMasukResource extends Resource
{
    protected static ?string $model = StokMasuk::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-down-on-square-stack';
    protected static ?string $navigationGroup = 'Manajemen Stok';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Pilihan barang dari tabel 'barangs'
                Forms\Components\Select::make('barang_id')
                    ->relationship('barang', 'nama')
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\TextInput::make('jumlah')
                    ->required()
                    ->numeric(),
                Forms\Components\DatePicker::make('tanggal_masuk')
                    ->required()
                    ->default(now()),
                // User pencatat diisi otomatis
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
                // Menampilkan nama barang dari relasi
                Tables\Columns\TextColumn::make('barang.nama')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('jumlah')->numeric()->sortable(),
                Tables\Columns\TextColumn::make('tanggal_masuk')->date()->sortable(),
                // Menampilkan nama user dari relasi
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
            'index' => Pages\ListStokMasuks::route('/'),
            'create' => Pages\CreateStokMasuk::route('/create'),
            'edit' => Pages\EditStokMasuk::route('/{record}/edit'),
        ];
    }
}