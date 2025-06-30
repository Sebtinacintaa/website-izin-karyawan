<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Support\Facades\Auth;
use App\Filament\Admin\Widgets\AdminStatsOverview;
use App\Filament\Admin\Widgets\PendingLeaveRequestsOverview;
use App\Filament\Admin\Widgets\TeamMembersOverview;
// use App\Filament\Admin\Widgets\LeaveRequestStatusChart; // <<< BARIS INI DIHAPUS/DIKOMENTARI
// use App\Filament\Admin\Widgets\LeaveDistributionByDepartmentChart; // <<< BARIS INI DIHAPUS/DIKOMENTARI
use App\Filament\Admin\Widgets\LatestPendingLeaveRequests;

class AdminDashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home'; 
    protected static string $view = 'filament.admin.pages.admin-dashboard';

    public function getTitle(): string
    {
        $user = Auth::user();
        if ($user && $user->hasRole('admin')) {
            return 'Dashboard Administrator Sistem';
        } elseif ($user && $user->hasRole('atasan')) {
            return 'Dashboard Supervisor Tim Anda';
        }
        return 'Dashboard Aplikasi'; 
    }

    public static function getNavigationLabel(): string
    {
        $user = Auth::user();
        if ($user && $user->hasRole('admin')) {
            return 'Dashboard Admin';
        } elseif ($user && $user->hasRole('atasan')) {
            return 'Dashboard Atasan';
        }
        return 'Dashboard';
    }

    protected function getHeaderWidgets(): array
    {
        $user = Auth::user();

        if ($user && $user->hasRole('admin')) {
            return [
                AdminStatsOverview::class,           // Kartu Statistik Admin
                // LeaveRequestStatusChart::class,      // <<< DIHAPUS DARI SINI
                // LeaveDistributionByDepartmentChart::class, // <<< DIHAPUS DARI SINI
                LatestPendingLeaveRequests::class,   // Tabel Permintaan Izin Pending Terbaru
            ];
        } elseif ($user && $user->hasRole('atasan')) {
            return [
                PendingLeaveRequestsOverview::class, // Kartu Statistik Izin Pending Atasan
                TeamMembersOverview::class,          // Kartu Statistik Anggota Tim Atasan
                // LeaveRequestStatusChart::class,      // <<< DIHAPUS DARI SINI
            ];
        }
        return [];
    }
}
