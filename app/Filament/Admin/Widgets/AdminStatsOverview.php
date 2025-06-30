<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;
use App\Models\LeaveRequest;

    class AdminStatsOverview extends BaseWidget
    {
        protected function getStats(): array
        {
            return [
                Stat::make('Total Pengguna', User::count())
                    ->description('Jumlah pengguna terdaftar')
                    ->descriptionIcon('heroicon-m-arrow-trending-up')
                    ->color('success'),
                Stat::make('Total Izin Diajukan', LeaveRequest::count())
                    ->description('Total seluruh permintaan izin')
                    ->descriptionIcon('heroicon-m-document-text')
                    ->color('info'),
                Stat::make('Izin Menunggu Persetujuan', LeaveRequest::where('status', 'pending')->count())
                    ->description('Permintaan izin yang belum diproses')
                    ->descriptionIcon('heroicon-m-clock')
                    ->color('warning'),
            ];
        }
    }
    