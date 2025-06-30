<?php

namespace App\Filament\Resources; // <<< PASTIKAN INI ADALAH NAMESPACE YANG BENAR

use App\Filament\Resources\LeaveRequestResource\Pages; // Corrected namespace for Pages
use App\Models\LeaveRequest;
use Filament\Forms; // Import correct Forms namespace
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables; // Import correct Tables namespace
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Models\User; 
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\BadgeColumn; // Import correct BadgeColumn namespace

class LeaveRequestResource extends Resource
{
    protected static ?string $model = LeaveRequest::class;
    
    protected static ?string $navigationGroup = 'Manajemen Izin'; 

    protected static ?string $navigationLabel = 'Permintaan Izin'; 
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $modelLabel = 'Permintaan Izin';
    protected static ?string $pluralModelLabel = 'Daftar Permintaan Izin';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id') // Corrected Forms\Components
                    ->relationship('user', 'name')
                    ->label('Karyawan')
                    ->required()
                    ->default(fn () => auth()->id())
                    ->disabled(fn () => !auth()->user()->hasRole('admin')),
                Forms\Components\Select::make('leave_type') // Corrected Forms\Components
                    ->options([
                        'Cuti Tahunan' => 'Cuti Tahunan',
                        'Izin Sakit' => 'Izin Sakit',
                        'Izin Pribadi' => 'Izin Pribadi',
                        'Lain-lain' => 'Lain-lain',
                        'Cuti Melahirkan' => 'Cuti Melahirkan', 
                    ])
                    ->label('Jenis Izin')
                    ->required(),
                Forms\Components\DatePicker::make('start_date') // Corrected Forms\Components
                    ->label('Tanggal Mulai')
                    ->required(),
                Forms\Components\DatePicker::make('end_date') // Corrected Forms\Components
                    ->label('Tanggal Akhir')
                    ->required(),
                Forms\Components\Textarea::make('reason') // Corrected Forms\Components
                    ->label('Alasan')
                    ->required()
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\Select::make('status') // Corrected Forms\Components
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                    ])
                    ->label('Status')
                    ->required()
                    ->default('pending')
                    ->visible(fn () => auth()->user()->hasAnyRole(['admin', 'atasan'])),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name') // Corrected Tables\Columns
                    ->label('Karyawan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('leave_type') // Corrected Tables\Columns
                    ->label('Jenis Izin')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_date') // Corrected Tables\Columns
                    ->label('Mulai Izin')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date') // Corrected Tables\Columns
                    ->label('Akhir Izin')
                    ->date()
                    ->sortable(),
                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger' => 'rejected',
                    ])
                    ->default('secondary')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at') // Corrected Tables\Columns
                    ->label('Diajukan Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status') // Corrected Tables\Filters
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                    ])
                    ->label('Filter Status'),
                Tables\Filters\SelectFilter::make('leave_type') // Corrected Tables\Filters
                    ->options([
                        'Cuti Tahunan' => 'Cuti Tahunan',
                        'Izin Sakit' => 'Izin Sakit',
                        'Izin Pribadi' => 'Izin Pribadi',
                        'Lain-lain' => 'Lain-lain',
                        'Cuti Melahirkan' => 'Cuti Melahirkan',
                    ])
                    ->label('Filter Jenis Izin'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(), // Corrected Tables\Actions
                Tables\Actions\DeleteAction::make(), // Corrected Tables\Actions
                Action::make('approve')
                    ->label('Setujui')
                    ->action(function (LeaveRequest $record) {
                        $record->update(['status' => 'approved']);
                        \Filament\Notifications\Notification::make()
                            ->title('Permintaan izin disetujui!')
                            ->success()
                            ->send();
                    })
                    ->visible(fn (LeaveRequest $record) => $record->status === 'pending' && auth()->user()->hasAnyRole(['admin', 'atasan']))
                    ->color('success')
                    ->icon('heroicon-o-check-circle'),
                Action::make('reject')
                    ->label('Tolak')
                    ->action(function (LeaveRequest $record) {
                        $record->update(['status' => 'rejected']);
                        \Filament\Notifications\Notification::make()
                            ->title('Permintaan izin ditolak!')
                            ->danger()
                            ->send();
                    })
                    ->visible(fn (LeaveRequest $record) => $record->status === 'pending' && auth()->user()->hasAnyRole(['admin', 'atasan']))
                    ->color('danger')
                    ->icon('heroicon-o-x-circle'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([ // Corrected Tables\Actions
                    Tables\Actions\DeleteBulkAction::make(), // Corrected Tables\Actions
                ]),
            ]);
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
            'index' => Pages\ListLeaveRequests::route('/'), // Corrected Pages\
            'create' => Pages\CreateLeaveRequest::route('/create'), // Corrected Pages\
            'edit' => Pages\EditLeaveRequest::route('/{record}/edit'), // Corrected Pages\
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (auth()->user()->hasRole('admin')) {
            return $query;
        }

        if (auth()->user()->hasRole('atasan')) {
            return $query; 
        }

        if (auth()->user()->hasRole('karyawan')) {
            return $query->where('user_id', auth()->id());
        }
        return $query;
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->hasAnyRole(['admin', 'atasan', 'karyawan']);
    }

    public static function canCreate(): bool
    {
        return auth()->user()->hasRole('karyawan');
    }

    public static function canEdit(Model $record): bool
    {
        $user = auth()->user();
        if ($user->hasRole('admin')) return true;
        if ($user->hasRole('atasan') && $record->status === 'pending') return true;
        if ($user->hasRole('karyawan') && $record->user_id === $user->id && $record->status === 'pending') return true;
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        $user = auth()->user();
        if ($user->hasRole('admin')) return true;
        if ($user->hasRole('karyawan') && $record->user_id === $user->id && $record->status === 'pending') return true;
        return false;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasAnyRole(['admin', 'atasan']); 
    }
}
