<?php

namespace App\Filament\Admin\Widgets;

use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn; 
use Filament\Tables\Columns\IconColumn;  // Tidak lagi diperlukan jika hanya SVG inline
use Filament\Tables\Actions\Action;      
use App\Models\LeaveRequest;             
use Illuminate\Database\Eloquent\Builder; 
use App\Filament\Resources\LeaveRequestResource; 
use Illuminate\Support\HtmlString;       // Tidak lagi diperlukan jika menggunakan .view()
use Carbon\Carbon; 

class LatestPendingLeaveRequests extends BaseWidget
{
    protected static ?string $heading = 'Permintaan Izin Menunggu Persetujuan Terbaru'; 
    protected static ?int $sort = 4; 
    protected array|string|int $columnSpan = 'full'; 
    protected static ?string $pollingInterval = '10s'; 

    public static function canView(): bool
    {
        return auth()->user()->hasAnyRole(['admin', 'atasan']); 
    }

    protected function getTableQuery(): Builder
    {
        $query = LeaveRequest::query()
            ->with('user') 
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->limit(5); 

        return $query;
    }

    protected function getTableColumns(): array
    {
        return [
            // Kolom "Detail Permintaan" akan menggunakan Blade view kustom
            TextColumn::make('details')
                ->label('Detail Permintaan')
                ->view('filament.admin.widgets.leave-request-details-column') // <<< MENGGUNAKAN BLADE VIEW KUSTOM
                ->grow(false), 

            // Kolom Status (Badge)
            BadgeColumn::make('status')
                ->label('Status')
                ->colors([
                    'warning' => 'pending',
                    'success' => 'approved',
                    'danger' => 'rejected',
                ])
                ->alignEnd(), 

            // Kolom Aksi (Tombol Proses) akan menggunakan Blade view kustom
            TextColumn::make('actions') // Nama kolom lebih pendek
                ->label('Aksi')
                ->view('filament.admin.widgets.leave-request-action-column') // <<< MENGGUNAKAN BLADE VIEW KUSTOM
                ->extraAttributes([
                    'class' => 'flex justify-end pr-2' 
                ]),
        ];
    }
    
    protected function getTableActions(): array
    {
        return [
            Action::make('see_all')
                ->label('Lihat Semua')
                ->url(LeaveRequestResource::getUrl('index', ['activeTab' => 'pending'])) 
                ->icon('heroicon-o-eye'),
        ];
    }
}
