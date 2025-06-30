<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Admin;
use App\Models\User;
use App\Models\LeaveRequest;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class StatsOverviewWidget extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Jumlah Pengguna', User::count())
                ->description('Termasuk semua karyawan')
                ->color('primary'),

            Card::make('Jumlah Admin', Admin::count())
                ->description('Akun dengan akses admin')
                ->color('info'),

            Card::make('Total Pengajuan Cuti', LeaveRequest::count())
                ->description('Semua pengajuan cuti')
                ->color('warning'),

            Card::make('Cuti Disetujui', LeaveRequest::where('status', 'disetujui')->count())
                ->description('Pengajuan cuti yang disetujui')
                ->color('success'),

            Card::make('Cuti Ditolak', LeaveRequest::where('status', 'ditolak')->count())
                ->description('Pengajuan cuti yang ditolak')
                ->color('danger'),
        ];
    }
}
