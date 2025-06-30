<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

    class TeamMembersOverview extends BaseWidget
    {
        protected function getStats(): array
        {
            $user = Auth::user();
            $teamMembersCount = 0;

            if ($user->hasRole('atasan') && $user->department) {
                // Asumsi: bawahan berada di departemen yang sama dengan atasan,
                // dan kita tidak menghitung atasan itu sendiri.
                $teamMembersCount = User::where('department', $user->department)
                                        ->where('id', '!=', $user->id)
                                        ->count();
            }

            return [
                Stat::make('Jumlah Anggota Tim', $teamMembersCount)
                    ->description('Karyawan di bawah pengawasan Anda')
                    ->descriptionIcon('heroicon-m-user-group')
                    ->color('primary'),
                // Anda bisa tambahkan statistik lain terkait tim di sini
            ];
        }
    }
    