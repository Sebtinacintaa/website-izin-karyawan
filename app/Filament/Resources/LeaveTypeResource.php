<?php

namespace App\Filament\Resources; // Pastikan namespace ini benar

use App\Filament\Resources\LeaveTypeResource\Pages;
use App\Models\LeaveType; // Pastikan model LeaveType Anda di-import
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model; // Tambahkan ini untuk type hinting Model

class LeaveTypeResource extends Resource
{
    protected static ?string $model = LeaveType::class;

    // Properti ini dihapus karena ikon diatur di item itu sendiri, bukan di grup.
    // protected static ?string $navigationIcon = 'heroicon-o-calendar-days'; 
    protected static ?string $navigationGroup = 'Manajemen Izin'; // Kelompokkan di bawah grup "Manajemen Izin"
    protected static ?int $navigationSort = 1; // Urutan di dalam grup (muncul paling atas)

    // <<< JUDUL NAVIGASI & IKON UNTUK ITEM INI (di sidebar) >>>
    protected static ?string $navigationLabel = 'Jenis Izin'; 
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days'; // Contoh ikon kalender
    // <<< AKHIR BAGIAN >>>

    // <<< JUDUL UTAMA HALAMAN & JAMAK >>>
    protected static ?string $modelLabel = 'Jenis Izin'; // Label tunggal untuk model
    protected static ?string $pluralModelLabel = 'Jenis-jenis Izin'; // Label jamak untuk model
    // <<< AKHIR BAGIAN >>>

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name') 
                    ->label('Nama Jenis Izin') // Label dalam Bahasa Indonesia
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true) // Pastikan nama jenis izin unik
                    ->placeholder('Contoh: Cuti Tahunan, Izin Sakit'),
                Forms\Components\TextInput::make('days_allotted') 
                    ->label('Jumlah Hari Dialokasikan (Opsional)') // Label dalam Bahasa Indonesia
                    ->numeric()
                    ->nullable()
                    ->default(null)
                    ->helperText('Jumlah hari standar yang dialokasikan untuk jenis izin ini per tahun.'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name') 
                    ->label('Nama Jenis Izin') // Label dalam Bahasa Indonesia
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('days_allotted') 
                    ->label('Hari Dialokasikan') // Label dalam Bahasa Indonesia
                    ->placeholder('Tidak Terbatas') // Jika null, tampilkan ini
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at') 
                    ->label('Dibuat Pada') // Label dalam Bahasa Indonesia
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at') 
                    ->label('Diperbarui Pada') // Label dalam Bahasa Indonesia
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Tambahkan filter jika diperlukan
            ])
            ->actions([
                Tables\Actions\EditAction::make(), // Corrected Tables\Actions
                Tables\Actions\DeleteAction::make(), // Corrected Tables\Actions
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([ // Corrected Tables\Actions
                    Tables\Actions\DeleteBulkAction::make(), // Corrected Tables\Actions
                ]),
            ])
            ->emptyStateHeading('Tidak ada jenis izin yang terdaftar.'); 
    }

    public static function getRelations(): array
    {
        return [
            // Tambahkan Relation Managers jika ada relasi (misal: users yang mengambil cuti jenis ini)
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLeaveTypes::route('/'), 
            'create' => Pages\CreateLeaveType::route('/create'), // Label tombol Create akan diatur di ListLeaveTypes.php
            'edit' => Pages\EditLeaveType::route('/{record}/edit'), 
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->hasRole('admin');
    }

    public static function canCreate(): bool
    {
        return auth()->user()->hasRole('admin');
    }

    public static function canEdit(Model $record): bool // Gunakan Illuminate\Database\Eloquent\Model
    {
        return auth()->user()->hasRole('admin');
    }

    public static function canDelete(Model $record): bool // Gunakan Illuminate\Database\Eloquent\Model
    {
        return auth()->user()->hasRole('admin');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasRole('admin');
    }
}
