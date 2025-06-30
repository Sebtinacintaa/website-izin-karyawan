<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\LeaveRequest;
use Illuminate\Support\Facades\Auth;

    class PendingLeaveRequestsOverview extends BaseWidget
    {
        protected function getStats(): array
        {
            $user = Auth::user();

            // Asumsi sederhana: Atasan bisa melihat semua izin pending.
            // Anda bisa menyesuaikannya untuk hanya melihat izin dari bawahan langsung:
            // if ($user->hasRole('atasan')) {
            //     // Implementasikan logika untuk mendapatkan ID bawahan
            //     // Contoh: $teamMemberIds = $user->subordinates->pluck('id');
            //     // $pendingCount = LeaveRequest::whereIn('user_id', $teamMemberIds)->where('status', 'pending')->count();
            // }

            $pendingCount = LeaveRequest::where('status', 'pending')->count();
            $approvedCount = LeaveRequest::where('status', 'approved')->count();
            $rejectedCount = LeaveRequest::where('status', 'rejected')->count();

            return [
                Stat::make('Izin Menunggu Saya', $pendingCount)
                    ->description('Permintaan izin yang perlu Anda proses')
                    ->descriptionIcon('heroicon-m-inbox')
                    ->color('warning'),
                Stat::make('Izin Disetujui Tim', $approvedCount)
                    ->description('Jumlah izin yang sudah disetujui')
                    ->descriptionIcon('heroicon-m-check-circle')
                    ->color('success'),
                Stat::make('Izin Ditolak Tim', $rejectedCount)
                    ->description('Jumlah izin yang ditolak')
                    ->descriptionIcon('heroicon-m-x-circle')
                    ->color('danger'),
            ];
        }
    }
    