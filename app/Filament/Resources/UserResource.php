<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Actions\BulkActionGroup; // Pastikan ini diimpor jika menggunakan secara spesifik

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationGroup = 'Pengaturan Akun'; 
    
    protected static ?string $navigationLabel = 'Pengguna'; 
    protected static ?string $navigationIcon = 'heroicon-o-users'; 

    protected static ?string $modelLabel = 'Pengguna';
    protected static ?string $pluralModelLabel = 'Daftar Pengguna';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Lengkap') 
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->label('Email') 
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Forms\Components\DateTimePicker::make('email_verified_at')
                    ->label('Email Diverifikasi Pada') 
                    ->hiddenOn('create'),
                Forms\Components\TextInput::make('password')
                    ->label('Password') 
                    ->password()
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->dehydrateStateUsing(fn (string $state): string => bcrypt($state))
                    ->dehydrated(fn (?string $state): bool => filled($state))
                    ->maxLength(255),

                Forms\Components\TextInput::make('department')
                    ->label('Departemen') 
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->label('Telepon') 
                    ->maxLength(255),
                Forms\Components\TextInput::make('nip')
                    ->label('NIP') 
                    ->maxLength(255),
                Forms\Components\TextInput::make('position')
                    ->label('Jabatan') 
                    ->maxLength(255),
                Forms\Components\TextInput::make('first_name')
                    ->label('Nama Depan') 
                    ->maxLength(255)
                    ->hiddenOn('create'),
                Forms\Components\TextInput::make('last_name')
                    ->label('Nama Belakang') 
                    ->maxLength(255)
                    ->hiddenOn('create'),
                Forms\Components\DatePicker::make('tanggal_lahir')
                    ->label('Tanggal Lahir'), 

                Forms\Components\Select::make('roles')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable()
                    ->label('Peran (Roles)') 
                    ->hidden(fn () => !auth()->user()->hasRole('admin'))
                    ->disabled(fn () => !auth()->user()->hasRole('admin')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Lengkap') 
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email') 
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->label('Peran') 
                    ->badge()
                    ->color('primary')
                    ->searchable(),
                Tables\Columns\TextColumn::make('department')
                    ->label('Departemen') 
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('position')
                    ->label('Jabatan') 
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada') 
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui Pada') 
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Tambahkan filter jika diperlukan
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([ // Menggunakan BulkActionGroup dari import
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Tidak ada pengguna yang terdaftar.'); 
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'), // <--- PERBAIKAN DI SINI
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    // Metode otorisasi
    public static function canViewAny(): bool
    {
        return auth()->user()->hasRole('admin');
    }

    public static function canCreate(): bool
    {
        return auth()->user()->hasRole('admin');
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()->hasRole('admin');
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()->hasRole('admin');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasRole('admin');
    }
}
