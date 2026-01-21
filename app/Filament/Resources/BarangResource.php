<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BarangResource\Pages;
use App\Models\Barang;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Traits\SendsWhatsAppNotifications;
use Filament\Notifications\Notification;

class BarangResource extends Resource
{
    use SendsWhatsAppNotifications;

    protected static ?string $model = Barang::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    protected static ?string $navigationGroup = 'Manajemen Stok';
    protected static ?string $navigationLabel = 'Data Barang';
    protected static ?int $navigationSort = 1;


    public static function canEdit(Model $record): bool
    {
        return auth()->user()->hasRole('admin');
    }

    public static function canCreate(): bool
    {
        return auth()->user()->hasRole('admin');
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()->hasRole('admin');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Barang')
                    ->schema([
                        Forms\Components\TextInput::make('nama')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: Kertas A4'),

                        // Satuan diubah menjadi Select
                        Forms\Components\Select::make('satuan')
                            ->options([
                                'Pcs' => 'Pcs (Pieces)',
                                'Kg' => 'Kg (Kilogram)',
                                'Liter' => 'Liter',
                                'Box' => 'Box',
                                'Botol' => 'Botol',
                                'Rim' => 'Rim',
                                'Unit' => 'Unit',
                            ])
                            ->required()
                            ->searchable() // Biar bisa cari satuan dengan mengetik
                            ->placeholder('Pilih satuan'),

                        Forms\Components\TextInput::make('stok_maksimal')
                            ->label('Batas Stok Maksimal')
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->helperText('Batas stok untuk memicu notifikasi peringatan.'),

                        // Stok sekarang diaktifkan kembali untuk input manual
                        Forms\Components\TextInput::make('stok_sekarang')
                            ->label('Stok Saat Ini')
                            ->numeric()
                            ->required() // Diwajibkan agar tidak kosong
                            ->default(0)
                            ->placeholder('Masukkan jumlah stok awal')
                            ->helperText('Masukkan Stok Awal Untuk Barang Ini.'),
                    ])->columns(2), // Membuat tampilan menjadi 2 kolom agar lebih rapi
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')->searchable(),
                Tables\Columns\TextColumn::make('satuan')->searchable(),
                Tables\Columns\TextColumn::make('stok_sekarang')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('stok_maksimal')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                Tables\Actions\Action::make('send_notification')
                    ->label('Kirim Notif WA')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Kirim Notifikasi Stok')
                    ->modalDescription('Anda yakin ingin mengirim notifikasi stok menipis untuk barang ini ke WhatsApp?')
                    ->action(function (Barang $record) {
                        // This call will now work correctly
                        (new static())->sendWhatsAppNotification($record);

                        Notification::make()
                            ->title('Notifikasi Terkirim')
                            ->body('Pesan peringatan stok untuk ' . $record->nama . ' telah dikirim ke WhatsApp.')
                            ->success()
                            ->send();
                    }),

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
            'index' => Pages\ListBarangs::route('/'),
            'create' => Pages\CreateBarang::route('/create'),
            'edit' => Pages\EditBarang::route('/{record}/edit'),
        ];
    }
}
